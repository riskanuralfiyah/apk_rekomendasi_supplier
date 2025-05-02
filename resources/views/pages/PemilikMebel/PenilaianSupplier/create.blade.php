@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Penilaian Supplier</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-4 font-weight-bold">Form Penilaian Supplier</h3>
        
        <form method="POST" action="{{ route('store.penilaiansupplier.pemilikmebel', $supplier->id) }}">
            @csrf

            @foreach($kriterias as $kriteria)
            <div class="form-group">
                <label for="kriteria_{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</label>
                <select class="form-control @error('kriteria.'.$kriteria->id) is-invalid @enderror" 
                        id="kriteria_{{ $kriteria->id }}" 
                        name="kriteria[{{ $kriteria->id }}]" required>
                    <option value="">-- Pilih {{ $kriteria->nama_kriteria }} --</option>
                    @foreach($kriteria->subkriterias as $subkriteria)
                        <option value="{{ $subkriteria->id }}" {{ old('kriteria.'.$kriteria->id) == $subkriteria->id ? 'selected' : '' }}>
                            {{ $subkriteria->nama_subkriteria }} (Nilai: {{ $subkriteria->nilai }})
                        </option>
                    @endforeach
                </select>
                @error('kriteria.'.$kriteria->id)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endforeach

            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection