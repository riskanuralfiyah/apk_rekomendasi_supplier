@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Buat Surat Pemesanan</li>
@endsection

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4 text-primary">Form Pemesanan Bahan Baku</h4>

            <form method="POST" action="{{ route('pdf.suratpemesanan.pemilikmebel') }}" class="needs-validation" novalidate>
                @csrf

                {{-- Bahan Baku Hampir Habis --}}
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Bahan Baku Hampir Habis</h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-wrapper-scroll">
                            <table class="table table-bordered table-sm align-middle text-sm">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Ukuran</th>
                                        <th>Stok</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bahanHampirHabis as $index => $bahan)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $bahan->nama_bahan_baku }}</td>
                                            <td>{{ $bahan->ukuran }}</td>
                                            <td class="text-center">{{ $bahan->jumlah_stok }}</td>
                                            <td>
                                                <select name="satuan[{{ $bahan->id }}]" class="form-control form-control-sm">
                                                    <option value="" disabled selected>Satuan</option>
                                                    <option value="kubik" {{ old('satuan.'.$bahan->id) == 'kubik' ? 'selected' : '' }}>Kubik</option>
                                                    <option value="batang" {{ old('satuan.'.$bahan->id) == 'batang' ? 'selected' : '' }}>Batang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="jumlah[{{ $bahan->id }}]" class="form-control form-control-sm"
                                                       placeholder="Jumlah" min="1" value="{{ old('jumlah.'.$bahan->id) }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="bahan_baku[]" value="{{ $bahan->id }}" class="bahan-checkbox" checked>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada bahan baku yang hampir habis.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Bahan Tambahan --}}
                <div class="card border-secondary mb-4">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center"
                         data-toggle="collapse" data-target="#bahanTambahanCollapse" style="cursor: pointer;">
                        <h5 class="mb-0">Bahan Baku Tambahan (Opsional)</h5>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="collapse" id="bahanTambahanCollapse">
                        <div class="card-body p-2">
                            <div class="table-wrapper-scroll">
                                <table class="table table-bordered table-sm align-middle text-sm">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Ukuran</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Pilih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bahanTambahan as $index => $bahan)
                                            <tr class="table-secondary">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $bahan->nama_bahan_baku }}</td>
                                                <td>{{ $bahan->ukuran }}</td>
                                                <td class="text-center">{{ $bahan->jumlah_stok }}</td>
                                                <td>
                                                    <select name="satuan[{{ $bahan->id }}]" class="form-control form-control-sm">
                                                        <option value="" disabled selected>Satuan</option>
                                                        <option value="kubik" {{ old('satuan.'.$bahan->id) == 'kubik' ? 'selected' : '' }}>Kubik</option>
                                                        <option value="batang" {{ old('satuan.'.$bahan->id) == 'batang' ? 'selected' : '' }}>Batang</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="jumlah[{{ $bahan->id }}]" class="form-control form-control-sm"
                                                           placeholder="Jumlah" min="1" value="{{ old('jumlah.'.$bahan->id) }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="bahan_baku[]" value="{{ $bahan->id }}" class="bahan-checkbox">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Tidak ada bahan tambahan tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Supplier --}}
                <div class="form-group">
                    <label for="supplier" class="font-weight-bold text-dark">
                        <i class="fas fa-truck mr-2"></i>Pilih Supplier
                    </label>
                    <select name="supplier" id="supplier" class="form-control selectpicker" data-live-search="true" required>
                        <option value="" disabled selected>-- Pilih Supplier --</option>

                        {{-- Supplier Rekomendasi --}}
                        <optgroup label="🟢 Supplier Rekomendasi">
                            @foreach($supplierRekomendasi->sortBy('peringkat') as $hasil)
                                <option value="{{ $hasil->supplier->id }}"
                                    @if($hasil->peringkat == 1) selected @endif
                                        data-tokens="{{ $hasil->supplier->nama_supplier }} {{ $hasil->supplier->alamat }}">
                                    @if($hasil->peringkat == 1)
                                        🥇 #1 -
                                    @elseif($hasil->peringkat == 2)
                                        🥈 #2 -
                                    @elseif($hasil->peringkat == 3)
                                        🥉 #3 -
                                    @endif
                                    {{ $hasil->supplier->nama_supplier }} ({{ $hasil->supplier->alamat }})
                                </option>
                            @endforeach
                        </optgroup>

                        {{-- Supplier Alternatif --}}
                        <optgroup label="🔵 Supplier Alternatif">
                            @foreach($supplierAlternatif->sortBy('peringkat') as $hasil)
                                <option value="{{ $hasil->supplier->id }}"
                                        data-tokens="{{ $hasil->supplier->nama_supplier }} {{ $hasil->supplier->alamat }}">
                                    #{{ $hasil->peringkat }} - {{ $hasil->supplier->nama_supplier }} ({{ $hasil->supplier->alamat }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>

                    <small class="form-text text-muted mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Supplier dengan peringkat 1-3 adalah supplier yang direkomendasikan.
                    </small>
                </div>


                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-file-pdf mr-2"></i> Buat Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .table-wrapper-scroll {
        max-height: 360px;
        overflow-y: auto;
    }

    .table th,
    .table td {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    select.form-control-sm,
    input.form-control-sm {
        font-size: 0.85rem;
        padding: 0.25rem 0.4rem;
        height: auto;
    }

    .badge-gold { background-color: #FFD700; color: #000; }
    .badge-silver { background-color: #C0C0C0; color: #000; }
    .badge-bronze { background-color: #CD7F32; color: #000; }

    .supplier-item:hover {
        background-color: rgba(0,0,0,0.03);
        transition: background-color 0.2s ease;
    }

    .card-header[data-toggle="collapse"] {
        transition: all 0.3s ease;
    }

    .card-header[data-toggle="collapse"] .toggle-icon {
        transition: transform 0.3s ease;
    }

    .card-header[data-toggle="collapse"].collapsed .toggle-icon {
        transform: rotate(0deg);
    }

    .card-header[data-toggle="collapse"]:not(.collapsed) .toggle-icon {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form.needs-validation');

        form.addEventListener('submit', function(event) {
            let isValid = true;
            const selectedMaterials = document.querySelectorAll('.bahan-checkbox:checked');

            if (selectedMaterials.length === 0) {
                isValid = false;
                alert('Pilih minimal satu bahan baku');
            }

            selectedMaterials.forEach(checkbox => {
                const bahanId = checkbox.value;
                const quantityInput = document.querySelector(`input[name="jumlah[${bahanId}]"]`);
                const unitSelect = document.querySelector(`select[name="satuan[${bahanId}]"]`);

                if (!quantityInput.value || !unitSelect.value) {
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });
    });
</script>
@endsection
