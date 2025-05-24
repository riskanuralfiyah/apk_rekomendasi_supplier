@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Supplier</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Supplier -->
      <h3 class="mb-4 font-weight-bold">Edit Data Supplier</h3>
      <form method="POST" action="{{ route('update.datasupplier.pemilikmebel', $supplier->id) }}" id="edit-form">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
          <label for="nama_supplier" class="form-label">Nama Supplier</label>
          <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                 id="nama_supplier" name="nama_supplier" 
                 value="{{ old('nama_supplier', $supplier->nama_supplier) }}" 
                 placeholder="Nama Supplier">
          @error('nama_supplier')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="alamat" class="form-label">Alamat</label>
          <textarea class="form-control @error('alamat') is-invalid @enderror" 
                    id="alamat" name="alamat" rows="3"
                    placeholder="Alamat">{{ old('alamat', $supplier->alamat) }}</textarea>
          @error('alamat')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="no_telpon" class="form-label">No. Telepon</label>
          <input type="text" class="form-control @error('no_telpon') is-invalid @enderror" 
                 id="no_telpon" name="no_telpon" 
                 value="{{ old('no_telpon', $supplier->no_telpon) }}" 
                 placeholder="No. Telepon">
          @error('no_telpon')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('datasupplier.pemilikmebel') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
      $('#edit-form').submit(function(e) {
          e.preventDefault();
          var form = $(this);
          var formData = new FormData(this);

          // Hapus pesan error sebelumnya
          $('.is-invalid').removeClass('is-invalid');
          $('.invalid-feedback').remove();

          $.ajax({
              url: form.attr('action'), // route untuk update
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