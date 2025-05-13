@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Laporan Stok Bahan Baku</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-3 font-weight-bold">Laporan Stok Bahan Baku</h3>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <!-- Filter Button -->
                <div>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filterModal">
                        <i class="mdi mdi-filter mr-1"></i> Filter
                    </button>
                </div>

                <!-- Export Button -->
                <div>
                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="15%">Periode</th>
                            <th>Bahan Baku</th>
                            <th width="10%">Satuan</th>
                            <th width="10%">Stok Awal</th>
                            <th width="12%">Stok Masuk</th>
                            <th width="12%">Stok Keluar</th>
                            <th width="10%">Sisa Stok</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $namaBulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp
                        @foreach ($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $namaBulan[$laporan->bulan] }} {{ $laporan->tahun }}</td>
                                <td>{{ $laporan->bahanBaku->nama_bahan_baku }}</td>
                                <td class="text-center">{{ $laporan->satuan }}</td>
                                <td class="text-right">{{ $laporan->stok_awal }}</td>
                                <td class="text-right">{{ $laporan->total_stok_masuk }}</td>
                                <td class="text-right">{{ $laporan->total_stok_keluar }}</td>
                                <td class="text-right font-weight-bold">{{ $laporan->sisa_stok }}</td>
                                <td>
                                    <span class="badge badge-{{ $laporan->sisa_stok <= 10 ? 'danger' : 'success' }}">
                                        {{ $laporan->sisa_stok <= 10 ? 'Kritis' : 'Aman' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination and Entries Info -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $laporans->count() }} entri
                </div>
                {{ $laporans->links() }}
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="form-group">
                            <label for="modalMonthFilter">Bulan</label>
                            <select class="form-control" id="modalMonthFilter">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modalYearFilter">Tahun</label>
                            <input type="number" class="form-control" id="modalYearFilter" value="{{ now()->year }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="filterLaporan()">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function filterLaporan() {
            const bulan = document.getElementById('modalMonthFilter').value;
            const tahun = document.getElementById('modalYearFilter').value;

            window.location.href = `/laporan-bahan-baku?bulan=${bulan}&tahun=${tahun}`;
        }
    </script>
@endsection
