@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Supplier</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Supplier -->
      <h3 class="mb-4 font-weight-bold">Form Data Supplier</h3>
      
      <form method="POST" action="{{ route('store.datasupplier.pemilikmebel') }}" id="add-form">
        @csrf
        
        <div class="form-group">
          <label for="nama_supplier">Nama Supplier</label>
          <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                 id="nama_supplier" name="nama_supplier" 
                 placeholder="Nama Supplier" value="{{ old('nama_supplier') }}">
          @error('nama_supplier')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea class="form-control @error('alamat') is-invalid @enderror" 
                    id="alamat" name="alamat" 
                    rows="3" placeholder="Alamat">{{ old('alamat') }}</textarea>
          @error('alamat')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="no_telpon">No. Telepon</label>
          <input type="text" class="form-control @error('no_telpon') is-invalid @enderror" 
                 id="no_telpon" name="no_telpon" 
                 placeholder="No. Telepon" value="{{ old('no_telpon') }}">
          @error('no_telpon')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('datasupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
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
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('datasupplier.pemilikmebel') }}";
                        }
                    });
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                    
                    // Display validation errors
                    $.each(errors, function(key, value) {
                            if (key === 'duplicate') {
                                // Tampilkan error umum (bukan field spesifik)
                                Swal.fire({
                                    title: "Gagal!",
                                    text: value[0],
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            } else {
                                // Tampilkan error di input form
                                var input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                            }
                        });
                    } else {
                        // Error sistem/server (500, dsb)
                        let message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                    // Show general error message
                    Swal.fire({
                        title: "Gagal!",
                        text: message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            }
        });
    });
});
  </script>
@endsection