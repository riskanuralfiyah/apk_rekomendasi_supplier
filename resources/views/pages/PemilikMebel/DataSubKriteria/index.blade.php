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
                <form method="GET" action="{{ route('datasubkriteria.pemilikmebel', $kriteria->id) }}" id="searchForm" class="d-flex" style="max-width: 300px;">
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

                <a href="{{ route('create.datasubkriteria.pemilikmebel', ['kriteriaId' => $kriteria->id]) }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah
                </a>
            </div>

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
                            <td>{{ ($subkriterias->currentPage() - 1) * $subkriterias->perPage() + $loop->iteration }}</td>
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

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $subkriterias->firstItem() }} to {{ $subkriterias->lastItem() }} of {{ $subkriterias->total() }} entries
                </div>

                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        @if ($subkriterias->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $subkriterias->previousPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="prev">&laquo;</a></li>
                        @endif

                        @foreach ($subkriterias->getUrlRange(1, $subkriterias->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $subkriterias->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if ($subkriterias->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $subkriterias->nextPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="next">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
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
            form.action = "{{ url('pemilikmebel/data-subkriteria') }}/" + id;
            modal.show();
        }

        function updatePerPage(value) {
            const form = document.getElementById('searchForm');
            form.querySelector('input[name="per_page"]').value = value;
            form.submit();
        }

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchForm').submit();
            }
        });
    </script>

    <style>
        .pagination .page-item.active .page-link {
            background-color: #4b49ac;
            border-color: #4b49ac;
        }
        .pagination .page-link {
            color: #4b49ac;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }
    </style>
@endsection
