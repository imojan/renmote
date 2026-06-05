@props(['unreadCount' => 0])

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    {{-- Bell Icon Button --}}
    <button @click="open = !open"
            class="relative flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-100 hover:text-rn-text"
            aria-label="{{ __('dashboard.topbar.notifications_aria') }}">
        <i class="fa fa-bell"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>
    
    {{-- Dropdown Panel --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-3 w-80 sm:w-96 origin-top-right">
        
        <div class="rounded-2xl border-2 border-slate-200 bg-white shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4 text-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">Notifikasi</h3>
                    @if($unreadCount > 0)
                        <span class="rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold">
                            {{ $unreadCount }} baru
                        </span>
                    @endif
                </div>
            </div>
            
            {{-- Tabs --}}
            <div class="flex border-b border-slate-200 bg-slate-50" x-data="{ tab: 'all' }">
                <button @click="tab = 'all'"
                        class="flex-1 px-4 py-3 text-sm font-semibold transition"
                        :class="tab === 'all' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-slate-600 hover:bg-slate-100'">
                    Semua
                </button>
                <button @click="tab = 'unread'"
                        class="flex-1 px-4 py-3 text-sm font-semibold transition"
                        :class="tab === 'unread' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-slate-600 hover:bg-slate-100'">
                    Belum Dibaca
                </button>
            </div>
            
            {{-- Notification List --}}
            <div class="max-h-96 overflow-y-auto">
                @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                    <a href="{{ route('notifications.show', $notification) }}"
                       @click="open = false"
                       class="block border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50 {{ is_null($notification->read_at) ? 'bg-blue-50/50' : '' }}">
                        <div class="flex items-start gap-3">
                            {{-- Icon --}}
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full 
                                {{ is_null($notification->read_at) ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500' }}">
                                <i class="fa {{ $notification->data['icon'] ?? 'fa-bell' }} text-sm"></i>
                            </div>
                            
                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 mb-1 line-clamp-2">
                                    {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                                </p>
                                <p class="text-xs text-slate-600 mb-2 line-clamp-2">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    <i class="fa fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            {{-- Unread Indicator --}}
                            @if(is_null($notification->read_at))
                                <div class="h-2 w-2 shrink-0 rounded-full bg-blue-600"></div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 px-5">
                        <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                            <i class="fa fa-bell-slash text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-600">Tidak ada notifikasi</p>
                        <p class="text-xs text-slate-500 mt-1">Anda akan menerima notifikasi di sini</p>
                    </div>
                @endforelse
            </div>
            
            {{-- Footer --}}
            <div class="border-t border-slate-200 bg-slate-50 px-5 py-3">
                <a href="{{ route('notifications.index') }}"
                   @click="open = false"
                   class="flex items-center justify-center gap-2 rounded-lg py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">
                    <span>Lihat Semua Notifikasi</span>
                    <i class="fa fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>
