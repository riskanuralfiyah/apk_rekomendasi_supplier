@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokmasuk.karyawan') }}">Stok Masuk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Stok Masuk</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Stok Masuk -->
      <h3 class="mb-4 font-weight-bold">Form Data Stok Masuk</h3> <!-- Tambah mb-4 untuk jarak bawah -->
      <form class="forms-sample">
        <div class="form-group mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <div class="input-group date" id="datepicker">
            <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Pilih tanggal">
          </div>
        </div>
        <div class="form-group">
          <label for="namaBahanBaku">Nama Bahan Baku</label>
          <input type="text" class="form-control" id="namaBahanBaku" placeholder="Nama Bahan Baku">
        </div>
        <div class="form-group">
          <label for="jumlahStokMasuk">Jumlah Stok Masuk</label>
          <input type="text" class="form-control" id="jumlahStokMasuk" placeholder="Jumlah Stok Masuk">
        </div>
        <div class="form-group">
          <label for="namaSupplier">Nama Supplier</label>
          <input type="text" class="form-control" id="namaSupplier" placeholder="Nama Supplier">
        </div>
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('stokmasuk.karyawan') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
@endsection
