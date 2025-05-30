@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola Pengguna</a></li>
@endsection

@section('content')
    <!-- Card untuk Tabel dan Fitur Search -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Data Supplier di dalam Card -->
            <h3 class="mb-3 font-weight-bold">Data Pengguna</h3>

            <!-- Header dengan Search dan Tombol Tambah -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Input Search -->
                <form method="GET" action="{{ route('kelolapengguna.pemilikmebel') }}" id="searchForm" class="d-flex" style="max-width: 300px;">
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="searchButton">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                </form>

                <!-- Tombol Tambah -->
                <a href="{{ route('create.kelolapengguna.pemilikmebel') }}" class="btn btn-primary">
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

            <!-- Tabel Data Supplier -->
            <div class="table-responsive">
                <table class="table table-striped" id="supplierTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penggunas as $index => $pengguna)
                        <tr>
                            <td>{{ ($penggunas->currentPage() - 1) * $penggunas->perPage() + $loop->iteration }}</td>
                            <td>{{ $pengguna->nama_pengguna }}</td>
                            <td>{{ $pengguna->email }}</td>
                            <td>{{ ucwords(str_replace('mebel', 'mebel', str_replace('pemilik', 'Pemilik ', $pengguna->role))) }}</td>
                            <td>
                                <a href="{{ route('edit.kelolapengguna.pemilikmebel', $pengguna->id) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil text-white"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $pengguna->id }})">
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
                    Showing {{ $penggunas->firstItem() }} to {{ $penggunas->lastItem() }} of {{ $penggunas->total() }} entries
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($penggunas->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $penggunas->previousPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($penggunas->getUrlRange(1, $penggunas->lastPage()) as $page => $url)
                            @if ($page == $penggunas->currentPage())
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
                        @if ($penggunas->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $penggunas->nextPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="next">&raquo;</a>
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
            document.getElementById('deleteForm').action = "{{ url('pemilikmebel/kelola-pengguna') }}/" + id;
            modal.show();
        }

        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            
            // Menutup modal sebelum menjalankan ajax request
            modal.hide();

            $.ajax({
                url: form.action,
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
                            location.reload();
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