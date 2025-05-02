@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Laporan Stok Bahan Baku</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-3 font-weight-bold">Laporan Stok Bahan Baku</h3>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <!-- Search Input -->
                <div class="input-group mb-2" style="max-width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>

                <!-- Right-aligned elements -->
                <div class="d-flex flex-column align-items-end">
                    <!-- Export Button -->
                    <button class="btn btn-danger mb-2">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>
                    
                    <!-- Show Entries - Now below Export -->
                    <div class="form-inline">
                        <label class="mr-2">Show</label>
                        <select class="form-control form-control-sm">
                            <option>5</option>
                            <option>10</option>
                            <option>20</option>
                            <option>50</option>
                        </select>
                        <span class="ml-2">entries</span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Bulan</th>
                            <th>Nama Bahan Baku</th>
                            <th>Satuan</th>
                            <th>Stok Awal</th>
                            <th>Total Stok Masuk</th>
                            <th>Total Stok Keluar</th>
                            <th>Sisa Stok</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Januari 2025</td>
                            <td>Kayu Jati</td>
                            <td>m³</td>
                            <td>30</td>
                            <td>50</td>
                            <td>35</td>
                            <td>45</td>
                            <td>Aman</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Januari 2025</td>
                            <td>Kayu Mahoni</td>
                            <td>m³</td>
                            <td>5</td>
                            <td>40</td>
                            <td>35</td>
                            <td>10</td>
                            <td>Perlu restock</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Januari 2025</td>
                            <td>Kayu Keruing</td>
                            <td>m³</td>
                            <td>28</td>
                            <td>50</td>
                            <td>10</td>
                            <td>68</td>
                            <td>Aman</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Februari 2025</td>
                            <td>Kayu Merbau</td>
                            <td>m³</td>
                            <td>10</td>
                            <td>20</td>
                            <td>24</td>
                            <td>6</td>
                            <td>Perlu restock</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Februari 2025</td>
                            <td>Kayu Mahogani</td>
                            <td>m³</td>
                            <td>45</td>
                            <td>25</td>
                            <td>8</td>
                            <td>62</td>
                            <td>Aman</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing 1 to 5 of 5 entries
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search button functionality
            document.getElementById('searchButton').addEventListener('click', function() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection