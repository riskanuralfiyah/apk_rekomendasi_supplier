@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item"><a href="{{ route('datasubkriteria.pemilikmebel', $kriteria->id) }}">Data Sub Kriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Subkriteria</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Informasi Kriteria -->
        {{-- <div class="row mb-4">
            <div class="col-md-4">
                <label for="namaKriteria">Nama Kriteria</label>
                <input type="text" class="form-control" id="namaKriteria" value="{{ $kriteria->nama_kriteria }}" readonly>
            </div>
            <div class="col-md-4">
                <label for="kategori">Kategori</label>
                <input type="text" class="form-control" id="kategori" value="{{ ucfirst($kriteria->kategori) }}" readonly>
            </div>
            <div class="col-md-4">
                <label for="bobot">Bobot</label>
                <input type="text" class="form-control" id="bobot" value="{{ $kriteria->bobot * 100 }}%" readonly>
            </div>
        </div> --}}

        <!-- Judul Halaman Form Data Sub Kriteria -->
        <h3 class="mb-4 font-weight-bold">Form Data Subkriteria</h3>
        
        <form method="POST" action="{{ route('store.datasubkriteria.pemilikmebel', $kriteria->id) }}">
            @csrf
            
            <div class="form-group">
                <label for="nama_subkriteria">Nama Subkriteria</label>
                <input type="text" class="form-control @error('nama_subkriteria') is-invalid @enderror" 
                       id="nama_subkriteria" name="nama_subkriteria" 
                       placeholder="Nama Subkriteria" value="{{ old('nama_subkriteria') }}" required>
                @error('nama_subkriteria')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" class="form-control @error('nilai') is-invalid @enderror" 
                       id="nilai" name="nilai" 
                       value="{{ old('nilai') }}" 
                       placeholder="Nilai Subkriteria" 
                       min="1" step="1" required>
                <small class="form-text text-muted">
                  Benefit = Nilai besar lebih baik, Cost = Nilai kecil lebih baik.
                </small>
                @error('nilai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('datasubkriteria.pemilikmebel', $kriteria->id) }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

<script>
    // Validasi client-side jika diperlukan
    document.querySelector('form').addEventListener('submit', function(e) {
        const nilaiInput = document.getElementById('nilai');
        if (nilaiInput.value <= 0) {
            e.preventDefault();
            alert('Nilai harus lebih besar dari 0');
            nilaiInput.focus();
        }
    });
</script>
@endsection