@extends('layouts.pemilikmebel')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Buat Surat Pemesanan</li>
@endsection

@section('content')
    {{-- Notifikasi Error --}}
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="font-weight-bold text-primary">Form Pemesanan Bahan Baku</h3>
                <div class="badge badge-primary p-2">Pemilik Mebel</div>
            </div>

            <form method="POST" action="{{ route('pdf.suratpemesanan.pemilikmebel') }}" class="needs-validation" novalidate>
                @csrf

                <!-- Bahan Hampir Habis Section -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Daftar Bahan Baku yang Hampir Habis</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3"><em>Centang bahan baku yang ingin dipilih.</em></p>
                        @forelse($bahanHampirHabis as $bahan)
                            <div class="form-group row align-items-center mb-3">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="custom-control custom-checkbox mr-3">
                                        <input type="checkbox" class="custom-control-input bahan-checkbox" 
                                               name="bahan_baku[]" value="{{ $bahan->id }}" 
                                               id="hampirhabis{{ $bahan->id }}" checked>
                                        <label class="custom-control-label font-weight-bold" for="hampirhabis{{ $bahan->id }}">
                                            {{ $bahan->nama_bahan_baku }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge badge-info">Stok: {{ $bahan->jumlah_stok }}</span>
                                </div>
                                <div class="col-md-3 quantity-input-col">
                                    <input type="number" class="form-control quantity-input" 
                                           name="jumlah[{{ $bahan->id }}]" 
                                           placeholder="Jumlah" min="1"
                                           value="{{ old('jumlah.'.$bahan->id) }}">
                                    <div class="invalid-feedback">Harap masukkan jumlah</div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info mb-0">
                                Tidak ada bahan baku yang hampir habis.
                            </div>
                        @endforelse
                    </div>
                </div>

            <!-- Bahan Tambahan Section -->
            <div class="card mb-4 border-secondary">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" 
                    data-toggle="collapse" data-target="#bahanTambahanCollapse" 
                    style="cursor: pointer; transition: all 0.3s ease;">
                    <h5 class="mb-0">Bahan Baku Tambahan (Opsional)</h5>
                    <span class="toggle-icon">
                        <i class="fas fa-plus-circle fa-lg"></i>
                    </span>
                </div>
                <div class="card-body collapse" id="bahanTambahanCollapse">
                    <p class="text-muted mb-3"><em>Centang bahan baku yang ingin dipilih.</em></p>
                    @foreach($bahanTambahan as $bahan)
                        <div class="form-group row align-items-center mb-3">
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input bahan-checkbox" 
                                        name="bahan_baku[]" value="{{ $bahan->id }}" 
                                        id="tambahan{{ $bahan->id }}">
                                    <label class="custom-control-label" for="tambahan{{ $bahan->id }}">
                                        {{ $bahan->nama_bahan_baku }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <span class="badge badge-info">Stok: {{ $bahan->jumlah_stok }}</span>
                            </div>
                            <div class="col-md-3 quantity-input-col" style="margin-left: -28px;">
                                <div class="d-flex align-items-center">
                                    <select class="custom-select me-2" name="satuan[{{ $bahan->id }}]" style="width: 120px;">
                                        <option value="" disabled selected>Satuan</option>
                                        <option value="kubik" {{ old('satuan.'.$bahan->id) == 'kubik' ? 'selected' : '' }}>Kubik</option>
                                        <option value="batang" {{ old('satuan.'.$bahan->id) == 'batang' ? 'selected' : '' }}>Batang</option>
                                    </select>
                                    <input type="number" class="form-control quantity-input"
                                        name="jumlah[{{ $bahan->id }}]"
                                        placeholder="Jumlah" min="1"
                                        value="{{ old('jumlah.'.$bahan->id) }}"
                                        style="width: 140px;">
                                </div>
                                <div class="invalid-feedback d-block">Harap pilih satuan dan masukkan jumlahnya.</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
                

                <!-- Supplier Selection Section -->
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Pilih Supplier</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-success mb-3">
                                <i class="fas fa-star mr-2"></i>Rekomendasi Utama
                            </h6>
                            @foreach($supplierRekomendasi as $key => $hasil)
                                <div class="form-group row align-items-center mb-2">
                                    <div class="col-md-1">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="supplier" 
                                                   value="{{ $hasil->supplier->id }}" id="rekom{{ $hasil->supplier->id }}" 
                                                   {{ $key == 0 ? 'checked' : '' }} required>
                                            <label class="custom-control-label" for="rekom{{ $hasil->supplier->id }}"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="rekom{{ $hasil->supplier->id }}" class="mb-0">
                                            <span class="font-weight-bold">{{ $hasil->supplier->nama_supplier }}</span>
                                            <p class="text-muted small mb-0">{{ $hasil->supplier->alamat }}</p>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="badge badge-success">Peringkat: {{ $hasil->peringkat }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div>
                            <h6 class="font-weight-bold text-info mb-3">
                                <i class="fas fa-list-alt mr-2"></i>Supplier Alternatif
                            </h6>
                            @foreach($supplierAlternatif as $hasil)
                                <div class="form-group row align-items-center mb-2">
                                    <div class="col-md-1">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="supplier" 
                                                   value="{{ $hasil->supplier->id }}" id="alternatif{{ $hasil->supplier->id }}">
                                            <label class="custom-control-label" for="alternatif{{ $hasil->supplier->id }}"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="alternatif{{ $hasil->supplier->id }}" class="mb-0">
                                            <span class="font-weight-bold">{{ $hasil->supplier->nama_supplier }}</span>
                                            <p class="text-muted small mb-0">{{ $hasil->supplier->alamat }}</p>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="badge badge-info">Peringkat: {{ $hasil->peringkat }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-file-pdf mr-2"></i>Buat Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // toggle ikon +/-
        const toggleHeader = document.querySelector('[data-target="#bahanTambahanCollapse"]');
        const toggleIcon = toggleHeader.querySelector('.toggle-icon i');
        
        toggleHeader.addEventListener('click', function () {
            const isShown = document.getElementById('bahanTambahanCollapse').classList.contains('show');
            if (isShown) {
                toggleIcon.classList.remove('fa-minus-circle');
                toggleIcon.classList.add('fa-plus-circle');
            } else {
                toggleIcon.classList.remove('fa-plus-circle');
                toggleIcon.classList.add('fa-minus-circle');
            }
        });

        function handleCheckboxChanges() {
        document.querySelectorAll('.bahan-checkbox').forEach(checkbox => {
            const row = checkbox.closest('.form-group');
            const quantityInputCol = row.querySelector('.quantity-input-col');
            
            // atur tampilan awal sesuai checked atau tidak
            updateQuantityVisibility(checkbox, quantityInputCol);
            
            checkbox.addEventListener('change', function() {
                updateQuantityVisibility(this, quantityInputCol);
            });
        });
    }

    function updateQuantityVisibility(checkbox, quantityCol) {
        const input = quantityCol.querySelector('input');
        const select = quantityCol.querySelector('select');
        
        if (checkbox.checked) {
            quantityCol.style.display = 'block';
            input.required = true;
            select.required = true;
            input.removeAttribute('disabled');
            select.removeAttribute('disabled');
        } else {
            input.required = false;
            select.required = false;
            input.setAttribute('disabled', 'disabled');
            select.setAttribute('disabled', 'disabled');
            input.value = '';
            select.selectedIndex = 0; // Reset ke "Satuan"
        }
    }

    handleCheckboxChanges();

    // Validasi form
    (function() {
        'use strict';
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.forEach.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                // Validasi tambahan untuk satuan
                document.querySelectorAll('.bahan-checkbox:checked').forEach(checkbox => {
                    const row = checkbox.closest('.form-group');
                    const select = row.querySelector('select');
                    if (select && select.value === "") {
                        select.setCustomValidity('Harap pilih satuan');
                    } else {
                        select.setCustomValidity('');
                    }
                });

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
});
    </script>
    
@endsection