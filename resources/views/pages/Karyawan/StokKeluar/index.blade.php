@extends('layouts.karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Stok Keluar</a></li>
@endsection

@section('content')
    <!-- Card untuk Tabel dan Fitur Search -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Data Bahan Baku di dalam Card -->
            <h3 class="mb-3 font-weight-bold">Data Stok Keluar</h3>

            <!-- Header dengan Search dan Tombol Tambah -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Input Search -->
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Tambah -->
                <a href="{{ route('create.stokkeluar.karyawan') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Tambah
                </a>
            </div>

            <!-- Show Entries di bawah Tombol Tambah -->
            <div class="d-flex justify-content-end mb-3">
                <div>
                    Show
                    <select id="showEntries" class="form-control form-control-sm d-inline-block" style="width: auto;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
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
                            <th>Tanggal</th>
                            <th>Nama Bahan Baku</th>
                            <th>Jumlah Stok Keluar</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Total Data dan Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <!-- Total Data -->
                <div>
                    Showing <span id="showingStart">1</span> to <span id="showingEnd">5</span> of <span id="totalData">10</span> entries
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0" id="pagination">
                        <!-- Tombol pagination akan diisi oleh JavaScript -->
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
                <!-- Tombol Silang -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
            </div>
        </div>
    </div>
</div>

    <!-- Script untuk Fitur Search, Show Entries, dan Pagination -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const data = [
                { no: 1, tanggal: "05-12-2024", namaBahanBaku: "Kayu Jati", jumlahStokKeluar: "10", keterangan: "Pembuatan Kusen-kusen" },
                { no: 2, tanggal: "28-02-2025", namaBahanBaku: "Kayu Mahoni", jumlahStokKeluar: "8", keterangan: "Pembuatan Pintu" },
                { no: 3, tanggal: "20-03-2025", namaBahanBaku: "Kayu Keruing", jumlahStokKeluar: "6", keterangan: "Pembuatan Jendela" },
            ];

            let itemsPerPage = 5; // Jumlah data per halaman (default: 5)
            let currentPage = 1;

            const tableBody = document.getElementById('tableBody');
            const pagination = document.getElementById('pagination');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const showEntries = document.getElementById('showEntries');
            const totalData = document.getElementById('totalData');
            const showingStart = document.getElementById('showingStart');
            const showingEnd = document.getElementById('showingEnd');
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');

            // Fungsi untuk menampilkan data
            function renderTable(page, searchQuery = '') {
            const filteredData = data.filter(item =>
                item.tanggal.includes(searchQuery) ||
                item.namaBahanBaku.toLowerCase().includes(searchQuery.toLowerCase()) ||
                item.jumlahStokKeluar.toString().includes(searchQuery) ||
                item.keterangan.toLowerCase().includes(searchQuery.toLowerCase())
            );
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const paginatedData = filteredData.slice(start, end);

                tableBody.innerHTML = paginatedData.map(item => `
                    <tr>
                        <td>${item.no}</td>
                        <td>${item.tanggal}</td>
                        <td>${item.namaBahanBaku}</td>
                        <td>${item.jumlahStokKeluar}</td>
                        <td>${item.keterangan}</td>
                        <td>
                            <button class="btn btn-primary btn-sm">
                                <a href="{{ route('edit.stokkeluar.karyawan') }}" class="mdi mdi-pencil text-white"></a>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(${item.no})">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');

                // Tampilkan total data
                totalData.textContent = filteredData.length;

                // Tampilkan informasi "Showing X to Y of Z entries"
                showingStart.textContent = start + 1;
                showingEnd.textContent = Math.min(end, filteredData.length);

                renderPagination(filteredData.length);
            }

            // Fungsi untuk menampilkan modal hapus
            window.showDeleteModal = function (id) {
                selectedId = id; // Simpan ID yang dipilih
                confirmDeleteModal.show(); // Tampilkan modal
            };

            // Fungsi untuk menampilkan pagination
            function renderPagination(totalItems) {
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                let paginationHTML = '';

                // Tombol Previous
                paginationHTML += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                    </li>
                `;

                // Tombol Halaman
                for (let i = 1; i <= totalPages; i++) {
                    paginationHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                // Tombol Next
                paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                    </li>
                `;

                pagination.innerHTML = paginationHTML;

                // Tambahkan event listener untuk tombol pagination
                pagination.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        currentPage = parseInt(this.getAttribute('data-page'));
                        renderTable(currentPage, searchInput.value);
                    });
                });
            }

            // Event listener untuk tombol search
            searchButton.addEventListener('click', function () {
                currentPage = 1;
                renderTable(currentPage, searchInput.value);
            });

            // Event listener untuk show entries
            showEntries.addEventListener('change', function () {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable(currentPage, searchInput.value);
            });

            // Render tabel dan pagination saat pertama kali dimuat
            renderTable(currentPage);
        });
    </script>
@endsection