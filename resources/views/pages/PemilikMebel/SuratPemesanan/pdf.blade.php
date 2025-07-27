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
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #2c3e50;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #2c3e50;
}

.header-left {
    flex: 0 0 auto;
}

.header-left img {
    height: 70px;
    width: auto;
}

.header-center {
    flex: 1;
    text-align: center;
    line-height: 1.2;
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-top: -10px; /* geser ke atas */
}

.header-center h1 {
    color: #2c3e50;
    font-size: 22px;
    margin: 0;
    padding: 0;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.header-center .address {
    font-size: 12px;
    color: #555;
    margin-top: 4px;
    white-space: nowrap;
}


.header-right {
    flex: 0 0 70px; /* agar seimbang dengan logo kiri */
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
            margin-top: 10px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .signature-position {
            font-size: 11px;
            margin-top: 2px;
        }
        
        .spacer {
            height: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('image/logo.png') }}" alt="logo">
        </div>
        <div class="header-center">
            <h1>RISKA MEBEL</h1>
            <div class="address">
                Rambatan Wetan, Kec. Sindang, Kab. Indramayu, Jawa Barat – Telp: +62 819 4688 3325
            </div>
        </div>
        <div class="header-right"></div> <!-- kosong untuk seimbangkan agar center -->
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
                    <th width="10%">No</th>
                    <th width="35%">Nama Bahan Baku</th>
                    <th width="25%">Ukuran</th>
                    <th width="30%">Jumlah Dipesan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bahanList as $index => $bahan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bahan->nama_bahan_baku }}</td>
                        <td>{{ $bahan->ukuran ?? '-' }}</td>
                        <td>{{ $bahan->jumlah ?? '-' }} {{ $bahan->satuan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        
        <div class="spacer"></div>
        
        <div class="closing">
            <p>Pesanan bahan baku ini kami sampaikan dengan harapan dapat dipenuhi sesuai dengan rincian di atas dan segera disiapkan. Apabila terdapat kendala atau bahan yang tidak tersedia, mohon segera menghubungi kami melalui nomor yang tertera.</p>
            <p>Adapun pembayaran akan dilakukan secara langsung pada saat pengambilan barang oleh pihak kami.</p>
            <p>Demikian surat ini kami buat. Atas perhatian dan kerjasama yang baik, kami mengucapkan terima kasih.</p>
        </div>
    </div>
    
    <div class="footer">
        <div class="signature-container">
            <div class="signature">
                <div class="signature-place">Indramayu, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                <div class="signature-position">Hormat kami,</div>
                <div class="spacer"></div>

                 {{-- gambar tanda tangan --}}
                <img src="{{ public_path('image/signature.png') }}" alt="Tanda Tangan" style="height: 80px; margin-bottom: 5px;">

                {{-- <div class="signature-name">{{ auth()->user()->name ?? '(_______________________)' }}</div> --}}
                <div class="signature-position">{{ auth()->user()->position ?? 'Pemilik Riska Mebel' }}</div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>