@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Laporan Stok Bahan Baku</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="mb-2 font-weight-bold">Laporan Bahan Baku</h3>

        <!-- Search, Filter, and Export in one row -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <!-- Bagian kiri: Search + Filter + Reset -->
            <form method="GET" action="{{ route('laporanbahanbaku.pemilikmebel') }}" id="searchForm" class="d-flex flex-column" style="max-width: 300px;">
                <div class="input-group mb-3">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search" value="{{ request('search') }}">
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    <input type="hidden" name="id_bahan_baku" value="{{ request('id_bahan_baku') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="searchButton">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>
            
                <!-- Tambahkan margin-top untuk jarak filter dan reset -->
                <div class="d-flex" style="gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filterModal">
                        <i class="mdi mdi-filter mr-1"></i> Filter
                    </button>
            
                    <a href="{{ route('laporanbahanbaku.pemilikmebel') }}" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-refresh"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Bagian kanan: Export PDF -->
            <div class="d-flex flex-wrap gap-2 mt-2">
                <a href="{{ route('pdf.laporanbahanbaku.pemilikmebel', [
                    'bulan' => request('bulan'),
                    'tahun' => request('tahun'),
                    'id_bahan_baku' => request('id_bahan_baku'),
                    'search' => request('search'),
                ]) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            
                <a href="{{ route('excel.laporanbahanbaku.pemilikmebel', [
                    'bulan' => request('bulan'),
                    'tahun' => request('tahun'),
                    'id_bahan_baku' => request('id_bahan_baku'),
                    'search' => request('search'),
                ]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>

        <!-- Show Entries -->
        <div class="d-flex justify-content-end mb-3">
            <div class="mr-2">Show</div>
            <select name="per_page" class="form-control form-control-sm" style="width: 70px;" 
                    onchange="window.location.href = '{{ request()->url() }}?per_page=' + this.value + '&search={{ request('search') }}&bulan={{ request('bulan') }}&tahun={{ request('tahun') }}'">
                @foreach([5, 10, 20, 50] as $perPage)
                    <option value="{{ $perPage }}" {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                        {{ $perPage }}
                    </option>
                @endforeach
            </select>
            <div class="ml-2">entries</div>
        </div>

        @if($laporans->count() > 0)
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="15%">Periode</th>
                        <th>Bahan Baku</th>
                        <th width="10%">Ukuran (cm)</th>
                        <th width="10%">Stok Awal</th>
                        <th width="12%">Stok Masuk</th>
                        <th width="12%">Stok Keluar</th>
                        <th width="10%">Sisa Stok</th>
                        <th width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $index => $laporan)
                    <tr>
                        <td>{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}</td>
                        <td>{{ $namaBulan[$laporan['bulan']] }} {{ $laporan['tahun'] }}</td>
                        <td>{{ $laporan['nama_bahan_baku'] }}</td>
                        <td class="text-center">{{ $laporan['ukuran'] }}</td>
                        <td class="text-right">{{ number_format($laporan['stok_awal']) }}</td>
                        <td class="text-right">{{ number_format($laporan['stok_masuk']) }}</td>
                        <td class="text-right">{{ number_format($laporan['stok_keluar']) }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($laporan['sisa_stok']) }}</td>
                        <td>
                            <span class="badge badge-{{ $laporan['sisa_stok'] <= 10 ? 'danger' : 'success' }}">
                                {{ $laporan['sisa_stok'] <= 10 ? 'Perlu Restock' : 'Aman' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                        <td class="text-right">{{ number_format($laporans->sum('stok_awal')) }}</td>
                        <td class="text-right">{{ number_format($laporans->sum('stok_masuk')) }}</td>
                        <td class="text-right">{{ number_format($laporans->sum('stok_keluar')) }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($laporans->sum('sisa_stok')) }}</td>
                    </tr>
                </tfoot> --}}
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Tidak ada data laporan stok bahan baku untuk periode {{ $namaBulan[$currentBulan] ?? '' }} {{ $currentTahun }}.
        </div>
    @endif

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $laporans->firstItem() }} to {{ $laporans->lastItem() }} of {{ $laporans->total() }} entries
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page --}}
                    @if ($laporans->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $laporans->previousPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}&bulan={{ request('bulan') }}&tahun={{ request('tahun') }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    {{-- Page Links --}}
                    @foreach ($laporans->getUrlRange(1, $laporans->lastPage()) as $page => $url)
                        @if ($page == $laporans->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}&bulan={{ request('bulan') }}&tahun={{ request('tahun') }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($laporans->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $laporans->nextPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}&bulan={{ request('bulan') }}&tahun={{ request('tahun') }}" rel="next">&raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="GET" action="{{ route('laporanbahanbaku.pemilikmebel') }}" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                                {{-- Filter berdasarkan bahan baku --}}
                                <div class="form-group">
                                    <label for="modalBahanBakuFilter">Bahan Baku</label>
                                    <select class="form-control" id="modalBahanBakuFilter" name="id_bahan_baku">
                                        <option value="">Semua Bahan Baku</option>
                                        @foreach($daftarBahanBaku as $bahan)
                                            <option value="{{ $bahan->id }}" {{ request('id_bahan_baku') == $bahan->id ? 'selected' : '' }}>
                                                {{ $bahan->nama_bahan_baku }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                {{-- Bulan (tidak ada opsi semua bulan) --}}
                <div class="form-group">
                    <label for="modalMonthFilter">Bulan</label>
                    <select class="form-control" id="modalMonthFilter" name="bulan" required>
                        @php
                            $bulanSekarang = request()->filled('bulan') ? request('bulan') : now()->month;
                        @endphp

                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ (int)$bulanSekarang == (int)$num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun --}}
                <div class="form-group">
                    <label for="modalYearFilter">Tahun</label>
                    <input type="number" class="form-control" id="modalYearFilter" name="tahun" value="{{ request('tahun', now()->year) }}">
                </div>

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
@endsection