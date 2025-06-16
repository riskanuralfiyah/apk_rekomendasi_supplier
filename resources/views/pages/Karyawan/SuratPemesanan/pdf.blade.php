<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Pemesanan Bahan Baku</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px 40px;
        }
        
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }

        .header .logo {
            margin-right: 15px;
            height: 50px;
            display: flex;
            align-items: center;
        }

        .header .logo img {
            height: 100%;
            width: auto;
        }

        .header .header-center {
            flex-grow: 1;
            text-align: center;
        }

        .header .header-center h1 {
            color: #2c3e50;
            font-size: 18px;
            margin: 0;
            padding: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .header .header-center .address {
            font-size: 11px;
            color: #555;
            margin-top: 3px;
        }
        
        .content {
            margin: 20px 0;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }
        
        .info-box p {
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 12px;
        }
        
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: normal;
            padding: 8px 10px;
            text-align: left;
            font-size: 12px;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 30px;
        }
        
        .closing {
            margin-bottom: 5px;
            line-height: 1.6;
            text-align: justify;
        }
        
        .signature-container {
            float: right;
            width: 200px;
            margin-top: 15px;
        }
        
        .signature {
            text-align: center;
        }
        
        .signature-place {
            margin-bottom: 15px;
            font-style: italic;
        }
        
        .signature-name {
            margin-top: 30px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .signature-position {
            font-size: 11px;
            margin-top: 3px;
        }
        
        .spacer {
            height: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('image/logo.png') }}" alt="logo">
        </div>
        <div class="header-center">
            <h1>RISKA MEBEL</h1>
            <div class="address">Rambatan Wetan, Kec. Sindang, Kab. Indramayu, Jawa Barat (+62 81946883325)</div>
        </div>
    </div>    
    
    <div class="content">
        {{-- info tanggal dan nomor surat --}}
        <p><strong>No Surat:</strong> {{ $nomorSurat }}</p>
        <p><strong>Hal: </strong>Pemesanan bahan baku</p>
    
        <div class="info-box">
            <p><strong>Kepada Yth:</strong></p>
            <p>{{ $supplier->nama_supplier }}</p>
            <p>{{ $supplier->alamat }}</p>
            <p>Telp: {{ $supplier->no_telpon ?? '-' }}</p>
        </div>
    
        <p>Dengan hormat,</p>
        
        <div class="spacer"></div>
        
        <p>Sehubungan dengan kebutuhan produksi kami, bersama surat ini kami bermaksud memesan bahan baku sebagai berikut:</p>
        
        <table>
            <thead>
                <tr>
                    <th width="15%">No</th>
                    <th width="40%">Nama Bahan Baku</th>
                    <th width="30%">Jumlah Dipesan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bahanList as $index => $bahan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bahan->nama_bahan_baku }}</td>
                        <td>
                            {{ $bahan->jumlah ?? '-' }} 
                            {{ $bahan->satuan ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="spacer"></div>
        
        <div class="closing">
            <p>Kami berharap bahan baku tersebut dapat dipenuhi sesuai dengan pesanan di atas untuk segera disiapkan.</p>
            <p>Demikian surat pemesanan ini kami buat dengan sebenar-benarnya. Atas perhatian dan kerjasama yang baik, kami mengucapkan terima kasih.</p>
        </div>
    </div>
    
    <div class="footer">
        <div class="signature-container">
            <div class="signature">
                <div class="signature-place">Indramayu, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                <div class="signature-position">Hormat kami,</div>
                <div class="spacer"></div>
                <div class="signature-name">{{ auth()->user()->name ?? '(_______________________)' }}</div>
                <div class="signature-position">{{ auth()->user()->position ?? 'Pemilik Riska Mebel' }}</div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>