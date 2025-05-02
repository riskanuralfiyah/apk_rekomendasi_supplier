@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel', $penilaian->supplier->id) }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Penilaian Supplier</li>
@endsection

@section('content')
<div class="container mt-1">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4 font-weight-bold">Edit Penilaian Supplier</h3>
            
            <form method="POST" action="{{ route('update.penilaiansupplier.pemilikmebel', ['supplierId' => $supplier->id, 'id' => $penilaian->id]) }}">
                @csrf
                @method('PUT')
                @foreach($kriterias as $kriteria)
                <div class="form-group mb-3">
                    <label for="kriteria_{{ $kriteria->id }}" class="form-label">{{ $kriteria->nama_kriteria }}</label>
                    <select class="form-control @error('kriteria.'.$kriteria->id) is-invalid @enderror" 
                            id="kriteria_{{ $kriteria->id }}" 
                            name="kriteria[{{ $kriteria->id }}]" required>
                        @foreach($kriteria->subkriterias as $subkriteria)
                        <option value="{{ $subkriteria->id }}"
                            {{ old('kriteria.' . $kriteria->id, $penilaian->id_subkriteria) == $subkriteria->id ? 'selected' : '' }}>
                            {{ $subkriteria->nama_subkriteria }} (Nilai: {{ $subkriteria->nilai }})
                        </option>
                        @endforeach
                    </select>
                    @error('kriteria.'.$kriteria->id)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('penilaiansupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection