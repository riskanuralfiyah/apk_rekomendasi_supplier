@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Bahan Baku</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-3 font-weight-bold">Data Bahan Baku</h3>

        <!-- Header dengan Search -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <!-- Search Form -->
            <form method="GET" action="{{ route('databahanbaku.pemilikmebel') }}" id="searchForm" class="d-flex flex-column" style="max-width: 300px;">
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

                    <a href="{{ route('databahanbaku.pemilikmebel') }}" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Show Entries -->
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

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-striped" id="bahanBakuTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Bahan Baku</th>
                        <th>Satuan</th>
                        <th>Stok Minimum</th>
                        <th>Jumlah Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bahanbakus as $index => $bahanbaku)
                    <tr>
                        <td>{{ ($bahanbakus->currentPage() - 1) * $bahanbakus->perPage() + $loop->iteration }}</td>
                        <td>{{ $bahanbaku->nama_bahan_baku }}</td>
                        <td>{{ $bahanbaku->satuan }}</td>
                        <td>{{ $bahanbaku->stok_minimum }}</td>
                        <td>{{ $bahanbaku->jumlah_stok }}</td>
                        <td>
                            <span class="badge badge-{{ $bahanbaku->jumlah_stok <= 10 ? 'danger' : 'success' }}">
                                {{ $bahanbaku->jumlah_stok <= 10 ? 'Perlu Restock' : 'Aman' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $bahanbakus->firstItem() }} to {{ $bahanbakus->lastItem() }} of {{ $bahanbakus->total() }} entries
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page --}}
                    @if ($bahanbakus->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $bahanbakus->previousPageUrl() }}&per_page={{ request('per_page') }}&search={{ request('search') }}&status={{ request('status') }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($bahanbakus->getUrlRange(1, $bahanbakus->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $bahanbakus->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&per_page={{ request('per_page') }}&search={{ request('search') }}&status={{ request('status') }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page --}}
                    @if ($bahanbakus->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $bahanbakus->nextPageUrl() }}&per_page={{ request('per_page') }}&search={{ request('search') }}&status={{ request('status') }}" rel="next">&raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Filter Modal (Baru) -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="GET" action="{{ route('databahanbaku.pemilikmebel') }}" class="modal-content">
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

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('searchForm').submit();
        }
    });
</script>
@endsection
