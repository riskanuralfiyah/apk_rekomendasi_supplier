@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Bahan Baku</li>
@endsection

@section('content')
    <!-- Card untuk Tabel dan Fitur Search -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Data Bahan Baku di dalam Card -->
            <h3 class="mb-3 font-weight-bold">Data Bahan Baku</h3>

            <!-- Header dengan Search dan Tombol Tambah -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Input Search -->
                <form method="GET" action="{{ route('databahanbaku.karyawan') }}" id="searchForm" class="d-flex flex-column" style="max-width: 300px;">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search" value="{{ request('search') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="searchButton">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
    
                    <div class="d-flex" style="gap: 10px; margin-top: 10px;">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filterModal">
                            <i class="mdi mdi-filter mr-1"></i> Filter
                        </button>
    
                        <a href="{{ route('databahanbaku.karyawan') }}" class="btn btn-secondary btn-sm">
                            <i class="mdi mdi-refresh"></i> Reset
                        </a>
                    </div>
                </form>

                <!-- Tombol Tambah -->
                <a href="{{ route('create.databahanbaku.karyawan') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah
                </a>
            </div>

            <!-- Show Entries di bawah Tombol Tambah -->
            <div class="d-flex justify-content-end mb-3">
                <div>
                    Show
                    <select id="showEntries" class="form-control form-control-sm d-inline-block" style="width: auto;" onchange="updatePerPage(this.value)">
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    entries
                </div>
            </div>

            <!-- Tabel Data Bahan Baku -->
            <div class="table-responsive">
                <table class="table table-striped" id="bahanBakuTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Bahan Baku</th>
                            <th>Ukuran (cm)</th>
                            <th>Stok Minimum</th>
                            <th>Jumlah Stok (Batang)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bahanbakus as $index => $bahanbaku)
                        <tr>
                            <td>{{ ($bahanbakus->currentPage() - 1) * $bahanbakus->perPage() + $loop->iteration }}</td>
                            <td>{{ $bahanbaku->nama_bahan_baku }}</td>
                            <td>{{ $bahanbaku->ukuran }}</td>
                            <td>{{ $bahanbaku->stok_minimum }}</td>
                            <td>{{ $bahanbaku->jumlah_stok }}</td>
                            <td>
                                <span class="badge badge-{{ $bahanbaku->jumlah_stok <= 10 ? 'danger' : 'success' }}">
                                    {{ $bahanbaku->jumlah_stok <= 10 ? 'Perlu Restock' : 'Aman' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('edit.databahanbaku.karyawan', $bahanbaku->id) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil text-white"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $bahanbaku->id }})">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Data dan Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <!-- Total Data -->
                <div>
                    Showing {{ $bahanbakus->firstItem() }} to {{ $bahanbakus->lastItem() }} of {{ $bahanbakus->total() }} entries
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($bahanbakus->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $bahanbakus->previousPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($bahanbakus->getUrlRange(1, $bahanbakus->lastPage()) as $page => $url)
                            @if ($page == $bahanbakus->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($bahanbakus->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $bahanbakus->nextPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
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
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
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

    <!-- Filter Modal (Baru) -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="GET" action="{{ route('databahanbaku.karyawan') }}" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="statusFilter">Status Stok</label>
                    <select class="form-control" id="statusFilter" name="status">
                        <option value="">Semua Status</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="perlu_restock" {{ request('status') == 'perlu_restock' ? 'selected' : '' }}>Perlu Restock</option>
                    </select>
                </div>
                <!-- Simpan search dan per_page agar tetap terbawa -->
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
        </form>
    </div>
</div>

    <script>
        function updatePerPage(value) {
            const form = document.getElementById('searchForm');
            form.querySelector('input[name="per_page"]').value = value;
            form.submit();
        }

        // Fitur instant search saat menekan Enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchForm').submit();
            }
        });
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
        url: "{{ url('karyawan/data-bahan-baku') }}/" + id,
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