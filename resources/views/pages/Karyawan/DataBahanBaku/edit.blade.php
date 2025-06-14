@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('databahanbaku.karyawan') }}">Data Bahan Baku</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Bahan Baku</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Bahan Baku -->
      <h3 class="mb-4 font-weight-bold">Edit Data Bahan Baku</h3>
      
      <form method="POST" action="{{ route('update.databahanbaku.karyawan', $bahanbaku->id) }}" id="edit-form">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
          <label for="nama_bahan_baku" class="form-label">Nama Bahan Baku</label>
          <input type="text" class="form-control @error('nama_bahan_baku') is-invalid @enderror" 
                 id="nama_bahan_baku" name="nama_bahan_baku" 
                 value="{{ old('nama_bahan_baku', $bahanbaku->nama_bahan_baku) }}" 
                 placeholder="Nama Bahan Baku">
          @error('nama_bahan_baku')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="ukuran" class="form-label">Ukuran (cm)</label>
          <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                 id="ukuran" name="ukuran" 
                 value="{{ old('ukuran', $bahanbaku->ukuran) }}" 
                 placeholder="Ukuran (contoh: 6x15)">
          @error('ukuran')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="stok_minimum" class="form-label">Stok Minimum</label>
          <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror" 
                 id="stok_minimum" name="stok_minimum" 
                 value="{{ old('stok_minimum', $bahanbaku->stok_minimum) }}" 
                 placeholder="Stok Minimum" min="0">
          @error('stok_minimum')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="jumlah_stok" class="form-label">Jumlah Stok (Batang)</label>
          <input type="number" class="form-control @error('jumlah_stok') is-invalid @enderror" 
                 id="jumlah_stok" name="jumlah_stok" 
                 value="{{ old('jumlah_stok', $bahanbaku->jumlah_stok) }}" 
                 placeholder="Jumlah Stok" min="0">
          @error('jumlah_stok')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('databahanbaku.karyawan') }}" class="btn btn-light">Cancel</a>
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
                          window.location.href = "{{ route('databahanbaku.karyawan') }}";
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