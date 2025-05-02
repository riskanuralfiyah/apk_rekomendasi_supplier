@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Perhitungan</a></li>
@endsection

@section('content')
    <!-- Card untuk Data Perhitungan -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Halaman -->
            <h3 class="mb-4 font-weight-bold">Data Perhitungan Metode SMART</h3>

            <!-- Bagian 1: Penilaian Kriteria -->
            <div class="mb-5">
                <h4 class="mb-3 font-weight-bold">Penilaian Kriteria</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Supplier</th>
                                <th>Kualitas</th>
                                <th>Harga</th>
                                <th>Pelayanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Supplier A</td>
                                <td>100</td>
                                <td>60</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Supplier B</td>
                                <td>80</td>
                                <td>80</td>
                                <td>80</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Supplier C</td>
                                <td>60</td>
                                <td>100</td>
                                <td>80</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bagian 2: Perhitungan Utility -->
            <div class="mb-5">
                <h4 class="mb-3 font-weight-bold">Hasil Perhitungan Utility</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Supplier</th>
                                <th>Kualitas</th>
                                <th>Harga</th>
                                <th>Pelayanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Supplier A</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Supplier B</td>
                                <td>0.5</td>
                                <td>0.5</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Supplier C</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bagian 3: Perhitungan Skor Akhir -->
            <div>
                <h4 class="mb-3 font-weight-bold">Hasil Perhitungan Skor Akhir</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Supplier</th>
                                <th>Skor Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Supplier A</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Supplier B</td>
                                <td>0.4</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Supplier C</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection