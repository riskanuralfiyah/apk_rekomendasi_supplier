@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokkeluar.karyawan') }}">Stok Keluar</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Stok Keluar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-4 font-weight-bold">Form Tambah Stok Keluar</h3>
        
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('store.stokkeluar.karyawan') }}" id="add-form">
            @csrf
            
            <div class="form-group">
                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                       id="tanggal" name="tanggal" 
                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="id_bahan_baku">Bahan Baku <span class="text-danger">*</span></label>
                <select class="form-control @error('id_bahan_baku') is-invalid @enderror" 
                        id="id_bahan_baku" name="id_bahan_baku" required>
                    <option value="">Pilih Bahan Baku</option>
                    @foreach($bahanBakus as $bahanBaku)
                    <option value="{{ $bahanBaku->id }}"
                        data-stok="{{ $bahanBaku->jumlah_stok }}"
                        data-ukuran="{{ $bahanBaku->ukuran }}"
                        {{ old('id_bahan_baku') == $bahanBaku->id ? 'selected' : '' }}>
                        {{ $bahanBaku->nama_bahan_baku }} - {{ $bahanBaku->ukuran }} (Stok: {{ $bahanBaku->jumlah_stok }})
                    </option>
                @endforeach
                
                </select>
                @error('id_bahan_baku')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="jumlah_stok_keluar">Jumlah Stok Keluar <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('jumlah_stok_keluar') is-invalid @enderror" 
                       id="jumlah_stok_keluar" name="jumlah_stok_keluar" 
                       placeholder="Jumlah Stok Keluar" 
                       value="{{ old('jumlah_stok_keluar') }}" 
                       min="1" required>
                @error('jumlah_stok_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small id="stokHelp" class="form-text text-muted">
                    Stok tersedia: <span id="stok-tersedia">0</span>
                </small>
            </div>
            
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('stokkeluar.karyawan') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize with selected option if exists
    const initialBahanBaku = $('#id_bahan_baku').find('option:selected');
    if (initialBahanBaku.length && initialBahanBaku.val() !== "") {
        updateStockInfo();
    }

    // Update available stock when bahan baku is selected
    $('#id_bahan_baku').change(function() {
        updateStockInfo();
    });

    // Real-time validation before submission
    $('#add-form').submit(function(e) {
        e.preventDefault();
        
        // Get current stock and input value
        const currentStock = parseInt($('#stok-tersedia').text()) || 0;
        const inputValue = parseInt($('#jumlah_stok_keluar').val()) || 0;
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate stock
        if (inputValue > currentStock) {
            $('#jumlah_stok_keluar').addClass('is-invalid');
            $('#jumlah_stok_keluar').after('<div class="invalid-feedback">Jumlah melebihi stok tersedia</div>');
            
            Swal.fire({
                title: "Gagal!",
                text: "Jumlah stok keluar melebihi stok tersedia",
                icon: "error",
                confirmButtonText: "OK"
            });
            return false;
        }

        // Proceed with AJAX if validation passes
        submitForm();
    });
    
    function updateStockInfo() {
        const selectedOption = $('#id_bahan_baku').find('option:selected');
        const currentStock = selectedOption.data('stok') || 0;
        const satuan = selectedOption.data('satuan') || '';
        
        $('#stok-tersedia').text(currentStock);
        $('#satuan-tersedia').text(satuan);
        $('#jumlah_stok_keluar').attr({
            'max': currentStock,
            'title': `Maksimal: ${currentStock} ${satuan}`
        });
    }
    
    function submitForm() {
        var form = $('#add-form');
        var formData = new FormData(form[0]);

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
                    text: "Data stok keluar berhasil ditambahkan",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('stokkeluar.karyawan') }}";
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
    }
});
</script>
@endsection