@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Supplier</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Supplier -->
      <h3 class="mb-4 font-weight-bold">Form Data Supplier</h3>
      
      <form method="POST" action="{{ route('store.datasupplier.pemilikmebel') }}">
        @csrf
        
        <div class="form-group">
          <label for="nama_supplier">Nama Supplier</label>
          <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                 id="nama_supplier" name="nama_supplier" 
                 placeholder="Nama Supplier" value="{{ old('nama_supplier') }}" required>
          @error('nama_supplier')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea class="form-control @error('alamat') is-invalid @enderror" 
                    id="alamat" name="alamat" 
                    rows="3" placeholder="Alamat" required>{{ old('alamat') }}</textarea>
          @error('alamat')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="no_telpon">No. Telepon</label>
          <input type="text" class="form-control @error('no_telpon') is-invalid @enderror" 
                 id="no_telpon" name="no_telpon" 
                 placeholder="No. Telepon" value="{{ old('no_telpon') }}" required>
          @error('no_telpon')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('datasupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
@endsection