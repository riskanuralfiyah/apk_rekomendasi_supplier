@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Perhitungan</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4 font-weight-bold">Data Perhitungan Metode SMART</h3>

            @if($jumlahPenilaian > 0)
                <!-- Penilaian Kriteria -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#penilaianKriteria" aria-expanded="false">
                        Penilaian Kriteria
                    </button>
                    <div class="collapse mt-2" id="penilaianKriteria">
                        <div class="table-responsive mt-3">
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
                                                    {{ $penilaianData[$supplier->id][$kriteria->id] ?? 0 }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Utility -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#utility" aria-expanded="false">
                        Hasil Perhitungan Utility
                    </button>
                    <div class="collapse mt-2" id="utility">
                        <div class="table-responsive mt-3">
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
                                                    {{ $utilityData[$supplier->id][$kriteria->id] ?? 0 }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Skor Akhir -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#skorAkhir" aria-expanded="false">
                        Hasil Perhitungan Skor Akhir
                    </button>
                    <div class="collapse mt-2" id="skorAkhir">
                        <div class="table-responsive mt-3">
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
                </div>
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
                    text: 'Tidak dapat menampilkan data perhitungan karena belum adanya kriteria. Harap lengkapi data kriteria terlebih dahulu',
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
                }).then(() => {
                    window.location.href = "{{ route('datasupplier.pemilikmebel') }}";
                });
            }
        });
    </script>
@endsection
