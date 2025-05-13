@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Stok Keluar</a></li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-3 font-weight-bold">Data Stok Keluar</h3>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>

                <a href="{{ route('create.stokkeluar.karyawan') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped" id="stokKeluarTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama Bahan Baku</th>
                            <th>Jumlah Stok Keluar</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokKeluars as $stokKeluar)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($stokKeluar->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $stokKeluar->bahanBaku->nama_bahan_baku }}</td>
                            <td>{{ $stokKeluar->jumlah_stok_keluar }}</td>
                            <td>{{ $stokKeluar->keterangan }}</td>
                            <td>
                                <a href="{{ route('edit.stokkeluar.karyawan', $stokKeluar->id) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil text-white"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $stokKeluar->id }})">
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
                    Showing {{ $stokKeluars->firstItem() }} to {{ $stokKeluars->lastItem() }} of {{ $stokKeluars->total() }} entries
                </div>

                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        @if ($stokKeluars->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $stokKeluars->previousPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        @foreach ($stokKeluars->getUrlRange(1, $stokKeluars->lastPage()) as $page => $url)
                            @if ($page == $stokKeluars->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($stokKeluars->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $stokKeluars->nextPageUrl() }}&per_page={{ request('per_page', 10) }}&search={{ request('search') }}" rel="next">&raquo;</a>
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
        function showDeleteModal(id) {
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('karyawan/stok-keluar') }}/" + id;
            modal.show();
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
    @endif
@endsection
