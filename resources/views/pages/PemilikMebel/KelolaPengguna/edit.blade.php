@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kelolapengguna.pemilikmebel') }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Pengguna</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Pengguna -->
      <h3 class="mb-4 font-weight-bold">Edit Data Pengguna</h3>
      <form method="POST" action="{{ route('update.kelolapengguna.pemilikmebel', $pengguna->id) }}" id="edit-form">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
          <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
          <input type="text" class="form-control @error('nama_pengguna') is-invalid @enderror" 
                 id="nama_pengguna" name="nama_pengguna" 
                 value="{{ old('nama_pengguna', $pengguna->nama_pengguna) }}" 
                 placeholder="Nama Pengguna">
          @error('nama_pengguna')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" 
                 id="email" name="email" 
                 value="{{ old('email', $pengguna->email) }}" 
                 placeholder="Email">
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-control @error('role') is-invalid @enderror" 
                  id="role" name="role">
              <option value="">-- Pilih Role --</option>
              <option value="pemilikmebel" {{ old('role', $pengguna->role) == 'pemilikmebel' ? 'selected' : '' }}>Pemilik Mebel</option>
              <option value="karyawan" {{ old('role', $pengguna->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
          </select>
          @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('kelolapengguna.pemilikmebel') }}" class="btn btn-light">Cancel</a>
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