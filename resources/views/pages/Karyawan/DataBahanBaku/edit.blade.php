@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('databahanbaku.karyawan') }}">Data Bahan Baku</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Bahan Baku</li>
@endsection

@section('content')
<div class="container mt-1"> <!-- Ubah mt-5 menjadi mt-3 untuk mengurangi margin atas -->
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Bahan Baku -->
      <h3 class="mb-4 font-weight-bold">Edit Data Bahan Baku</h3>
      <form class="forms-sample" method="POST" action="{{ route('edit.databahanbaku.karyawan', $bahanbaku->id) }}">
        @csrf
        @method('PUT') <!-- Method untuk update data -->
        
        <div class="form-group mb-3">
          <label for="nama" class="form-label">Nama Bahan Baku</label>
          <input type="text" class="form-control" id="nama" name="nama" value="{{ $bahanbaku->nama }}" placeholder="Nama Bahan Baku">
        </div>
        <div class="form-group mb-3">
            <label for="satuan" class="form-label">Satuan</label>
            <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $bahanbaku->satuan }}" placeholder="Satuan">
        </div>
        <div class="form-group mb-3">
          <label for="stokMinimum" class="form-label">Stok Minimum</label>
          <input type="text" class="form-control" id="stokMinimum" name="stokMinimum" value="{{ $bahanbaku->stokMinimum }}" placeholder="Stok Minimum">
        </div>
        <div class="form-group mb-3">
            <label for="jumlahStok" class="form-label">Jumlah Stok</label>
            <input type="text" class="form-control" id="jumlahStok" name="jumlahStok" value="{{ $bahanbaku->jumlahStok }}" placeholder="Jumlah Stok">
          </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('databahanbaku.karyawan') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection