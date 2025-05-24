@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datakriteria.pemilikmebel') }}">Data Kriteria</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Subkriteria</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-3 font-weight-bold">Data Subkriteria</h3>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="namaKriteria">Nama Kriteria</label>
                    <input type="text" class="form-control" id="namaKriteria" value="{{ $kriteria->nama_kriteria }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="kategori">Kategori</label>
                    <input type="text" class="form-control" id="kategori" value="{{ ucfirst($kriteria->kategori) }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="bobot">Bobot</label>
                    <input type="text" class="form-control" id="bobot" value="{{ $kriteria->bobot * 100 }}%" readonly>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div></div>
                <a href="{{ route('create.datasubkriteria.pemilikmebel', ['kriteriaId' => $kriteria->id]) }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped" id="subkriteriaTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Subkriteria</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subkriterias as $index => $subkriteria)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subkriteria->nama_subkriteria }}</td>
                            <td>{{ $subkriteria->nilai }}</td>
                            <td>
                                <a href="{{ route('edit.datasubkriteria.pemilikmebel', ['kriteriaId' => $kriteria->id, 'id' => $subkriteria->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil text-white"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $subkriteria->id }})">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin menghapus data ini?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(id) {
        var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        var form = document.getElementById('deleteForm');
        form.action = "{{ url('pemilikmebel/data-subkriteria') }}/{{ $kriteria->id }}/" + id;
        modal.show();
        }
    </script>

<script type="text/javascript">
    function showDeleteModal(id) {
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        document.getElementById('deleteForm').setAttribute('data-id', id); // simpan ID
        modal.show();
    }

    document.getElementById('deleteForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = this.getAttribute('data-id');
    const form = this;
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));  // Mengambil referensi modal

    // Menutup modal sebelum menjalankan ajax request dan menampilkan alert Swal
    modal.hide();

    $.ajax({
        url: "{{ url('pemilikmebel/data-subkriteria') }}/{{ $kriteria->id }}/" + id,
        type: 'POST',
        data: {
            _method: 'DELETE',
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            Swal.fire({
                title: 'Berhasil!',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // reload halaman untuk merefresh data
                }
            });
        },
        error: function(xhr) {
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                // Menampilkan notifikasi kesalahan setelah modal ditutup
                Swal.fire({
                    title: 'Gagal!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    willClose: () => {
                        // Refresh atau aktifkan ulang tombol setelah Swal ditutup
                        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                        modal.show();  // Menunjukkan kembali modal setelah Swal ditutup
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); 
                    }
                });
            }
    });
});
</script>
@endsection
