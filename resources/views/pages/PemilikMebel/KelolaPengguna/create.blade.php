@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kelolapengguna.pemilikmebel') }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Pengguna</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Pengguna -->
      <h3 class="mb-4 font-weight-bold">Form Data Pengguna</h3>
      
      <form method="POST" action="{{ route('store.kelolapengguna.pemilikmebel') }}" id="add-form">
        @csrf
        
        <div class="form-group">
          <label for="nama_pengguna">Nama Pengguna</label>
          <input type="text" class="form-control @error('nama_pengguna') is-invalid @enderror" 
                 id="nama_pengguna" name="nama_pengguna" 
                 placeholder="Nama Pengguna" value="{{ old('nama_pengguna') }}">
          @error('nama_pengguna')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" 
                 id="email" name="email" 
                 placeholder="Email" value="{{ old('email') }}">
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="role">Role</label>
          <select class="form-control @error('role') is-invalid @enderror" 
                  id="role" name="role">
              <option value="">-- Pilih Role --</option>
              <option value="pemilikmebel" {{ old('role') == 'pemilikmebel' ? 'selected' : '' }}>Pemilik Mebel</option>
              <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
          </select>
          @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('kelolapengguna.pemilikmebel') }}" class="btn btn-light">Cancel</a>
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
                            window.location.href = "{{ route('kelolapengguna.pemilikmebel') }}";
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