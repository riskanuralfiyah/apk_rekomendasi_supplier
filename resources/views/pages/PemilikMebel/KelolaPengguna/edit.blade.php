@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kelolapengguna.pemilikmebel') }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Pengguna</li>
@endsection

@section('content')
<div class="container mt-1"> <!-- Ubah mt-5 menjadi mt-3 untuk mengurangi margin atas -->
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Pengguna -->
      <h3 class="mb-4 font-weight-bold">Edit Data Pengguna</h3>
      <form class="forms-sample" method="POST" action="{{ route('edit.kelolapengguna.pemilikmebel', $pengguna->id) }}">
        @csrf
        @method('PUT') <!-- Method untuk update data -->
        
        <div class="form-group mb-3">
          <label for="nama" class="form-label">Nama Pengguna</label>
          <input type="text" class="form-control" id="nama" name="nama" value="{{ $pengguna->nama }}" placeholder="Nama Pengguna">
        </div>
        <div class="form-group mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="text" class="form-control" id="email" name="email" value="{{ $pengguna->email }}" placeholder="Email">
        </div>
        <div class="form-group mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
                <option value="Pemilik Mebel" {{ $pengguna->role == 'Pemilik Mebel' ? 'selected' : '' }}>Pemilik Mebel</option>
                <option value="Karyawan" {{ $pengguna->role == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('kelolapengguna.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection