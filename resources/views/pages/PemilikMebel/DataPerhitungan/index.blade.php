@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Perhitungan</a></li>
@endsection

@section('content')
    <!-- Card untuk Data Perhitungan -->
    <div class="card">
        <div class="card-body">
            <!-- Judul Halaman -->
            <h3 class="mb-4 font-weight-bold">Data Perhitungan Metode SMART</h3>

            @if($jumlahPenilaian > 0)
                <!-- Bagian 1: Penilaian Kriteria -->
                <div class="mb-5">
                    <h4 class="mb-3 font-weight-bold">Penilaian Kriteria</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Supplier</th>
                                    @foreach ($kriterias as $kriteria)
                                        <th>{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $supplier->nama_supplier }}</td>
                                        @foreach ($kriterias as $kriteria)
                                            <td>
                                                @if (isset($penilaianData[$supplier->id][$kriteria->id]))
                                                    {{ $penilaianData[$supplier->id][$kriteria->id] }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bagian 2: Perhitungan Utility -->
                <div class="mb-5">
                    <h4 class="mb-3 font-weight-bold">Hasil Perhitungan Utility</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Supplier</th>
                                    @foreach ($kriterias as $kriteria)
                                        <th>{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $supplier->nama_supplier }}</td>
                                        @foreach ($kriterias as $kriteria)
                                            <td>
                                                @if (isset($utilityData[$supplier->id][$kriteria->id]))
                                                    {{ $utilityData[$supplier->id][$kriteria->id] }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bagian 3: Perhitungan Skor Akhir -->
                <div>
                    <h4 class="mb-3 font-weight-bold">Hasil Perhitungan Skor Akhir</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Supplier</th>
                                    <th>Skor Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row['nama_supplier'] }}</td>
                                        <td>{{ $row['skor_akhir'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            {{-- @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i> Belum ada data penilaian untuk ditampilkan. Silahkan lakukan penilaian terlebih dahulu.
                </div> --}}
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jumlahKriteria = {{ $jumlahKriteria ?? 0 }};
            const jumlahPenilaian = {{ $jumlahPenilaian ?? 0 }};
    
            if (jumlahKriteria === 0) {
            Swal.fire({
                title: 'Data Belum Lengkap',
                text: 'Tidak dapat menampilkan data perhitungan karena belum adanya kriteria.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('datakriteria.pemilikmebel') }}";
            });
            } else if (jumlahPenilaian === 0) {
                Swal.fire({
                    title: 'Data Belum Lengkap',
                    text: 'Belum ada data penilaian untuk ditampilkan. Silahkan lakukan penilaian terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }). then(() => {
                    window.location.href = "{{ route('datasupplier.pemilikmebel') }}";
                });
            }
        });
    </script>
@endsection