<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Bahan Baku Toko Riska Mebel</title>
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

        .highlight-info {
            color: #4B49AC;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #4B49AC;
            color: white;
            text-align: center;
            padding: 8px 5px;
            font-weight: bold;
            font-size: 9pt;
        }

        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer {
            margin-top: 20px;
            font-size: 8pt;
            color: #7f8c8d;
            padding-top: 10px;
        }
        
        .closing {
            margin-bottom: 5px;
            line-height: 1.8;
            text-align: justify;
        }
        
        .signature-container {
            float: right;
            width: 250px;
            margin-top: 20px;
        }
        
        .signature {
            text-align: center;
        }
        
        .signature-place {
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .signature-name {
            margin-top: 5px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .signature-position {
            font-size: 11px;
            margin-top: 5px;
        }
        
        .spacer {
            height: 10px;
        }
    </style>
</head>
<body>
    @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Safely handle period display
        $periodLabel = '-';
        if (!empty($currentTahun)) {
            if ($jenisLaporan == 'bahan_baku' && !empty($currentBulan) && isset($namaBulan[$currentBulan])) {
                $periodLabel = $namaBulan[$currentBulan] . ' ' . $currentTahun;
            } else {
                $periodLabel = $currentTahun;
            }
        }
    @endphp

    <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="flex: 0 0 auto;">
            <img src="{{ public_path('image/logo.png') }}" alt="logo" style="height: 80px;">
        </div>
        <div style="flex: 1; text-align: center;">
            <h1>LAPORAN STOK BAHAN BAKU TOKO RISKA MEBEL</h1>
            @if(!empty($bahanBakuNama))
                <h2>Bahan Baku: {{ $bahanBakuNama }}</h2>
            @endif
            <p>Periode: {{ $periodLabel }}</p>
        </div>
    </div>


    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Tanggal Laporan:</span>
            <span>{{ now()->locale('id')->translatedFormat('d F Y') }}</span>
        </div>        
        
        @if($jenisLaporan == 'bahan_baku')
            <div class="info-row">
                <span class="info-label">Bahan Baku dengan Stok Masuk Terbanyak:</span>
                <span class="highlight-info">
                    {{ $maxInfo['masuk'] }}
                    @if($maxInfo['masuk'] != '-')
                        {{ ' ' . $maxInfo['ukuran_masuk'] }} ({{ number_format($maxInfo['nilai_masuk'] ?? 0) }} batang)
                    @endif
                </span>
            </div>        
            <div class="info-row">
                <span class="info-label">Bahan Baku dengan Stok Keluar Terbanyak:</span>
                <span class="highlight-info">
                    {{ $maxInfo['keluar'] }}
                    @if($maxInfo['keluar'] != '-')
                    {{ ' ' . $maxInfo['ukuran_keluar'] }} ({{ number_format($maxInfo['nilai_keluar'] ?? 0) }} batang)
                    @endif
                </span>
            </div>
        {{-- @elseif($jenisLaporan == 'bulan')
            <div class="info-row">
                <span class="info-label">Bulan dengan Stok Masuk Terbanyak:</span>
                <span class="highlight-info">
                    {{ $maxInfo['masuk'] }}
                    @if($maxInfo['masuk'] != '-')
                    {{ ' ' . $maxInfo['ukuran_masuk'] }} ({{ number_format($maxInfo['nilai_masuk'] ?? 0) }})
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Bulan dengan Stok Keluar Terbanyak:</span>
                <span class="highlight-info">
                    {{ $maxInfo['keluar'] }}
                    @if($maxInfo['keluar'] != '-')
                    {{ ' ' . $maxInfo['ukuran_keluar'] }} ({{ number_format($maxInfo['nilai_keluar'] ?? 0) }})
                    @endif
                </span>
            </div> --}}
            {{-- <div class="info-row">
                <span class="info-label">Bulan dengan Stok Awal Terbanyak:</span>
                <span class="highlight-info">
                    {{ $maxInfo['awal'] }}
                    @if($maxInfo['awal'] != '-')
                    ({{ number_format($maxInfo['nilai_awal']) }} {{ $maxInfo['satuan'] }})
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Bulan dengan Sisa Stok Terbanyak:</span>
                <span class="highlight-info">
                    {{ $maxInfo['sisa'] }}
                    @if($maxInfo['sisa'] != '-')
                    ({{ number_format($maxInfo['nilai_sisa']) }} {{ $maxInfo['satuan'] }})
                    @endif
                </span>
            </div> --}}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">No.</th>
                @if($jenisLaporan != 'bahan_baku')
                <th width="20%">Periode</th>
                @endif
                <th width="{{ $jenisLaporan == 'bahan_baku' ? '12%' : '15%' }}" class="text-left">Bahan Baku</th>
                <th width="10%" class="text-center">Ukuran</th>
                <th width="13%">Stok Awal</th>
                <th width="13%">Stok Masuk</th>
                <th width="13%">Stok Keluar</th>
                <th width="13%">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporans as $index => $laporan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @if($jenisLaporan != 'bahan_baku')
                    <td class="text-center">
                        @if(isset($laporan['bulan']) && isset($namaBulan[$laporan['bulan']]) && isset($laporan['tahun']))
                            {{ $namaBulan[$laporan['bulan']] }} {{ $laporan['tahun'] }}
                        @else
                            -
                        @endif
                    </td>
                    @endif
                    <td class="text-left">{{ $laporan['nama_bahan_baku'] ?? '-' }}</td>
                    <td class="text-center">{{ $laporan['ukuran'] ?? '-' }}</td>
                    <td class="text-center">{{ number_format($laporan['stok_awal'] ?? 0) }}</td>
                    <td class="text-center">{{ number_format($laporan['stok_masuk'] ?? 0) }}</td>
                    <td class="text-center">{{ number_format($laporan['stok_keluar'] ?? 0) }}</td>
                    <td class="text-center">{{ number_format($laporan['sisa_stok'] ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer" style="position: relative; height: 150px;">
        <!-- waktu cetak -->
        <p style="margin: 0; font-size: 8pt; color: #7f8c8d;">
            Dokumen ini dicetak pada {{ now()->locale('id')->translatedFormat('d F Y H:i') }}
        </p>
    
        <!-- tanda tangan di pojok kanan bawah, tapi lebih ke bawah dari teks -->
        <div style="position: absolute; top: 35px; right: 0; text-align: center;">
            <div class="signature">
                <div class="signature-place">
                    Indramayu, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
                </div>
                <div class="signature-position">Pemilik Riska Mebel</div>
                <div style="height: 10px;"></div>
                <img src="{{ public_path('image/signature.png') }}" alt="Tanda Tangan"
                    style="height: 80px; margin-bottom: 5px;">
                <div class="signature-name">
                    {{ auth()->user()->name ?? '(_______________________)' }}
                </div>
            </div>
        </div>
    </div>
    
    
       
</body>
</html>