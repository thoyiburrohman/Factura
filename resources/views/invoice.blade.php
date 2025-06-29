<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        .header {
            top: 0px;
        }

        .footer {
            bottom: 0px;
            font-size: 10px;
            color: #777;
            margin-bottom: 1rem;
        }

        .invoice-header {
            margin-bottom: 40px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }

        .company-details,
        .invoice-details,
        .bill-to {
            width: 100%;
            margin-bottom: 20px;
        }

        .company-details {
            text-align: right;
        }

        .bill-to {
            text-align: left;
        }

        .invoice-details table {
            width: 40%;
            float: right;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-section {
            width: 40%;
            float: right;
        }

        .total-section table {
            width: 100%;
        }

        .total-section th {
            text-align: right;
            width: 50%;
        }

        .total-section td {
            text-align: right;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .watermark {
            position: fixed;
            /* Membuatnya tetap di posisi yang sama */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Memutar dan memposisikan di tengah */
            z-index: 10;
            /* Pastikan di atas beberapa elemen, tapi di bawah konten utama jika perlu */

            font-size: 120px;
            /* Ukuran font yang sangat besar */
            font-weight: bold;
            color: rgba(0, 0, 0, 0.1);
            /* Warna semi-transparan */
            opacity: 0.5;
            pointer-events: none;
            /* Agar tidak bisa di-klik atau di-select */
            text-align: center;
            width: 100%;
            text-transform: uppercase;
        }

        .watermark-paid {
            color: rgba(22, 163, 74, 0.15);
            /* Warna hijau transparan (success) */
        }

        .watermark-unpaid {
            color: rgba(220, 38, 38, 0.1);
            /* Warna merah transparan (danger) */
        }
    </style>
</head>

<body>
    @if ($invoice->status === 'paid')
        <div class="watermark watermark-paid">Lunas</div>
    @elseif ($invoice->status === 'unpaid')
        <div class="watermark watermark-unpaid">Belum Lunas</div>
    @endif
    <div class="container">
        <div class="invoice-header clearfix">
            <div style="float: left;">
                <h1>INVOICE <span>#{{ $invoice->number ?? 'INV-001' }}</span> </h1>
            </div>
            <div class="company-details" style="float: right;">
                <div style="text-transform: capitalize;"><strong>{{ $invoice->company->name }}</strong></div>
                <div>{{ $invoice->company->address }}</div>
                <div>Email: {{ $invoice->company->email }}</div>
                <div>Telp: {{ $invoice->company->phone }}</div>
            </div>
        </div>

        <div class="bill-to clearfix">
            <div style="float: left;">
                <strong>Ditagihkan kepada:</strong>
                <div style="text-transform: capitalize;">{{ $invoice->client->name ?? 'Nama Pelanggan' }}</div>
                <div>{{ $invoice->client->address ?? 'Nama Pelanggan' }}</div>
                <div>{{ $invoice->client->email ?? 'Nama Pelanggan' }}</div>
            </div>
            <div class="invoice-details" style="float: right;">
                <table>
                    <tr>
                        <th>No. Invoice:</th>
                        <td>#{{ $invoice->number ?? 'INV-001' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Invoice:</th>
                        <td>{{ $invoice->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Jatuh Tempo:</th>
                        <td>{{ $invoice->due_date->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Kuantitas</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                {{-- Asumsi Anda memiliki relasi 'items' di model Invoice --}}
                {{-- @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach --}}

                {{-- Contoh statis jika belum ada relasi --}}
                @foreach ($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <th>Subtotal</th>
                    <td>Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Pajak (11%)</th>
                    <td>Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td><strong>Rp {{ number_format($invoice->grand_total - 11 / 100, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="notes" style="margin-top: 50px;">
            <strong>Catatan:</strong>
            <p>Pembayaran dapat dilakukan melalui transfer ke rekening {{ $invoice->company->bank_name }} No.
                {{ $invoice->company->bank_account_name }} a.n. {{ $invoice->company->bank_account_holder }}.
            </p>
        </div>

    </div>

    <div class="footer">
        Terima kasih atas kerjasama Anda!
    </div>

</body>

</html>
