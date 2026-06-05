{{-- Toast Notification Component (Top-right corner, auto-dismiss) --}}
<div x-data="toastNotification()" 
     x-show="show" 
     x-cloak
     @notification-toast.window="handleNotification($event.detail)"
     x-transition:enter="transform transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transform transition ease-in duration-200"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="fixed top-20 right-4 z-[9999] w-full max-w-sm"
     style="display: none;">
    
    <div class="rounded-xl shadow-2xl border-l-4 overflow-hidden"
         :class="{
             'bg-white border-emerald-500': type === 'success',
             'bg-white border-red-500': type === 'error',
             'bg-white border-amber-500': type === 'warning',
             'bg-white border-blue-500': type === 'info'
         }">
        
        <div class="p-4">
            <div class="flex items-start gap-3">
                {{-- Icon --}}
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                     :class="{
                         'bg-emerald-100 text-emerald-600': type === 'success',
                         'bg-red-100 text-red-600': type === 'error',
                         'bg-amber-100 text-amber-600': type === 'warning',
                         'bg-blue-100 text-blue-600': type === 'info'
                     }">
                    <i class="text-lg"
                       :class="{
                           'fa fa-check': type === 'success',
                           'fa fa-exclamation-triangle': type === 'error',
                           'fa fa-exclamation-circle': type === 'warning',
                           'fa fa-info-circle': type === 'info'
                       }"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    {{-- Title --}}
                    <h4 class="text-sm font-bold mb-1"
                        :class="{
                            'text-emerald-900': type === 'success',
                            'text-red-900': type === 'error',
                            'text-amber-900': type === 'warning',
                            'text-blue-900': type === 'info'
                        }"
                        x-text="title"></h4>
                    
                    {{-- Message --}}
                    <p class="text-xs leading-relaxed"
                       :class="{
                           'text-emerald-700': type === 'success',
                           'text-red-700': type === 'error',
                           'text-amber-700': type === 'warning',
                           'text-blue-700': type === 'info'
                       }"
                       x-text="message"></p>
                </div>
                
                {{-- Close Button --}}
                <button @click="close()" 
                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full transition-colors hover:bg-slate-100">
                    <i class="fa fa-xmark text-xs text-slate-400"></i>
                </button>
            </div>
            
            {{-- Progress Bar --}}
            <div class="mt-3 h-1 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full transition-all ease-linear"
                     :class="{
                         'bg-emerald-500': type === 'success',
                         'bg-red-500': type === 'error',
                         'bg-amber-500': type === 'warning',
                         'bg-blue-500': type === 'info'
                     }"
                     :style="`width: ${progress}%`"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toastNotification() {
    return {
        show: false,
        type: 'info',
        title: '',
        message: '',
        progress: 100,
        autoCloseTimer: null,
        progressInterval: null,
        duration: 5000, // 5 seconds
        
        handleNotification(detail) {
            this.type = detail.type || 'info';
            this.title = detail.title || this.getDefaultTitle(this.type);
            this.message = detail.message || '';
            this.show = true;
            this.progress = 100;
            
            // Clear existing timers
            this.clearTimers();
            
            // Progress bar animation
            const progressStep = 100 / (this.duration / 50);
            this.progressInterval = setInterval(() => {
                this.progress -= progressStep;
                if (this.progress <= 0) {
                    this.progress = 0;
                    clearInterval(this.progressInterval);
                }
            }, 50);
            
            // Auto close
            this.autoCloseTimer = setTimeout(() => {
                this.close();
            }, this.duration);
        },
        
        close() {
            this.show = false;
            this.clearTimers();
        },
        
        clearTimers() {
            if (this.autoCloseTimer) {
                clearTimeout(this.autoCloseTimer);
                this.autoCloseTimer = null;
            }
            if (this.progressInterval) {
                clearInterval(this.progressInterval);
                this.progressInterval = null;
            }
        },
        
        getDefaultTitle(type) {
            const titles = {
                success: 'Berhasil!',
                error: 'Terjadi Kesalahan',
                warning: 'Peringatan',
                info: 'Informasi'
            };
            return titles[type] || 'Notifikasi';
        }
    }
}

// Global function to trigger toast notification
window.showNotification = function(type, title, message) {
    window.dispatchEvent(new CustomEvent('notification-toast', {
        detail: { type, title, message }
    }));
};

// Backward compatibility
window.showNotificationModal = window.showNotification;

// Auto-show notification from session flash messages on page load
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        window.showNotification('success', 'Berhasil!', '{{ session('success') }}');
    @elseif(session('error'))
        window.showNotification('error', 'Terjadi Kesalahan', '{{ session('error') }}');
    @elseif(session('warning'))
        window.showNotification('warning', 'Peringatan', '{{ session('warning') }}');
    @elseif(session('info'))
        window.showNotification('info', 'Informasi', '{{ session('info') }}');
    @endif
});
</script>
@endpush
