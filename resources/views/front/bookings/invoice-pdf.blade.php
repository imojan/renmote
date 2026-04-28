<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->payment->invoice_number }}</title>
    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.45;
        }

        .header {
            border-bottom: 2px solid #0f4ea9;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 700;
            color: #0f4ea9;
            letter-spacing: .6px;
            margin: 0;
        }

        .brand-sub {
            color: #475569;
            font-size: 11px;
            margin-top: 2px;
        }

        .header-grid {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }

        .header-grid td {
            vertical-align: top;
            width: 50%;
        }

        .meta-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px;
        }

        .meta-row {
            margin-bottom: 4px;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .label {
            color: #64748b;
            display: inline-block;
            min-width: 110px;
        }

        .value {
            font-weight: 700;
            color: #0f172a;
        }

        .section-title {
            margin: 16px 0 8px;
            font-size: 13px;
            color: #0f4ea9;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        table.data th,
        table.data td {
            border-bottom: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }

        table.data th {
            background: #eff6ff;
            color: #1e3a8a;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
        }

        table.data tr:last-child td {
            border-bottom: 0;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .highlight {
            color: #0f4ea9;
            font-weight: 700;
        }

        .note-box {
            margin-top: 14px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            border-radius: 6px;
            padding: 10px;
            color: #1e3a8a;
            font-size: 11px;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            color: #64748b;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $snapshot = $booking->delivery_address_snapshot ?? [];
    @endphp

    <div class="header">
        <h1 class="brand-title">RENMOTE INVOICE</h1>
        <div class="brand-sub">Motorcycle Rental Receipt</div>

        <table class="header-grid" cellspacing="0" cellpadding="0">
            <tr>
                <td style="padding-right: 8px;">
                    <div class="meta-box">
                        <div class="meta-row"><span class="label">No. Invoice</span><span class="value">{{ $booking->payment->invoice_number }}</span></div>
                        <div class="meta-row"><span class="label">No. Booking</span><span class="value">#{{ $booking->id }}</span></div>
                        <div class="meta-row"><span class="label">Tanggal</span><span class="value">{{ $booking->created_at->format('d M Y H:i') }}</span></div>
                    </div>
                </td>
                <td style="padding-left: 8px;">
                    <div class="meta-box">
                        <div class="meta-row"><span class="label">Penyewa</span><span class="value">{{ $booking->user->name }}</span></div>
                        <div class="meta-row"><span class="label">Email</span><span class="value">{{ $booking->user->email }}</span></div>
                        <div class="meta-row"><span class="label">Vendor</span><span class="value">{{ $booking->vehicle->vendor->store_name }}</span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Rincian Penyewaan</div>
    <table class="data" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Item</th>
                <th>Periode</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $booking->vehicle->name }}</strong><br>
                    <span style="color:#64748b;">Metode ambil: {{ $booking->fulfillment_method === 'delivery' ? 'Diantar' : 'Ambil di outlet' }}</span>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}<br>
                    s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Pembayaran</div>
    <table class="data" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>Total Sewa</td>
                <td class="text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>DP Dibayar ({{ strtoupper($booking->payment->payment_method) }})</td>
                <td class="text-right highlight">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Sisa Bayar Saat Pengambilan</td>
                <td class="text-right">Rp {{ number_format(max(0, $booking->total_price - $booking->payment->amount), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="total">Status Bukti Pembayaran</td>
                <td class="text-right total">{{ strtoupper(str_replace('_', ' ', $booking->payment->proof_status)) }}</td>
            </tr>
        </tbody>
    </table>

    @if($booking->fulfillment_method === 'delivery')
        <div class="section-title">Alamat Pengantaran</div>
        <div class="meta-box">
            <div class="meta-row"><span class="label">Label</span><span class="value">{{ $snapshot['label'] ?? optional($booking->address)->label ?? '-' }}</span></div>
            <div class="meta-row"><span class="label">Alamat</span><span class="value">{{ $snapshot['street'] ?? optional($booking->address)->street ?? '-' }}</span></div>
            <div class="meta-row"><span class="label">Kota</span><span class="value">{{ $snapshot['city'] ?? optional($booking->address)->city ?? '-' }}</span></div>
            <div class="meta-row"><span class="label">Kode Pos</span><span class="value">{{ $snapshot['postal_code'] ?? optional($booking->address)->postal_code ?? '-' }}</span></div>
        </div>
    @endif

    <div class="note-box">
        Dokumen ini adalah invoice resmi dari Renmote dan dapat digunakan sebagai bukti transaksi. Simpan file ini untuk arsip penyewaan Anda.
    </div>

    <div class="footer">
        Renmote Motorcycle Rental • Generated at {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
