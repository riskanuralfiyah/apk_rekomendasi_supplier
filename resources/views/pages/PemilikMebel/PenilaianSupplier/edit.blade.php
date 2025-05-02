@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel') }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Penilaian Supplier</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-4 font-weight-bold">Form Edit Penilaian Supplier</h3>
        <form class="forms-sample" method="POST" action="{{ route('edit.penilaiansupplier.pemilikmebel', $penilaian->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="kualitas">Kualitas</label>
                <select class="form-control" id="kualitas" name="kualitas" required>
                    <option value="baik" {{ $penilaian->kualitas == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="cukup" {{ $penilaian->kualitas == 'cukup' ? 'selected' : '' }}>Cukup</option>
                    <option value="kurang" {{ $penilaian->kualitas == 'kurang' ? 'selected' : '' }}>Kurang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harga">Harga</label>
                <select class="form-control" id="harga" name="harga" required>
                    <option value="murah" {{ $penilaian->harga == 'murah' ? 'selected' : '' }}>Murah</option>
                    <option value="standar" {{ $penilaian->harga == 'standar' ? 'selected' : '' }}>Standar</option>
                    <option value="mahal" {{ $penilaian->harga == 'mahal' ? 'selected' : '' }}>Mahal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pelayanan">Pelayanan</label>
                <select class="form-control" id="pelayanan" name="pelayanan" required>
                    <option value="baik" {{ $penilaian->pelayanan == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="cukup" {{ $penilaian->pelayanan == 'cukup' ? 'selected' : '' }}>Cukup</option>
                    <option value="buruk" {{ $penilaian->pelayanan == 'buruk' ? 'selected' : '' }}>Buruk</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mr-2">Update</button>
            <a href="{{ route('penilaiansupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
