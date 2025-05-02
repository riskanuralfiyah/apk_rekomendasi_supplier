@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Kriteria</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Kriteria -->
      <h3 class="mb-4 font-weight-bold">Edit Data Kriteria</h3>
      
      <form method="POST" action="{{ route('update.datakriteria.pemilikmebel', $kriteria->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
          <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
          <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" 
                 id="nama_kriteria" name="nama_kriteria" 
                 value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" 
                 placeholder="Nama Kriteria" required>
          @error('nama_kriteria')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="kategori" class="form-label">Kategori</label>
          <select class="form-control @error('kategori') is-invalid @enderror" 
                  id="kategori" name="kategori" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="cost" {{ old('kategori', $kriteria->kategori) == 'cost' ? 'selected' : '' }}>Cost (Lebih kecil, lebih baik)</option>
              <option value="benefit" {{ old('kategori', $kriteria->kategori) == 'benefit' ? 'selected' : '' }}>Benefit (Lebih besar, lebih baik)</option>
          </select>
          @error('kategori')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="bobot" class="form-label">Bobot (%)</label>
          <input type="number" class="form-control @error('bobot') is-invalid @enderror" 
                 id="bobot" name="bobot" 
                 value="{{ old('bobot', isset($kriteria) ? $kriteria->bobot * 100 : '') }}" 
                 placeholder="Bobot (0-100)" 
                 min="1" max="100" step="1" required>
          <small class="form-text text-muted">
            Masukkan nilai antara 1-100. Total semua bobot kriteria tidak boleh melebihi 100%.
            <span id="total-bobot-info" class="font-weight-bold"></span>
          </small>
          @error('bobot')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('datakriteria.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection