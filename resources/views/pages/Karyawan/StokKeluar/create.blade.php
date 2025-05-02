@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokmasuk.karyawan') }}">Stok Keluar</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Stok Keluar</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Stok Keluar -->
      <h3 class="mb-4 font-weight-bold">Form Data Stok Keluar</h3> <!-- Tambah mb-4 untuk jarak bawah -->
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
          <label for="jumlahStokKeluar">Jumlah Stok Keluar</label>
          <input type="text" class="form-control" id="jumlahStokKeluar" placeholder="Jumlah Stok Keluar">
        </div>
        <div class="form-group">
          <label for="keterangan">Keterangan</label>
          <input type="text" class="form-control" id="keterangan" placeholder="Keterangan">
        </div>
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('stokkeluar.karyawan') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
@endsection
