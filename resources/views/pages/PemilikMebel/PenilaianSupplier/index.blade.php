@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penilaian Supplier</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Judul Penilaian Supplier -->
            <h3 class="mb-4 font-weight-bold">Penilaian Supplier</h3>

            <!-- Informasi Supplier -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="namaSupplier">Nama Supplier</label>
                    <input type="text" class="form-control" id="namaSupplier" value="PT Kayu Jaya Abadi" readonly>
                </div>
                <div class="col-md-4">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" value="Jl. Industri No. 45, Cirebon" readonly>
                </div>
                <div class="col-md-4">
                    <label for="noTelpon">No. Telpon</label>
                    <input type="text" class="form-control" id="noTelpon" value="08123456789" readonly>
                </div>
            </div>

            <!-- Daftar Penilaian -->
            <div id="assessmentList">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Daftar Penilaian</h5>
                    <div>
                        <button class="btn btn-primary me-2" onclick="showAssessmentForm()" id="tambahButton">
                            <i class="mdi mdi-plus"></i> Tambah Penilaian
                        </button>                        
                        <button class="btn btn-danger" onclick="showDeleteAllModal()">
                            <i class="mdi mdi-delete"></i> Hapus Semua
                        </button>
                    </div>
                </div>

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
                            <tr>
                                <td>1</td>
                                <td>Kualitas</td>
                                <td>Kayu tua, lurus, padat</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Harga</td>
                                <td>Standar</td>
                                <td>60</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pelayanan</td>
                                <td>Baik</td>
                                <td>100</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Penilaian (Awalnya hidden) -->
            <div class="card d-none" id="assessmentForm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Form Penilaian Supplier</h5>
                </div>
                <div class="card-body">
                    <form id="penilaianForm">
                        <!-- Kriteria Kualitas -->
                        <div class="form-group mb-3">
                            <label for="kualitas">Kualitas</label>
                            <select class="form-control" id="kualitas" required>
                                <option value="">-- Pilih Subkriteria --</option>
                                <option value="1">Kayu tua, lurus, padat (Nilai: 100)</option>
                                <option value="2">Kayu tua, bengkok, padat (Nilai: 80)</option>
                                <option value="3">Kayu muda, lurus, padat (Nilai: 60)</option>
                                <option value="4">Kayu muda, bengkok, padat (Nilai: 40)</option>
                                <option value="5">Kayu muda, bengkok, lunak (Nilai: 20)</option>
                            </select>
                        </div>

                        <!-- Kriteria Harga -->
                        <div class="form-group mb-3">
                            <label for="harga">Harga</label>
                            <select class="form-control" id="harga" required>
                                <option value="">-- Pilih Subkriteria --</option>
                                <option value="1">Murah (Nilai: 100)</option>
                                <option value="2">Standar (Nilai: 60)</option>
                                <option value="3">Mahal (Nilai: 20)</option>
                            </select>
                        </div>

                        <!-- Kriteria Pelayanan -->
                        <div class="form-group mb-3">
                            <label for="pelayanan">Pelayanan</label>
                            <select class="form-control" id="pelayanan" required>
                                <option value="">-- Pilih Subkriteria --</option>
                                <option value="1">Baik (Nilai: 100)</option>
                                <option value="2">Cukup (Nilai: 60)</option>
                                <option value="3">Kurang (Nilai: 20)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" onclick="cancelForm()">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua -->
    <div class="modal fade" id="confirmDeleteAllModal" tabindex="-1" aria-labelledby="confirmDeleteAllModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteAllModalLabel">Konfirmasi Hapus Semua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus semua penilaian?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAll">Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isDataAdded = false; // flag sudah tambah data atau belum
    
        function showAssessmentForm() {
            if (!isDataAdded) {
                // kalau belum pernah tambah ➔ redirect ke create
                window.location.href = "{{ route('create.penilaiansupplier.pemilikmebel') }}";
            } else {
                // kalau sudah pernah tambah ➔ redirect ke edit
                window.location.href = "{{ route('edit.penilaiansupplier.pemilikmebel') }}";
            }
        }
    
        // tombol batal (kalau dipakai)
        function cancelForm() {
            document.getElementById('assessmentForm').classList.add('d-none');
            document.getElementById('assessmentList').classList.remove('d-none');
        }
    
        // submit form
        document.getElementById('penilaianForm').addEventListener('submit', function(e) {
            e.preventDefault();
    
            if (!isDataAdded) {
                alert('Penilaian berhasil ditambahkan!');
                isDataAdded = true;
                changeButtonToEdit();
            } else {
                alert('Penilaian berhasil diperbarui!');
            }
    
            cancelForm();
        });
    
        function changeButtonToEdit() {
            const tambahButton = document.getElementById('tambahButton');
            tambahButton.innerHTML = '<i class="mdi mdi-pencil"></i> Edit Penilaian';
        }
    
        function resetButtonToTambah() {
            const tambahButton = document.getElementById('tambahButton');
            tambahButton.innerHTML = '<i class="mdi mdi-plus"></i> Tambah Penilaian';
        }
    
        // hapus semua
        function showDeleteAllModal() {
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteAllModal'));
            modal.show();
        }
    
        document.getElementById('confirmDeleteAll').addEventListener('click', function() {
            alert('Semua penilaian berhasil dihapus!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteAllModal'));
            modal.hide();
    
            // reset kondisi
            isDataAdded = false;
            resetButtonToTambah();
        });
    </script>    
    
@endsection
