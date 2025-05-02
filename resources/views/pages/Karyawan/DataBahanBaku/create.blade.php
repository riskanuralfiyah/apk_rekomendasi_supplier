@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('databahanbaku.karyawan') }}">Data Bahan Baku</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Bahan Baku</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Bahan Baku -->
      <h3 class="mb-4 font-weight-bold">Form Data Bahan Baku</h3> <!-- Tambah mb-4 untuk jarak bawah -->
      <form class="forms-sample">
        <div class="form-group">
          <label for="namaBahanBaku">Nama Bahan Baku</label>
          <input type="text" class="form-control" id="namaBahanBaku" placeholder="Nama Bahan Baku">
        </div>
        <div class="form-group">
          <label for="satuan">Satuan</label>
          <input type="text" class="form-control" id="satuan" placeholder="Satuan">
        </div>
        <div class="form-group">
          <label for="stokMinimum">Stok Minimum</label>
          <input type="text" class="form-control" id="stokMinimum" placeholder="Stok Minimum">
        </div>
        <div class="form-group">
          <label for="jumlahStok">Jumlah Stok</label>
          <input type="text" class="form-control" id="jumlahStok" placeholder="Jumlah Stok">
        </div>
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('databahanbaku.karyawan') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
@endsection
