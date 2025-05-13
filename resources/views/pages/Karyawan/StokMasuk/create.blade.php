@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokmasuk.karyawan') }}">Stok Masuk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Stok Masuk</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Judul Halaman Form Stok Masuk -->
        <h3 class="mb-4 font-weight-bold">Form Tambah Stok Masuk</h3>
        
        <form method="POST" action="{{ route('store.stokmasuk.karyawan') }}" id="add-form">
            @csrf
            
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                       id="tanggal" name="tanggal" 
                       value="{{ old('tanggal', date('Y-m-d')) }}">
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="id_bahan_baku">Bahan Baku</label>
                <select class="form-control @error('id_bahan_baku') is-invalid @enderror" 
                        id="id_bahan_baku" name="id_bahan_baku">
                    <option value="">Pilih Bahan Baku</option>
                    @foreach($bahanBakus as $bahanBaku)
                        <option value="{{ $bahanBaku->id }}" {{ old('id_bahan_baku') == $bahanBaku->id ? 'selected' : '' }}>
                            {{ $bahanBaku->nama_bahan_baku }} (Stok: {{ $bahanBaku->jumlah_stok }} {{ $bahanBaku->satuan }})
                        </option>
                    @endforeach
                </select>
                @error('id_bahan_baku')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="jumlah_stok_masuk">Jumlah Stok Masuk</label>
                <input type="number" class="form-control @error('jumlah_stok_masuk') is-invalid @enderror" 
                       id="jumlah_stok_masuk" name="jumlah_stok_masuk" 
                       placeholder="Jumlah Stok Masuk" value="{{ old('jumlah_stok_masuk') }}" min="1">
                @error('jumlah_stok_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="id_supplier">Supplier</label>
                <select class="form-control @error('id_supplier') is-invalid @enderror" 
                        id="id_supplier" name="id_supplier">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('id_supplier') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama_supplier }}
                        </option>
                    @endforeach
                </select>
                @error('id_supplier')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- <div class="form-group">
                <label for="keterangan">Keterangan (Opsional)</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}
            
            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('stokmasuk.karyawan') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#add-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        // Clear previous error messages
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data stok masuk berhasil ditambahkan",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('stokmasuk.karyawan') }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                
                // Display validation errors
                $.each(errors, function(key, value) {
                    var input = $('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                });

                // Show general error message
                Swal.fire({
                    title: "Gagal!",
                    text: "Terdapat kesalahan dalam pengisian form",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    });
});
</script>
@endsection