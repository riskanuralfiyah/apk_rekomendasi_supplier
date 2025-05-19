<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Bahan Baku</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 10pt;
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
            font-size: 16pt;
        }

        .header p {
            color: #7f8c8d;
            margin-top: 0;
            font-size: 9pt;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 9pt;
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
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        th {
            background-color: #4B49AC;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            font-size: 9pt;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
        }

        /* .badge-success {
            background-color: #25d039;
            color: white;
        }

        .badge-danger {
            background-color: #ee2a15;
            color: white;
        } */

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 8pt;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK BAHAN BAKU</h1>
        <p>Periode: {{ $namaBulan[$currentBulan] }} {{ $currentTahun }}</p>
    </div>

    <div class="info-box">
        {{-- <div class="info-row">
            <span class="info-label">Periode: {{ $namaBulan[$currentBulan] }} {{ $currentTahun }}</span>
        </div> --}}
        <div class="info-row">
            <span class="info-label">Tanggal Laporan:</span>
            <span>{{ now()->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Bahan Baku:</span>
            <span>{{ count($laporans) }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="12%">Periode</th>
                <th class="text-left">Bahan Baku</th>
                <th width="8%">Satuan</th>
                <th width="10%">Stok Awal</th>
                <th width="12%">Stok Masuk</th>
                <th width="12%">Stok Keluar</th>
                <th width="10%">Sisa Stok</th>
                {{-- <th width="12%">Status</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp
            @foreach ($laporans as $index => $laporan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $namaBulan[$laporan->bulan] }} {{ $laporan->tahun }}</td>
                    <td class="text-left">{{ $laporan->bahanBaku->nama_bahan_baku }}</td>
                    <td class="text-center">{{ $laporan->satuan }}</td>
                    <td class="text-right">{{ number_format($laporan->stok_awal) }}</td>
                    <td class="text-right">{{ number_format($laporan->total_stok_masuk) }}</td>
                    <td class="text-right">{{ number_format($laporan->total_stok_keluar) }}</td>
                    <td class="text-right">{{ number_format($laporan->sisa_stok) }}</td>
                    {{-- <td class="text-center">
                        <span class="badge badge-{{ $laporan->sisa_stok <= 10 ? 'danger' : 'success' }}">
                            {{ $laporan->sisa_stok <= 10 ? 'KRITIS' : 'AMAN' }}
                        </span>
                    </td> --}}
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
