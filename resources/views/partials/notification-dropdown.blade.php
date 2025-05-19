<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-0" aria-labelledby="notificationDropdown" style="min-width: 380px; max-width: 420px; border: 1px solid #e9ecef; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <div class="dropdown-header bg-light py-2 px-4" style="border-bottom: 1px solid #e9ecef;">
        <h6 class="mb-0 font-weight-semibold text-primary">Notifikasi Stok</h6>
    </div>
    
    @forelse($notifications as $index => $notif)
        <div class="preview-item py-3 px-4 d-flex align-items-center border-bottom notification-item" data-index="{{ $index }}" style="transition: all 0.3s ease;">
            <div class="mr-3">
                <div class="icon-circle bg-primary-light" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #e6f0ff;">
                    <i class="mdi mdi-alert-circle-outline text-primary" style="font-size: 1.25rem;"></i>
                </div>
            </div>
            <div class="preview-item-content" style="width: 100%;">
                <h6 class="preview-subject font-weight-normal mb-1" style="color: #2a3547;">Stok hampir habis</h6>
                <p class="font-weight-light small-text mb-2" style="color: #6c757d; font-size: 0.875rem;">
                    {{ $notif['message'] }}
                </p>
                <div class="d-flex justify-content-end mt-2">
                    <button class="btn btn-sm btn-outline-primary close-notification" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="preview-item py-4 px-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 120px;">
            <div class="icon-circle bg-light mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="mdi mdi-bell-outline text-muted" style="font-size: 1.5rem;"></i>
            </div>
            <p class="font-weight-light small-text text-muted mb-0">Tidak ada notifikasi baru</p>
        </div>
    @endforelse

    <div class="dropdown-divider"></div>
    
    <div class="dropdown-footer text-center py-2">
        <a href="{{ route('notifikasi.index') }}" class="text-primary font-weight-medium" style="font-size: 0.875rem;">
            Lihat semua notifikasi <i class="mdi mdi-chevron-right"></i>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle close button click with smooth animation
    document.querySelectorAll('.close-notification').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationItem = this.closest('.notification-item');
            
            // Add fade out animation
            notificationItem.style.opacity = '0';
            notificationItem.style.transform = 'translateX(20px)';
            
            // Remove after animation completes
            setTimeout(() => {
                notificationItem.remove();
                
                // Store dismissal in localStorage
                const index = notificationItem.getAttribute('data-index');
                const dismissed = JSON.parse(localStorage.getItem('dismissedNotifications') || '[]');
                dismissed.push(index);
                localStorage.setItem('dismissedNotifications', JSON.stringify(dismissed));
                
                // Show empty state if no notifications left
                if (document.querySelectorAll('.notification-item').length === 0) {
                    const emptyState = `
                        <div class="preview-item py-4 px-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 120px;">
                            <div class="icon-circle bg-light mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="mdi mdi-bell-outline text-muted" style="font-size: 1.5rem;"></i>
                            </div>
                            <p class="font-weight-light small-text text-muted mb-0">Tidak ada notifikasi baru</p>
                        </div>
                    `;
                    document.querySelector('.preview-list').insertAdjacentHTML('afterbegin', emptyState);
                }
            }, 300);
        });
    });
});
</script>