@extends('layouts.pemilikmebel')

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
                    <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal({{ $notif->id }})">Hapus Notifikasi</button>
                </div>
            </div>
        </div>
    @empty
        <p>Tidak ada notifikasi.</p>
    @endforelse

    {{-- <div class="mt-4 d-flex justify-content-start">
        <a href="{{ route('suratpemesanan.pemilikmebel') }}" class="btn btn-outline-primary">
            Buat Surat Pemesanan
        </a>
    </div>     --}}
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus notifikasi ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- AJAX & Modal JS --}}
<script>
    function showDeleteModal(id) {
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        document.getElementById('deleteForm').action = `/notifikasi/${id}/softdelete`;
        modal.show();
    }

    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));

        modal.hide();

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _method: 'PUT' })
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message || 'Notifikasi berhasil dihapus.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => location.reload());
        })
        .catch(error => {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menghapus notifikasi.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => location.reload());
        });
    });
</script>
@endsection
