@extends('layouts.pemilikmebel') {{-- atau layout kamu yang sesuai --}}

@section('content')
<div class="container">
    <h4>Daftar Notifikasi</h4>
    @forelse ($notifikasis as $notif)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-subtitle mb-2 text-muted">Stok hampir habis</h6>
                    <p class="card-text">{{ $notif->message }}</p>
                </div>
                <div>
                    {{-- <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form> --}}
                </div>
            </div>
        </div>
    @empty
        <p>Tidak ada notifikasi.</p>
    @endforelse

    {{-- tombol Lihat Daftar Stok Habis di bawah sekali --}}
    <div class="mt-4 d-flex justify-content-start">
        <a href="{{ route('suratpemesanan.pemilikmebel') }}" class="btn btn-outline-primary">
            Buat Surat Pemesanan
        </a>
    </div>    
</div>
@endsection
