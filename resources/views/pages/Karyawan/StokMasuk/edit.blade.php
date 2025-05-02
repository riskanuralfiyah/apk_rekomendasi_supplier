@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokmasuk.karyawan') }}">Stok Masuk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Stok Masuk</li>
@endsection

@section('content')
<div class="container mt-1"> <!-- Ubah mt-5 menjadi mt-3 untuk mengurangi margin atas -->
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Bahan Baku -->
      <h3 class="mb-4 font-weight-bold">Edit Data Stok Masuk</h3>
      <form class="forms-sample" method="POST" action="{{ route('edit.stokmasuk.karyawan', $stokmasuk->id) }}">
        @csrf
        @method('PUT') <!-- Method untuk update data -->
        
        <div class="form-group mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <div class="input-group date" id="datepicker">
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $stokmasuk->tanggal }}" placeholder="Pilih tanggal">
          </div>
        </div>
        <div class="form-group mb-3">
            <label for="namaBahanBaku" class="form-label">Nama Bahan Baku</label>
            <input type="text" class="form-control" id="namaBahanBaku" name="namaBahanBaku" value="{{ $stokmasuk->namaBahanBaku }}" placeholder="Nama Bahan Baku">
        </div>
        <div class="form-group mb-3">
          <label for="jumlahStokMasuk" class="form-label">Jumlah Stok Masuk</label>
          <input type="text" class="form-control" id="jumlahStokMasuk" name="jumlahStokMasuk" value="{{ $stokmasuk->jumlahStokMasuk }}" placeholder="Jumlah Stok Masuk">
        </div>
        <div class="form-group mb-3">
            <label for="namaSupplier" class="form-label">Nama Supplier</label>
            <input type="text" class="form-control" id="namaSupplier" name="namaSupplier" value="{{ $stokmasuk->namaSupplier }}" placeholder="Nama Supplier">
          </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('stokmasuk.karyawan') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection