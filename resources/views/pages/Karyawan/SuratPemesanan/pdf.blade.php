<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Pemesanan Bahan Baku</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 30px 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
        }
        
        .header h1 {
            color: #2c3e50;
            font-size: 20px;
            margin: 0 0 8px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .header .company {
            font-weight: bold;
            font-size: 14px;
            margin-top: 8px;
        }
        
        .header .address {
            font-size: 11px;
            color: #555;
            margin-top: 3px;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 0 4px 4px 0;
        }
        
        .info-box p {
            margin: 7px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 12px;
        }
        
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            padding: 10px 12px;
            text-align: left;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 5px;
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
            margin-top: 40px;
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
    <div class="header">
        <h1>SURAT PEMESANAN BAHAN BAKU</h1>
        <div class="company">RISKA MEBEL</div>
        <div class="address">Rambatan Wetan, Kec. Sindang, Kab. Indramayu, Jawa Barat (+62 81946883325)</div>
    </div>
    
    <div class="content">
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
                        <td>{{ $bahan->jumlah ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="spacer"></div>
        
        <div class="closing">
            <p>Kami berharap bahan baku tersebut dapat dipenuhi sesuai dengan pesanan di atas untuk diambil langsung di toko.</p>
            <p>Demikian surat pemesanan ini kami buat dengan sebenar-benarnya. Atas perhatian dan kerjasama yang baik, kami mengucapkan terima kasih.</p>
        </div>
    </div>
    
    <div class="footer">
        <div class="signature-container">
            <div class="signature">
                <div class="signature-place">Indramayu, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
                <div class="signature-position">Hormat kami,</div>
                <div class="spacer"></div>
                <div class="spacer"></div>
                <div class="signature-name">{{ auth()->user()->name ?? '(_______________________)' }}</div>
                <div class="signature-position">{{ auth()->user()->position ?? 'Pemilik Riska Mebel' }}</div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>