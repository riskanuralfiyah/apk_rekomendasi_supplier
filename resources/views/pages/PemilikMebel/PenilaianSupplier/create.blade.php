@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Penilaian Supplier</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-4 font-weight-bold">Form Penilaian Supplier</h3>
        
        <form method="POST" action="{{ route('store.penilaiansupplier.pemilikmebel', $supplier->id) }}" id="add-form">
            @csrf

            @foreach($kriterias as $kriteria)
            <div class="form-group">
                <label for="kriteria_{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</label>
                <select class="form-control @error('kriteria.'.$kriteria->id) is-invalid @enderror" 
                        id="kriteria_{{ $kriteria->id }}" 
                        name="kriteria[{{ $kriteria->id }}]" required>
                    <option value="">-- Pilih {{ $kriteria->nama_kriteria }} --</option>
                    @foreach($kriteria->subkriterias as $subkriteria)
                        <option value="{{ $subkriteria->id }}" {{ old('kriteria.'.$kriteria->id) == $subkriteria->id ? 'selected' : '' }}>
                            {{ $subkriteria->nama_subkriteria }} (Nilai: {{ $subkriteria->nilai }})
                        </option>
                    @endforeach
                </select>
                @error('kriteria.'.$kriteria->id)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endforeach

            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-light">Cancel</a>
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
                        text: "Data penilaian berhasil ditambahkan",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}";
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