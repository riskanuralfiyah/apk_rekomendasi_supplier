@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Kriteria</a></li>
@endsection

@section('content')
    <!-- Card untuk Tabel dan Fitur Search -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Data Kriteria di dalam Card -->
            <h3 class="mb-3 font-weight-bold">Data Kriteria</h3>

            <!-- Header dengan Search dan Tombol Tambah -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Input Search -->
                <form method="GET" action="{{ route('datakriteria.pemilikmebel') }}" id="searchForm" class="d-flex" style="max-width: 300px;">
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
                <a href="{{ route('create.datakriteria.pemilikmebel') }}" class="btn btn-primary">
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

            <!-- Tabel Data Kriteria -->
            <div class="table-responsive">
                <table class="table table-striped" id="kriteriaTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kriteria</th>
                            <th>Kategori</th>
                            <th>Bobot (%)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriterias as $index => $kriteria)
                        <tr>
                            <td>{{ ($kriterias->currentPage() - 1) * $kriterias->perPage() + $loop->iteration }}</td>
                            <td>{{ $kriteria->nama_kriteria }}</td>
                            <td>{{ ucfirst($kriteria->kategori) }}</td>
                            <td>{{ $kriteria->bobot * 100 }}</td> <!-- Tampilkan dalam persen -->
                            <td>
                                <a href="{{ route('datasubkriteria.pemilikmebel', $kriteria->id) }}" class="btn btn-success btn-sm">Subkriteria</a>
                                <a href="{{ route('edit.datakriteria.pemilikmebel', $kriteria->id) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil text-white"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $kriteria->id }})">
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
                    Showing {{ $kriterias->firstItem() }} to {{ $kriterias->lastItem() }} of {{ $kriterias->total() }} entries
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($kriterias->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $kriterias->previousPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($kriterias->getUrlRange(1, $kriterias->lastPage()) as $page => $url)
                            @if ($page == $kriterias->currentPage())
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
                        @if ($kriterias->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $kriterias->nextPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="next">&raquo;</a>
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
        document.getElementById('deleteForm').setAttribute('data-id', id); // Menyimpan ID di form
        modal.show();
    }

    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = this.getAttribute('data-id');
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal')); // Modal untuk konfirmasi
        modal.hide();  // Menutup modal terlebih dahulu sebelum menjalankan AJAX

        // Mengirim request AJAX untuk penghapusan
        $.ajax({
            url: "{{ url('pemilikmebel/data-kriteria') }}/" + id,
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
                        location.reload(); // Reload halaman untuk menampilkan data yang sudah terupdate
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
    
    // Event listener untuk menutup modal jika tombol batal diklik
    document.querySelector('#confirmDeleteModal .btn-close').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.hide();
    });
</script>
@endsection