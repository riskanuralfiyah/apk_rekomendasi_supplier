@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Data Kriteria</li>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
      <!-- Judul Halaman Form Data Kriteria -->
      <h3 class="mb-4 font-weight-bold">Form Data Kriteria</h3>
      
      <form method="POST" action="{{ route('store.datakriteria.pemilikmebel') }}" id="add-form">
        @csrf
        
        <div class="form-group">
          <label for="nama_kriteria">Nama Kriteria</label>
          <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" 
                 id="nama_kriteria" name="nama_kriteria" 
                 placeholder="Nama Kriteria" value="{{ old('nama_kriteria') }}">
          @error('nama_kriteria')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="kategori">Kategori</label>
          <select class="form-control @error('kategori') is-invalid @enderror" 
                  id="kategori" name="kategori">
              <option value="">-- Pilih Kategori --</option>
              <option value="cost" {{ old('kategori') == 'cost' ? 'selected' : '' }}>Cost (Lebih kecil, lebih baik)</option>
              <option value="benefit" {{ old('kategori') == 'benefit' ? 'selected' : '' }}>Benefit (Lebih besar, lebih baik)</option>
          </select>
          @error('kategori')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
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
        
        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
        <a href="{{ route('datakriteria.pemilikmebel') }}" class="btn btn-light">Cancel</a>
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
                        text: "Data kriteria berhasil ditambahkan",
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