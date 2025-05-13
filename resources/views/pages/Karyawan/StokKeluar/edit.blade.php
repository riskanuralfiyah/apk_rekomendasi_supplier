@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stokkeluar.karyawan') }}">Stok Keluar</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Stok Keluar</li>
@endsection

@section('content')
<div class="container mt-1">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4 font-weight-bold">Edit Data Stok Keluar</h3>
            
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            
            <form method="POST" action="{{ route('update.stokkeluar.karyawan', $stokKeluar->id) }}" id="edit-form">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" name="tanggal" 
                           value="{{ old('tanggal', $stokKeluar->tanggal) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="id_bahan_baku" class="form-label">Bahan Baku <span class="text-danger">*</span></label>
                    <select class="form-control @error('id_bahan_baku') is-invalid @enderror" 
                            id="id_bahan_baku" name="id_bahan_baku" required>
                        <option value="">Pilih Bahan Baku</option>
                        @foreach($bahanBakus as $bahanBaku)
                            <option value="{{ $bahanBaku->id }}" 
                                {{ old('id_bahan_baku', $stokKeluar->id_bahan_baku) == $bahanBaku->id ? 'selected' : '' }}
                                data-stok="{{ $bahanBaku->jumlah_stok }}"
                                data-satuan="{{ $bahanBaku->satuan }}"
                                data-oldvalue="{{ $stokKeluar->id_bahan_baku == $bahanBaku->id ? $stokKeluar->jumlah_stok_keluar : 0 }}">
                                {{ $bahanBaku->nama_bahan_baku }} ({{ $bahanBaku->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_bahan_baku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="jumlah_stok_keluar" class="form-label">Jumlah Stok Keluar <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('jumlah_stok_keluar') is-invalid @enderror" 
                           id="jumlah_stok_keluar" name="jumlah_stok_keluar" 
                           value="{{ old('jumlah_stok_keluar', $stokKeluar->jumlah_stok_keluar) }}" 
                           placeholder="Jumlah Stok Keluar" 
                           min="1" required>
                    @error('jumlah_stok_keluar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small id="stokHelp" class="form-text text-muted">
                        Stok tersedia: <span id="stok-tersedia">{{ $stokKeluar->bahanBaku->jumlah_stok ?? 0 }}</span> 
                    </small>
                </div>
                
                <div class="form-group mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $stokKeluar->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('stokkeluar.karyawan') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize with selected bahan baku
    updateStockInfo();
    
    // Update available stock when bahan baku is selected
    $('#id_bahan_baku').change(function() {
        updateStockInfo();
    });

    // Form submission handler
    $('#edit-form').submit(function(e) {
        e.preventDefault();
        
        // Get current values
        const selectedOption = $('#id_bahan_baku').find('option:selected');
        const currentStock = parseInt(selectedOption.data('stok')) || 0;
        const oldValue = parseInt(selectedOption.data('oldvalue')) || 0;
        const inputValue = parseInt($('#jumlah_stok_keluar').val()) || 0;
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Calculate available stock (current stock + old value if same bahan baku)
        const availableStock = ($('#id_bahan_baku').val() == "{{ $stokKeluar->id_bahan_baku }}") 
            ? currentStock + oldValue 
            : currentStock;
        
        // Validate stock
        if (inputValue > availableStock) {
            $('#jumlah_stok_keluar').addClass('is-invalid');
            $('#jumlah_stok_keluar').after('<div class="invalid-feedback">Jumlah melebihi stok tersedia</div>');
            
            Swal.fire({
                title: "Gagal!",
                text: `Jumlah stok keluar (${inputValue}) melebihi stok tersedia (${availableStock})`,
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
        const oldValue = parseInt(selectedOption.data('oldvalue')) || 0;
        
        // Calculate available stock (add back old value if same bahan baku)
        const availableStock = ($('#id_bahan_baku').val() == "{{ $stokKeluar->id_bahan_baku }}") 
            ? currentStock + oldValue 
            : currentStock;
        
        $('#stok-tersedia').text(availableStock);
        $('#satuan-tersedia').text(satuan);
        $('#jumlah_stok_keluar').attr('max', availableStock);
    }
    
    function submitForm() {
        var form = $('#edit-form');
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
                    text: "Data stok keluar berhasil diperbarui",
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