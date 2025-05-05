@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Data Kriteria</li>
@endsection

@section('content')
<div class="container mt-1">
  <div class="card">
    <div class="card-body">
      <!-- Judul Halaman Form Edit Data Kriteria -->
      <h3 class="mb-4 font-weight-bold">Edit Data Kriteria</h3>
      
      <form method="POST" action="{{ route('update.datakriteria.pemilikmebel', $kriteria->id) }}" id="edit-form">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
          <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
          <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" 
                 id="nama_kriteria" name="nama_kriteria" 
                 value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" 
                 placeholder="Nama Kriteria">
          @error('nama_kriteria')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="kategori" class="form-label">Kategori</label>
          <select class="form-control @error('kategori') is-invalid @enderror" 
                  id="kategori" name="kategori">
              <option value="">-- Pilih Kategori --</option>
              <option value="cost" {{ old('kategori', $kriteria->kategori) == 'cost' ? 'selected' : '' }}>Cost (Lebih kecil, lebih baik)</option>
              <option value="benefit" {{ old('kategori', $kriteria->kategori) == 'benefit' ? 'selected' : '' }}>Benefit (Lebih besar, lebih baik)</option>
          </select>
          @error('kategori')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <label for="bobot" class="form-label">Bobot (%)</label>
          <input type="number" class="form-control @error('bobot') is-invalid @enderror" 
                 id="bobot" name="bobot" 
                 value="{{ old('bobot', isset($kriteria) ? $kriteria->bobot * 100 : '') }}" 
                 placeholder="Bobot (0-100)" 
                 min="1" max="100" step="1">
          <small class="form-text text-muted">
            Masukkan nilai antara 1-100. Total semua bobot kriteria tidak boleh melebihi 100%.
            <span id="total-bobot-info" class="font-weight-bold"></span>
          </small>
          @error('bobot')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('datakriteria.pemilikmebel') }}" class="btn btn-light">Cancel</a>
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
                      text: "Data kriteria berhasil diperbarui",
                      icon: "success",
                      confirmButtonText: "OK"
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href = "{{ route('datakriteria.pemilikmebel') }}";
                      }
                  });
              },
              error: function(xhr, status, error) {
                  var errors = xhr.responseJSON.errors;
                  
                  $.each(errors, function(key, value) {
                      var input = $('[name="' + key + '"]');
                      input.addClass('is-invalid');
                      input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                  });

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