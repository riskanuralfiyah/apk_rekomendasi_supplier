@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel') }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Penilaian Supplier</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-4 font-weight-bold">Form Penilaian Supplier</h3>
        <form class="forms-sample" onsubmit="event.preventDefault(); handleFormSubmit();">
            <div class="form-group">
                <label for="kualitas">Kualitas</label>
                <select class="form-control" id="kualitas" name="kualitas" required>
                    <option value="">-- Pilih Kualitas --</option>
                    <option value="baik">Baik</option>
                    <option value="cukup">Cukup</option>
                    <option value="kurang">Kurang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harga">Harga</label>
                <select class="form-control" id="harga" name="harga" required>
                    <option value="">-- Pilih Harga --</option>
                    <option value="murah">Murah</option>
                    <option value="standar">Standar</option>
                    <option value="mahal">Mahal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pelayanan">Pelayanan</label>
                <select class="form-control" id="pelayanan" name="pelayanan" required>
                    <option value="">-- Pilih Pelayanan --</option>
                    <option value="baik">Baik</option>
                    <option value="cukup">Cukup</option>
                    <option value="buruk">Buruk</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('penilaiansupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

<script>
    function handleFormSubmit() {
        // Show success message
        alert('Penilaian berhasil disimpan!');
        
        // Redirect back to penilaian supplier page after 1 second
        setTimeout(function() {
            window.location.href = "{{ route('penilaiansupplier.pemilikmebel') }}";
        }, 1000);
    }
</script>
@endsection