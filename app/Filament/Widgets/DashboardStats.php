<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Invoice;
use App\Models\Client;

class DashboardStats extends BaseWidget
{
    protected  string|int|array $columnSpan = 'full';
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Invoice', Invoice::count())
                ->description('Jumlah invoice yang sudah dibuat')
                ->icon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Total Klien', Client::count())
                ->description('Klien terdaftar di sistem')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Total Tagihan', 'Rp ' . number_format(Invoice::sum('total'), 0, ',', '.'))
                ->description('Akumulasi seluruh invoice')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
        ];
    }
}
