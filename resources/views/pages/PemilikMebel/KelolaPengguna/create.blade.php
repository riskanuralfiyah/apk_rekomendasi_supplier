@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kelolapengguna.pemilikmebel') }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Pengguna</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Pengguna -->
      <h3 class="mb-4 font-weight-bold">Form Data Pengguna</h3> <!-- Tambah mb-4 untuk jarak bawah -->
      <form class="forms-sample">
        <div class="form-group">
          <label for="nama">Nama Pengguna</label>
          <input type="text" class="form-control" id="nama" placeholder="Nama Pengguna">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" placeholder="Email">
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role">
                <option value="">-- Pilih Role --</option>
                <option value="1">Pemilik Mebel</option>
                <option value="2">Karyawan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('kelolapengguna.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
@endsection
