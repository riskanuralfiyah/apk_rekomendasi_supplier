@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}">Penilaian Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Penilaian Supplier</li>
@endsection

@section('content')
<div class="container mt-1">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4 font-weight-bold">Edit Penilaian Supplier</h3>
            
            <form method="POST" action="{{ route('update.penilaiansupplier.pemilikmebel', $supplier->id) }}" id="edit-form">
                @csrf
                @method('PUT')

                @foreach($penilaians as $penilaian)
                <div class="form-group mb-3">
                    <label class="form-label">{{ $penilaian->kriteria->nama_kriteria }}</label>
                    <select class="form-control" 
                            name="penilaian[{{ $penilaian->id }}][id_subkriteria]" required>
                        @foreach($penilaian->kriteria->subkriterias as $subkriteria)
                        <option value="{{ $subkriteria->id }}"
                            {{ $penilaian->id_subkriteria == $subkriteria->id ? 'selected' : '' }}>
                            {{ $subkriteria->nama_subkriteria }} (Nilai: {{ $subkriteria->nilai }})
                        </option>
                        @endforeach
                    </select>
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-light">Cancel</a>
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
                        text: "Data penilaian berhasil diperbarui",
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