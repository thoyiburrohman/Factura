<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Invoice per Bulan (Tahun Ini)';
    protected static ?string $pollingInterval = null;
    protected  string|int|array $columnSpan = 'full';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        // Mengambil tahun saat ini, misal: 2025
        $currentYear = Carbon::now()->year;

        // Mengambil data dari database dengan filter TAHUN INI
        $data = Invoice::query()
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw("TO_CHAR(date, 'YYYY-MM') as year_month")
            )
            // Filter diubah menjadi whereYear untuk mengambil data di tahun ini saja
            ->whereYear('date', $currentYear)
            ->groupBy('year_month')
            ->orderBy('year_month')
            ->get()
            ->keyBy('year_month');

        $labels = [];
        $values = [];

        // Membuat periode dari Januari hingga Desember untuk tahun ini
        $period = Carbon::createFromDate($currentYear)->startOfYear()->toPeriod(Carbon::createFromDate($currentYear)->endOfYear(), '1 month');

        foreach ($period as $date) {
            // Format 'YYYY-MM' untuk kunci pencarian, contoh: '2025-07'
            $yearMonth = $date->format('Y-m');

            // Format 'M Y' untuk label di chart (Contoh: Jul 2025)
            $labels[] = $date->format('M'); // Cukup 'Jan', 'Feb', dst.

            // Ambil data. Jika bulan tersebut belum terjadi atau tidak ada data, nilainya akan 0.
            $values[] = $data->get($yearMonth)->count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Invoice',
                    'data' => $values,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        $data = $this->getData();

        $values = $data['datasets'][0]['data'];
        $maxValue = empty($values) ? 0 : max($values);
        return [
            'scales' => [
                'y' => [
                    // Opsi untuk memastikan sumbu Y selalu dimulai dari 0
                    'beginAtZero' => true,
                    'suggestedMax' => $maxValue < 10 ? 10 : $maxValue + 2,
                    'ticks' => [
                        // Opsi inilah yang memaksa sumbu Y untuk menampilkan bilangan bulat
                        'precision' => 0,
                    ],
                ],
            ],
            'animation' => [
                'duration' => 500,
            ],
        ];
    }
}
