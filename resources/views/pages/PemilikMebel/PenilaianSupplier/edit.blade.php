@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Penilaian Supplier</li>
@endsection

@section('content')
<div class="container mt-1">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4 font-weight-bold">Edit Penilaian Supplier</h3>
            
            <form method="POST" action="{{ route('update.penilaiansupplier.pemilikmebel', $supplier->id) }}">
                @csrf
                @method('PUT')

                @foreach($penilaians as $penilaian)
                <div class="form-group mb-3">
                    <label class="form-label">{{ $penilaian->kriteria->nama_kriteria }}</label>
                    <select class="form-control" 
                            name="penilaian[{{ $penilaian->id }}][id_subkriteria]" required>
                        @foreach($penilaian->kriteria->subkriterias as $subkriteria)
                        <option value="{{ $subkriteria->id }}"
                            {{ $penilaian->id_subkriteria == $subkriteria->id ? 'selected' : '' }}>
                            {{ $subkriteria->nama_subkriteria }} (Nilai: {{ $subkriteria->nilai }})
                        </option>
                        @endforeach
                    </select>
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection