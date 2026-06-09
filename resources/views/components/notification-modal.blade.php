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
    
    <div class="rounded-2xl border overflow-hidden backdrop-blur-sm"
         :class="{
             'bg-emerald-50/80 border-emerald-200': type === 'success',
             'bg-red-50/80 border-red-200': type === 'error',
             'bg-amber-50/80 border-amber-200': type === 'warning',
             'bg-blue-50/80 border-blue-200': type === 'info'
         }"
         :style="{
             boxShadow: type === 'success' ? '0 8px 32px -4px rgba(16, 185, 129, 0.12), 0 2px 8px -2px rgba(16, 185, 129, 0.08)' :
                         type === 'error'   ? '0 8px 32px -4px rgba(239, 68, 68, 0.12), 0 2px 8px -2px rgba(239, 68, 68, 0.08)' :
                         type === 'warning' ? '0 8px 32px -4px rgba(245, 158, 11, 0.12), 0 2px 8px -2px rgba(245, 158, 11, 0.08)' :
                                              '0 8px 32px -4px rgba(59, 130, 246, 0.12), 0 2px 8px -2px rgba(59, 130, 246, 0.08)'
         }">
        
        <div class="px-4 py-3.5">
            <div class="flex items-start gap-3">
                {{-- Icon --}}
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                     :class="{
                         'bg-emerald-500 text-white': type === 'success',
                         'bg-red-500 text-white': type === 'error',
                         'bg-amber-400 text-white': type === 'warning',
                         'bg-blue-500 text-white': type === 'info'
                     }">
                    {{-- Success: Checkmark --}}
                    <svg x-show="type === 'success'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{-- Error: X mark --}}
                    <svg x-show="type === 'error'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{-- Warning: Exclamation --}}
                    <svg x-show="type === 'warning'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{-- Info: Info circle --}}
                    <svg x-show="type === 'info'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>
                
                <div class="flex-1 min-w-0 pt-0.5">
                    {{-- Title --}}
                    <h4 class="text-[13.5px] font-bold leading-snug"
                        :class="{
                            'text-emerald-900': type === 'success',
                            'text-red-900': type === 'error',
                            'text-amber-900': type === 'warning',
                            'text-blue-900': type === 'info'
                        }"
                        x-text="title"></h4>
                    
                    {{-- Message --}}
                    <p class="text-xs leading-relaxed mt-0.5"
                       :class="{
                           'text-emerald-700/80': type === 'success',
                           'text-red-700/80': type === 'error',
                           'text-amber-700/80': type === 'warning',
                           'text-blue-700/80': type === 'info'
                       }"
                       x-text="message"></p>
                </div>
                
                {{-- Close Button --}}
                <button @click="close()" 
                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg transition-colors mt-0.5"
                        :class="{
                            'text-emerald-400 hover:text-emerald-600 hover:bg-emerald-100': type === 'success',
                            'text-red-400 hover:text-red-600 hover:bg-red-100': type === 'error',
                            'text-amber-400 hover:text-amber-600 hover:bg-amber-100': type === 'warning',
                            'text-blue-400 hover:text-blue-600 hover:bg-blue-100': type === 'info'
                        }">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
        autoCloseTimer: null,
        duration: 5000, // 5 seconds
        
        handleNotification(detail) {
            this.type = detail.type || 'info';
            this.title = detail.title || this.getDefaultTitle(this.type);
            this.message = detail.message || '';
            this.show = true;
            
            // Clear existing timers
            this.clearTimers();
            
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
