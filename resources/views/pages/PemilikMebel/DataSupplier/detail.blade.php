@extends('layouts.pemilikmebel')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard.pemilikmebel') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('datasupplier.pemilikmebel') }}">Data Supplier</a></li>
  <li class="breadcrumb-item active" aria-current="page">Detail Supplier</li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-lg">
      <!-- Header dengan gradient -->
      <div class="card-header py-3 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex justify-content-between align-items-center">
          <h4 class="mb-0">
            <i class="fas fa-store-alt me-2"></i>
            Profile Supplier
          </h4>
          @if($supplier->is_recommended)
          <span class="badge bg-white text-primary fs-6 shadow-sm">
            <i class="fas fa-check-circle me-1"></i> Rekomendasi
          </span>
          @endif
        </div>
      </div>
      
      <div class="card-body p-4">
        <div class="row">
          <!-- Foto Profil -->
          <div class="col-md-4 text-center mb-4 mb-md-0">
            <div class="position-relative d-inline-block">
              <img src="https://ui-avatars.com/api/?name={{ urlencode($supplier->nama_supplier) }}&background=random&color=fff&size=200" 
                   class="rounded-circle shadow-lg" width="180" height="180" alt="Supplier">
              <span class="position-absolute bottom-0 end-0 bg-success p-2 rounded-circle shadow">
                <i class="fas fa-check text-white"></i>
              </span>
            </div>
            <h5 class="mt-3 mb-0">{{ $supplier->nama_supplier }}</h5>
            <p class="text-muted">Supplier Kayu Jati</p>
          </div>
          
          <!-- Informasi Detail -->
          <div class="col-md-8 d-flex flex-column justify-content-center">
            <div class="info-card mb-4">
              <div class="icon-box bg-primary-light">
                <i class="fas fa-map-marker-alt text-primary"></i>
              </div>
              <div class="info-content">
                <h6 class="info-label">Alamat</h6>
                <p class="info-text text-start">{{ $supplier->alamat }}</p>
              </div>
            </div>
            
            <div class="info-card mb-4">
              <div class="icon-box bg-success-light">
                <i class="fas fa-phone-alt text-success"></i>
              </div>
              <div class="info-content">
                <h6 class="info-label">No. Telepon</h6>
                <p class="info-text text-start">{{ $supplier->no_telpon }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer dengan tombol aksi -->
      <div class="card-footer bg-light p-3">
        <div class="d-flex justify-content-between">
          <a href="{{ route('datasupplier.pemilikmebel') }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
          </a>
          <div>
            @php
              // Format nomor telepon ke +62
              $raw_phone = $supplier->no_telpon;
              $phone = preg_replace('/[^0-9]/', '', $raw_phone);
              if (substr($phone, 0, 1) === '0') {
                  $phone = '62' . substr($phone, 1);
              } elseif (substr($phone, 0, 2) !== '62') {
                  $phone = '62' . $phone;
              }
              
              $message = "Halo " . $supplier->nama_supplier . ",\n\nSaya ingin memesan bahan baku kayu untuk produksi mebel karena stok kami sudah hampir habis.\n\nBerikut detail pesanan yang kami butuhkan:\n- Jenis Kayu: \n- Jumlah: \n- Spesifikasi: \n- Waktu Pengiriman: \n\nMohon informasikan harga dan ketersediaannya. Terima kasih.";
              $whatsapp_url = "https://wa.me/{$phone}?text=" . urlencode($message);
            @endphp
            <a href="{{ $whatsapp_url }}" 
               class="btn btn-primary px-4" target="_blank">
              <i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .info-card {
    display: flex;
    align-items: flex-start;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
    width: 100%;
  }
  
  .info-card:hover {
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transform: translateY(-2px);
  }
  
  .icon-box {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
  }
  
  .info-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 5px;
    font-weight: 600;
  }
  
  .info-text {
    font-size: 1rem;
    margin-bottom: 0;
    color: #343a40;
    font-weight: 500;
  }
  
  .bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
  }
  
  .bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
  }
  
  .bg-info-light {
    background-color: rgba(13, 202, 240, 0.1);
  }
  
  .info-content {
    flex: 1;
  }
</style>
@endsection