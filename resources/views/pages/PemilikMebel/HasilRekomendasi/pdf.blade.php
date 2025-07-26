<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Rekomendasi Supplier</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #4B49AC;
            padding-bottom: 8px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 16px;
        }
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4B49AC;
            color: white;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 7px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }
        .badge-primary {
            background-color: #4B49AC;
            color: white;
        }
        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 12px;
            color: #2c3e50;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL REKOMENDASI SUPPLIER</h1>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Tanggal Laporan:</span>
            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Supplier:</span>
            <span>{{ $hasilRekomendasi->count() }} Supplier</span>
        </div>
        <div class="info-row">
            <span class="info-label">Supplier Terbaik:</span>
            <span>{{ optional($hasilRekomendasi->firstWhere('peringkat', 1))->supplier->nama_supplier ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Skor Tertinggi:</span>
            <span>{{ rtrim(rtrim(number_format(optional($hasilRekomendasi->firstWhere('peringkat', 1))->skor_akhir ?? 0, 2), '0'), '.') }}</span>
        </div>
    </div>

    <!-- Bagian Supplier Rekomendasi -->
    <div class="section-title">Supplier Rekomendasi (Peringkat 1–3)</div>
    <table>
        <thead>
            <tr>
                <th width="25%">Peringkat</th>
                <th width="40%">Nama Supplier</th>
                <th width="35%">No. Telpon</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilRekomendasi->where('peringkat', '<=', 3) as $result)
                <tr>
                    <td>
                        @if($result->peringkat == 1)
                            <span class="badge badge-primary">{{ $result->peringkat }}</span>
                        @else
                            {{ $result->peringkat }}
                        @endif
                    </td>
                    <td>{{ $result->supplier->nama_supplier }}</td>
                    <td>{{ $result->supplier->no_telpon }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Bagian Supplier Alternatif -->
    <div class="section-title">Supplier Alternatif (Peringkat > 3)</div>
    <table>
        <thead>
            <tr>
                <th width="25%">Peringkat</th>
                <th width="40%">Nama Supplier</th>
                <th width="35%">No. Telpon</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilRekomendasi->where('peringkat', '>', 3) as $result)
                <tr>
                    <td>{{ $result->peringkat }}</td>
                    <td>{{ $result->supplier->nama_supplier }}</td>
                    <td>{{ $result->supplier->no_telpon }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan otomatis pada {{ now()->format('d F Y H:i') }}</p>
        <p>&copy; {{ date('Y') }} Riska Mebel</p>
    </div>
</body>
</html>
