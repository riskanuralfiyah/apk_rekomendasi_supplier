@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item"><a href="{{ route('datasubkriteria.pemilikmebel', $subkriteria->kriteria->id) }}">Data Subkriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Sub Kriteria</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Sub Kriteria -->
      <h3 class="mb-4 font-weight-bold">Edit Data Subkriteria</h3>

      <form method="POST" action="{{ route('update.datasubkriteria.pemilikmebel', ['kriteriaId' => $kriteria->id, 'id' => $subkriteria->id]) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="nama_subkriteria" class="form-label">Nama Subkriteria</label>
          <input type="text" class="form-control @error('nama_subkriteria') is-invalid @enderror"
                 id="nama_subkriteria" name="nama_subkriteria"
                 value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}"
                 placeholder="Nama Sub Kriteria" required>
          @error('nama')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="nilai" class="form-label">Nilai</label>
          <input type="number" class="form-control @error('nilai') is-invalid @enderror"
                 id="nilai" name="nilai"
                 value="{{ old('nilai', $subkriteria->nilai) }}"
                 placeholder="Nilai Sub Kriteria"
                 min="1" step="1" required>
          <small class="form-text text-muted">
            Benefit = Nilai besar lebih baik, Cost = Nilai kecil lebih baik.
          </small>
          @error('nilai')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('datasubkriteria.pemilikmebel', $subkriteria->kriteria->id) }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
