<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'company_id',
        'number',
        'date',
        'due_date',
        'total',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'total' => 'float',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Bonus: Total dari items (kalau mau override kolom `total`)
    public function getTotalCalculatedAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->unit_price);
    }

    /**
     * Tarif Pajak (PPN). 0.11 merepresentasikan 11%.
     */
    const TAX_RATE = 0.11;

    /**
     * ACCESSOR: Menghitung jumlah pajak (PPN 11%) secara otomatis.
     * Dipanggil dengan: $invoice->tax_amount
     */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->total * 0.11
        );
    }

    /**
     * ACCESSOR: Menghitung total akhir setelah ditambah pajak.
     * Dipanggil dengan: $invoice->grand_total
     */
    protected function grandTotal(): Attribute
    {
        return Attribute::make(
            // Perhatikan bagaimana kita menggunakan accessor tax_amount di sini
            get: fn() => $this->total + $this->tax_amount
        );
    }

    /**
     * Metode ini sekarang menjadi satu-satunya sumber kebenaran
     * untuk membuat nomor invoice baru.
     */
    public static function generateNextInvoiceNumber(): string
    {
        // 1. Tentukan Prefix, Tahun, dan Bulan
        $prefix = 'INV';
        $year = now()->year;
        $month = now()->format('m');

        // 2. Cari invoice terakhir di bulan dan tahun yang sama
        $lastInvoice = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest('id')
            ->first();

        // 3. Tentukan nomor urut berikutnya
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->number, -4));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // 4. Format nomor urut dengan padding nol
        $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 5. Gabungkan dan kembalikan nomor invoice final
        return "{$prefix}/{$year}/{$month}/{$sequence}";
    }


    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            // Hanya set nomor invoice jika belum ada
            if (empty($invoice->number)) {
                // Panggil method baru kita yang terpusat
                $invoice->number = self::generateNextInvoiceNumber();
            }
        });
    }
}
