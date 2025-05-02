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

            <!-- Tombol Tambah Penilaian -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('create.penilaiansupplier.pemilikmebel', $supplier->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah Penilaian
                </a>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penilaians as $index => $penilaian)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $penilaian->kriteria->nama_kriteria }}</td>
                            <td>{{ $penilaian->subkriteria->nama_subkriteria }}</td>
                            <td>{{ $penilaian->subkriteria->nilai }}</td>
                            <td>
                                <a href="{{ route('edit.penilaiansupplier.pemilikmebel', ['supplierId' => $supplier->id, 'id' => $penilaian->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil text-white"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $penilaian->id }})">
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
            form.action = "{{ url('pemilikmebel/penilaian-supplier') }}/" + id;
            modal.show();
        }
    </script>
@endsection