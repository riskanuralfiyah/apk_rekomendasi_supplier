{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.pemilikmebel')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard.pemilikmebel') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Ubah Profile</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="mb-4 font-weight-bold">Ubah Profile</h5>

    {{-- Profile Header --}}
    <div class="bg-light px-3 py-2 mb-4"><strong>Profile</strong></div>

    <div class="row align-items-center mb-5">
      {{-- Foto & Change Picture --}}
      <div class="col-md-3 text-center">
        <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('image/profile1.png') }}"
             class="rounded-circle mb-2"
             style="width:120px; height:120px; object-fit:cover;"
             alt="Foto Profil">

        <button type="button"
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#changePictureModal">
          Pilih Foto
        </button>
      </div>

      {{-- Data Profil --}}
      <div class="col-md-9">
        <form action="{{ route('profile.update') }}" method="POST">
          @csrf
          @method('PATCH')

          {{-- Email --}}
          <div class="row mb-3 g-3">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="email"
                     class="form-control-plaintext"
                     readonly
                     value="{{ $user->email }}">
            </div>
          </div>

          {{-- Nama Pengguna --}}
          <div class="row mb-3 g-3">
            <label class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
              <input type="text"
                     class="form-control-plaintext"
                     readonly
                     value="{{ $user->nama_pengguna }}">
            </div>
          </div>

          {{-- Role (sekarang di bawah Nama) --}}
          <div class="row mb-3 g-3">
            <label class="col-sm-2 col-form-label">Role</label>
            <div class="col-sm-10">
              <input type="text"
                    class="form-control-plaintext"
                    readonly
                    value="{{ ucfirst(str_replace(['pemilikmebel', 'karyawan'], ['Pemilik Mebel', 'Karyawan'], $user->role)) }}">
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="bg-light px-3 py-2 mb-3"><strong>Ubah Password</strong></div>
    <form action="{{ route('profile.password') }}" method="POST">
      @csrf
      @method('PATCH')

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label for="current_password" class="form-label">Password Saat Ini</label>
          <input type="password" id="current_password" name="current_password"
                 class="form-control @error('current_password') is-invalid @enderror">
          @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-4">
          <label for="new_password" class="form-label">Password Baru</label>
          <input type="password" id="new_password" name="new_password"
                 class="form-control @error('new_password') is-invalid @enderror">
          @error('new_password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-4">
          <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
          <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Ubah Password</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Ganti Foto --}}
<div class="modal fade" id="changePictureModal" tabindex="-1" aria-labelledby="changePictureModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <div class="modal-header">
        <h5 class="modal-title" id="changePictureModalLabel">Pilih Foto Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="file"
               name="foto"
               class="form-control @error('foto') is-invalid @enderror"
               accept="image/*">
        @error('foto')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
    </form>
  </div>
</div>
@endsection
