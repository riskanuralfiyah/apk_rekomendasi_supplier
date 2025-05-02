@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Supplier</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Supplier -->
      <h3 class="mb-4 font-weight-bold">Edit Data Supplier</h3>
      <form method="POST" action="{{ route('update.datasupplier.pemilikmebel', $supplier->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
          <label for="nama_supplier" class="form-label">Nama Supplier</label>
          <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                 id="nama_supplier" name="nama_supplier" 
                 value="{{ old('nama_supplier', $supplier->nama_supplier) }}" 
                 placeholder="Nama Supplier" required>
          @error('nama_supplier')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="alamat" class="form-label">Alamat</label>
          <textarea class="form-control @error('alamat') is-invalid @enderror" 
                    id="alamat" name="alamat" rows="3"
                    placeholder="Alamat" required>{{ old('alamat', $supplier->alamat) }}</textarea>
          @error('alamat')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="no_telpon" class="form-label">No. Telepon</label>
          <input type="text" class="form-control @error('no_telpon') is-invalid @enderror" 
                 id="no_telpon" name="no_telpon" 
                 value="{{ old('no_telpon', $supplier->no_telpon) }}" 
                 placeholder="No. Telepon" required>
          @error('no_telpon')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('datasupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection