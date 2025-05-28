@extends('layouts.pemilikmebel')

@section('content')
<div class="col-md-12 grid-margin transparent">
    <!-- Chart Welcome Admin -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card card-dark-blue position-relative" style="padding: 15px; overflow: visible;"> <!-- Gunakan class card-dark-blue -->
                <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h2 class="mb-2 text-white" style="font-size: 32px;">Selamat datang!</h2> <!-- Ukuran font -->
                        <p class="mb-0 text-white" style="margin-top: 12px; font-size: 14px;">Lihat stok bahan baku dan rekomendasi supplier dengan mudah.</p> <!-- Ukuran font dan jarak -->
                    </div>
                    <!-- Gambar diperbesar dan diposisikan di luar card -->
                    <img src="{{ asset('image/welcome.png') }}" alt="Welcome Image" class="img-fluid" style="max-height: 180px; position: absolute; right: 60px; top: 51%; transform: translateY(-50%);">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Card 1: Total Data Supplier -->
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale"> <!-- Biarkan warna card seperti semula -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4">Total Data Supplier</p>
                        <i class="mdi mdi-account-multiple-outline" style="font-size: 35px;"></i> <!-- Ikon diperbesar -->
                    </div>
                    <p class="fs-30 mb-2">{{ $totalSupplier }}</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Data Kriteria -->
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-dark-blue"> <!-- Biarkan warna card seperti semula -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4">Total Data Kriteria</p>
                        <i class="mdi mdi-format-list-checks" style="font-size: 35px;"></i> <!-- Ikon diperbesar -->
                    </div>
                    <p class="fs-30 mb-2">{{ $totalKriteria }}</p>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Sub Kriteria -->
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue"> <!-- Biarkan warna card seperti semula -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4">Total Sub Kriteria</p>
                        <i class="mdi mdi-format-list-checks" style="font-size: 35px;"></i> <!-- Ikon diperbesar -->
                    </div>
                    <p class="fs-30 mb-2">{{ $totalSubkriteria }}</p>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Bahan Baku -->
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card" style="background-color: #F3797E;"> <!-- Warna card diubah ke #F3797E -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4" style="color: #FFFFFF;">Total Bahan Baku</p> <!-- Warna font putih -->
                        <i class="mdi mdi-package-variant-closed" style="font-size: 35px; color: #FFFFFF;"></i> <!-- Ikon diperbesar dan warna font putih -->
                    </div>
                    <p class="fs-30 mb-2" style="color: #FFFFFF;">{{ $totalBahanBaku }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection