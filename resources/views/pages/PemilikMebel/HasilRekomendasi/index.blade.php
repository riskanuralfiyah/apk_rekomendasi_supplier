@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Hasil Rekomendasi</a></li>
@endsection

@section('content')
    <!-- Card untuk Hasil Rekomendasi -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Halaman -->
            <h3 class="mb-4 font-weight-bold">Hasil Rekomendasi Supplier</h3>

            <!-- Tombol Export PDF -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('pdf.hasilrekomendasi.pemilikmebel') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <!-- Tabel Hasil Rekomendasi -->
            <div class="mb-5">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Supplier</th>
                                <th>Skor Akhir</th>
                                <th>Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilRekomendasi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->supplier->nama_supplier }}</td>
                                <td>{{ number_format($item->skor_akhir, 2) }}</td>
                                <td>{{ $item->peringkat }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection