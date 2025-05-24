@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item"><a href="{{ route('datasubkriteria.pemilikmebel', $subkriteria->kriteria->id) }}">Data Subkriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Sub Kriteria</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Sub Kriteria -->
      <h3 class="mb-4 font-weight-bold">Edit Data Subkriteria</h3>

      <form method="POST" action="{{ route('update.datasubkriteria.pemilikmebel', ['kriteriaId' => $kriteria->id, 'id' => $subkriteria->id]) }}" id="edit-form">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="nama_subkriteria" class="form-label">Nama Subkriteria</label>
          <input type="text" class="form-control @error('nama_subkriteria') is-invalid @enderror"
                 id="nama_subkriteria" name="nama_subkriteria"
                 value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}"
                 placeholder="Nama Sub Kriteria" required>
          @error('nama')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="nilai" class="form-label">Nilai</label>
          <input type="number" class="form-control @error('nilai') is-invalid @enderror"
                 id="nilai" name="nilai"
                 value="{{ old('nilai', $subkriteria->nilai) }}"
                 placeholder="Nilai Sub Kriteria"
                 min="1" step="1" required>
          <small class="form-text text-muted">
            Benefit = Nilai besar lebih baik, Cost = Nilai kecil lebih baik.
          </small>
          @error('nilai')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('datasubkriteria.pemilikmebel', $subkriteria->kriteria->id) }}" class="btn btn-light">Cancel</a>
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
                          window.location.href = "{{ route('datasubkriteria.pemilikmebel', $subkriteria->kriteria->id) }}";
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
