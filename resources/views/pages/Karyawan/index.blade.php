@extends('layouts.karyawan')

@section('content')
<div class="col-md-12 grid-margin transparent">
    <!-- Chart Welcome Admin -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card card-dark-blue position-relative" style="padding: 15px; overflow: visible;"> <!-- Gunakan class card-dark-blue -->
                <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h2 class="mb-2 text-white" style="font-size: 32px;">Selamat datang!</h2> <!-- Ukuran font -->
                        <p class="mb-0 text-white" style="margin-top: 12px; font-size: 14px;">Lihat stok bahan baku dan catat keluar masuknya stok dengan mudah.</p> <!-- Ukuran font dan jarak -->
                    </div>
                    <!-- Gambar diperbesar dan diposisikan di luar card -->
                    <img src="{{ asset('image/welcome.png') }}" alt="Welcome Image" class="img-fluid" style="max-height: 180px; position: absolute; right: 60px; top: 51%; transform: translateY(-50%);">
                </div>
            </div>
        </div>
    </div>

        <!-- Card 1: Stok Keluar Hari Ini -->
    <div class="row">
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4">Stok Masuk Hari Ini</p>
                        <i class="mdi mdi-arrow-down-bold" style="font-size: 35px;"></i>
                    </div>
                    <p class="fs-30 mb-2">{{ $stokMasukHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Stok Keluar Hari Ini -->
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-dark-blue" style="background-color: #1a237e; color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4 text-white">Stok Keluar Hari Ini</p>
                        <i class="mdi mdi-arrow-up-bold text-white" style="font-size: 35px;"></i>
                    </div>
                    <p class="fs-30 mb-2 text-white">{{ $stokKeluarHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Bahan Baku -->
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale"> <!-- Biarkan warna card seperti semula -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-4">Total Bahan Baku</p>
                        <i class="mdi mdi-package-variant-closed" style="font-size: 35px;"></i> <!-- Ikon diperbesar -->
                    </div>
                    <p class="fs-30 mb-2">{{ $totalBahanBaku }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection