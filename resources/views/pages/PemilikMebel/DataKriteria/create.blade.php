@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Kriteria</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Kriteria -->
      <h3 class="mb-4 font-weight-bold">Form Data Kriteria</h3>
      
      <form method="POST" action="{{ route('store.datakriteria.pemilikmebel') }}">
        @csrf
        
        <div class="form-group">
          <label for="nama_kriteria">Nama Kriteria</label>
          <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" 
                 id="nama_kriteria" name="nama_kriteria" 
                 placeholder="Nama Kriteria" value="{{ old('nama_kriteria') }}" required>
          @error('nama_kriteria')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="kategori">Kategori</label>
          <select class="form-control @error('kategori') is-invalid @enderror" 
                  id="kategori" name="kategori" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="cost" {{ old('kategori') == 'cost' ? 'selected' : '' }}>Cost (Lebih kecil, lebih baik)</option>
              <option value="benefit" {{ old('kategori') == 'benefit' ? 'selected' : '' }}>Benefit (Lebih besar, lebih baik)</option>
          </select>
          @error('kategori')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
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
        
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('datakriteria.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
@endsection