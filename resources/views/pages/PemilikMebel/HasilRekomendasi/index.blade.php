@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Hasil Rekomendasi</li>
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

            @php
                $rekomendasi = $hasilRekomendasi->where('peringkat', '<=', 3);
                $alternatif = $hasilRekomendasi->where('peringkat', '>', 3);
            @endphp

            <!-- Tabel Supplier Rekomendasi -->
            <h5 class="font-weight-bold mb-3">Supplier Rekomendasi (Peringkat 1–3)</h5>
            <div class="table-responsive mb-4">
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
                        @forelse($rekomendasi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->supplier->nama_supplier }}</td>
                                <td>{{ rtrim(rtrim(number_format($item->skor_akhir, 2), '0'), '.') }}</td>
                                <td>{{ $item->peringkat }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center fst-italic">Belum ada data rekomendasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Toggle Button untuk Alternatif -->
            @if($alternatif->isNotEmpty())
            <div class="mb-2">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#alternatifCollapse" aria-expanded="false" aria-controls="alternatifCollapse">
                    Lihat Supplier Alternatif
                </button>
            </div>

            <!-- Tabel Supplier Alternatif -->
            <div class="collapse" id="alternatifCollapse">
                <div class="card card-body">
                    <h5 class="font-weight-bold mb-3">Supplier Alternatif (Peringkat > 3)</h5>
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
                                @foreach($alternatif as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->supplier->nama_supplier }}</td>
                                        <td>{{ rtrim(rtrim(number_format($item->skor_akhir, 2), '0'), '.') }}</td>
                                        <td>{{ $item->peringkat }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
