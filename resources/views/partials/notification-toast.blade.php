@if(isset($notifications) && $notifications->count() > 0)
<div aria-live="polite" aria-atomic="true" style="position: fixed; top: 1rem; right: 1rem; z-index: 1080; min-width: 320px;">
    @foreach($notifications as $notif)
    <div class="toast fade show shadow-sm" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" style="border: 1px solid #e9ecef; border-radius: 8px;">
        <div class="toast-header" style="background-color: white; border-bottom: 1px solid #f1f1f1; border-radius: 8px 8px 0 0; padding: 12px 16px;">
            <div style="background-color: #F0F0FF; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#4B49AC" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                </svg>
            </div>
            <strong class="mr-auto" style="color: #495057; font-size: 14px;">Stok Rendah</strong>
            <button type="button" class="ml-2 close" data-dismiss="toast" aria-label="Close" style="color: #6c757d; font-size: 18px;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body" style="background-color: white; padding: 16px; border-radius: 0 0 8px 8px;">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="mb-2" style="color: #495057; font-weight: 500; font-size: 14px;">
                        {{ $notif['message'] }}
                    </p>
                    {{-- <div class="d-flex justify-content-between" style="font-size: 13px;">
                        <span style="color: #6c757d;">Sisa Stok: <span style="color: #495057; font-weight: 500;">{{ $notif->jumlah_stok }}</span></span>
                        <span style="color: #6c757d;">Min: <span style="color: #495057; font-weight: 500;">{{ $notif->stok_minimum }}</span></span>
                    </div> --}}
                </div>
                <div class="ml-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#4B49AC" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 2.5.5h7l.654 3.003L8.186 1.113zM14.5 4.5L8.5.5v7l5.5-2.5V4.5zm-1 3.5l-5.5 2.5v-7l5.5 2.5v2.5z"/>
                        <path d="M8.5 12.5V15l5.5-2.5v-2.5L8.5 12.5zm-7-9.5L7.5.5v7L1.5 4.5v-2.5z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="progress" style="height: 3px; border-radius: 0 0 8px 8px;">
            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #4B49AC;"></div>
        </div>
    </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.toast').toast({
            animation: true,
            autohide: true,
            delay: 5000
        }).toast('show');

        $('.toast').each(function(index, el) {
            const toast = $(el);
            const progressBar = toast.find('.progress-bar');
            let progress = 100;
            
            // animasi progress bar
            const interval = setInterval(() => {
                progress -= 2; // 100 / (5000ms / 100ms)
                if (progress <= 0) {
                    progress = 0;
                    clearInterval(interval);
                }
                progressBar.css('width', progress + '%');
            }, 100);

            // tombol close
            toast.find('.close').on('click', function () {
                clearInterval(interval);
                toast.toast('hide');
            });
        });

        // ⏱ fetch dipanggil setelah 6 detik
        setTimeout(() => {
            fetch('{{ route("notifikasi.marktoasted") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        });
        }, 6000); // setelah toast tampil
    });
</script>
@endif
