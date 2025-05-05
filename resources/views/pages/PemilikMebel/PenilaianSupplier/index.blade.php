@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penilaian Supplier</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Judul Penilaian Supplier -->
            <h3 class="mb-3 font-weight-bold">Penilaian Supplier</h3>

            <!-- Informasi Supplier -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="namaSupplier">Nama Supplier</label>
                    <input type="text" class="form-control" id="namaSupplier" value="{{ $supplier->nama_supplier }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" value="{{ $supplier->alamat }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="noTelpon">No. Telpon</label>
                    <input type="text" class="form-control" id="noTelpon" value="{{ $supplier->no_telpon }}" readonly>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end mb-3">
                @if($penilaians->isEmpty())
                    <a href="{{ route('create.penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-primary me-2">
                        <i class="mdi mdi-plus"></i> Tambah
                    </a>
                @else
                <a href="{{ route('edit.penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-warning me-2">
                    <i class="mdi mdi-pencil"></i> Edit
                </a>                
                    
                    <button class="btn btn-danger" onclick="showDeleteModal({{ $penilaians->first()->id }})">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                @endif
            </div>

            <!-- Tabel Penilaian -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kriteria</th>
                            <th>Subkriteria</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penilaians as $index => $penilaian)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $penilaian->kriteria->nama_kriteria }}</td>
                            <td>{{ $penilaian->subkriteria->nama_subkriteria }}</td>
                            <td>{{ $penilaian->subkriteria->nilai }}</td>
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
                <div class="modal-body">Apakah Anda yakin ingin menghapus penilaian ini?</div>
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
            form.action = "{{ url('pemilikmebel/penilaian') }}/{{ $supplier->id }}";
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
        url: "{{ url('pemilikmebel/penilaian') }}/{{ $supplier->id }}",
        type: 'POST',
        data: {
            _method: 'DELETE',
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data subkriteria berhasil dihapus.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // reload halaman untuk merefresh data
                }
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menghapus data.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>
@endsection