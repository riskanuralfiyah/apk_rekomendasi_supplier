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
                <a href="#" class="btn btn-danger">
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
                                <th>Ranking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Supplier A</td>
                                <td>1</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Supplier B</td>
                                <td>0.4</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Supplier C</td>
                                <td>0</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection