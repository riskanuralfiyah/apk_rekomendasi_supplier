<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Rekomendasi Supplier</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4B49AC;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .header p {
            color: #7f8c8d;
            margin-top: 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th {
            background-color: #4B49AC;
            color: white;
            text-align: left;
            padding: 12px;
            font-weight: bold;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }
        .badge-primary {
            background-color: #4B49AC;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL REKOMENDASI SUPPLIER</h1>
        <p>Sistem Rekomendasi Pemilihan Supplier</p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Tanggal Laporan:</span>
            <span>{{ now()->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Supplier:</span>
            <span>{{ $hasilRekomendasi->count() }} Supplier</span>
        </div>
        <div class="info-row">
            <span class="info-label">Supplier Terbaik:</span>
            <span>{{ $hasilRekomendasi->first()->supplier->nama_supplier ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Skor Tertinggi:</span>
            <span>{{ $hasilRekomendasi->first()->skor_akhir ?? '-' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th width="35%">Nama Supplier</th>
                <th width="30%">No. Telpon</th>
                <th width="15%">Skor</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilRekomendasi as $result)
                <tr>
                    <td>
                        @if($result->peringkat == 1)
                            <span class="badge badge-primary">{{ $result->peringkat }}</span>
                        @else
                            {{ $result->peringkat }}
                        @endif
                    </td>
                    <td>{{ $result->supplier->nama_supplier }}</td>
                    <td>
                        {{ $result->supplier->no_telpon }}
                    </td>
                    <td>{{ number_format($result->skor_akhir, 2) }}</td>
                    <td>
                        @if($result->peringkat <= 3)
                            Direkomendasikan
                        @else
                            Alternatif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis pada {{ now()->format('d F Y H:i') }}</p>
        <p>&copy; {{ date('Y') }} Riska Mebel. All rights reserved.</p>
    </div>
</body>
</html>