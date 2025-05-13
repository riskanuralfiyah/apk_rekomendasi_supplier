@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Bahan Baku</li>
@endsection

@section('content')
    <!-- Card untuk Tabel dan Fitur Search -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Data Bahan Baku di dalam Card -->
            <h3 class="mb-3 font-weight-bold">Data Bahan Baku</h3>

            <!-- Header dengan Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Input Search -->
                <form method="GET" action="{{ route('databahanbaku.pemilikmebel') }}" id="searchForm" class="d-flex" style="max-width: 300px;">
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

            <!-- Tabel Data Bahan Baku -->
            <div class="table-responsive">
                <table class="table table-striped" id="bahanBakuTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Bahan Baku</th>
                            <th>Satuan</th>
                            <th>Stok Minimum</th>
                            <th>Jumlah Stok</th>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Data dan Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
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
