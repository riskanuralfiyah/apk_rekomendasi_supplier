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

        @php
            // total bobot dari kriteria lain (dalam persen)
            $totalBobotSaatIni = $kriterias->sum('bobot') * 100;

            // jika sedang edit data, kurangi bobot lama dari total agar tidak double dihitung
            if (isset($kriteria)) {
                $totalBobotSaatIni -= $kriteria->bobot * 100;
            }
        @endphp
        
        <div class="form-group">
          <label for="bobot" class="form-label">Bobot (%)</label>
          <input type="number" class="form-control @error('bobot') is-invalid @enderror" 
                 id="bobot" name="bobot" 
                 value="{{ old('bobot', isset($kriteria) ? $kriteria->bobot * 100 : '') }}" 
                 placeholder="Bobot (0-100)" 
                 min="1" max="100" step="1">
            <small class="form-text text-muted">
                Masukkan nilai antara 1-100. Sisa bobot yang tersedia:
                <span id="sisa-bobot-info" class="font-weight-bold text-primary"></span>
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalBobotSaatIni = {{ $totalBobotSaatIni }};
        const bobotInput = document.getElementById('bobot');
        const sisaBobotInfo = document.getElementById('sisa-bobot-info');

        function updateSisaBobot() {
            const inputValue = parseFloat(bobotInput.value) || 0;
            const sisa = 100 - totalBobotSaatIni;

            const sisaSetelahInput = sisa - inputValue;

            // update isi teks
            sisaBobotInfo.textContent = sisaSetelahInput + '%';

            // warnai teks jika melebihi batas
            if (sisaSetelahInput < 0) {
                sisaBobotInfo.classList.remove('text-primary');
                sisaBobotInfo.classList.add('text-danger');
            } else {
                sisaBobotInfo.classList.remove('text-danger');
                sisaBobotInfo.classList.add('text-primary');
            }
        }

        // jalankan saat load dan saat input berubah
        updateSisaBobot();
        bobotInput.addEventListener('input', updateSisaBobot);
    });
</script>


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
                            window.location.href = "{{ route('datakriteria.pemilikmebel') }}";
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