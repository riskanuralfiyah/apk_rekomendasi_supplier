@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokkeluar.karyawan') }}">Stok Keluar</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Stok Keluar</li>
@endsection

@section('content')
<div class="container mt-1"> <!-- Ubah mt-5 menjadi mt-3 untuk mengurangi margin atas -->
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Bahan Baku -->
      <h3 class="mb-4 font-weight-bold">Edit Data Stok Keluar</h3>
      <form class="forms-sample" method="POST" action="{{ route('edit.stokkeluar.karyawan', $stokkeluar->id) }}">
        @csrf
        @method('PUT') <!-- Method untuk update data -->
        
        <div class="form-group mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <div class="input-group date" id="datepicker">
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $stokkeluar->tanggal }}" placeholder="Pilih tanggal">
          </div>
        </div>
        <div class="form-group mb-3">
            <label for="namaBahanBaku" class="form-label">Nama Bahan Baku</label>
            <input type="text" class="form-control" id="namaBahanBaku" name="namaBahanBaku" value="{{ $stokkeluar->namaBahanBaku }}" placeholder="Nama Bahan Baku">
        </div>
        <div class="form-group mb-3">
          <label for="jumlahStokKeluar" class="form-label">Jumlah Stok Keluar</label>
          <input type="text" class="form-control" id="jumlahStokKeluar" name="jumlahStokKeluar" value="{{ $stokkeluar->jumlahStokKeluar }}" placeholder="Jumlah Stok Keluar">
        </div>
        <div class="form-group mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ $stokkeluar->keterangan }}" placeholder="Keterangan">
          </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('stokkeluar.karyawan') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection