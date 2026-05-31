@extends('layouts.dashboard')

@section('title', __('Kelola Artikel'))

@section('content')
<x-dashboard.card padded="false">
    <x-slot name="title">{{ __('Daftar Artikel') }}</x-slot>
    <x-slot name="subtitle">{{ __('Kelola konten artikel yang tampil di beranda.') }}</x-slot>
    <x-slot name="actions">
        <x-dashboard.btn variant="primary" icon="fa-plus" :href="route('admin.articles.create')">{{ __('Tambah Artikel') }}</x-dashboard.btn>
    </x-slot>

    @if($articles->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Judul') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Slug') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Dipublikasikan') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($articles as $article)
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="max-w-xs px-6 py-3">
                                <div class="font-semibold text-rn-text">{{ $article->title }}</div>
                                <div class="text-xs text-slate-500">{{ __('Oleh') }} {{ $article->author->name ?? 'Admin' }}</div>
                            </td>
                            <td class="max-w-xs px-6 py-3 text-xs text-slate-500 break-all">{{ $article->slug }}</td>
                            <td class="whitespace-nowrap px-6 py-3">
                                @if($article->is_published)
                                    <x-dashboard.badge tone="success">Published</x-dashboard.badge>
                                @else
                                    <x-dashboard.badge tone="warning">Draft</x-dashboard.badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-xs text-slate-500">
                                {{ optional($article->published_at)->locale(app()->getLocale())->translatedFormat('d M Y H:i') ?: '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <x-dashboard.btn variant="ghost" size="sm" :href="route('articles.show', $article)" target="_blank">{{ __('Lihat') }}</x-dashboard.btn>
                                    <x-dashboard.btn variant="secondary" size="sm" :href="route('admin.articles.edit', $article)" icon="fa-pen">{{ __('Edit') }}</x-dashboard.btn>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline"
                                          data-confirm-title="{{ __('Hapus artikel?') }}"
                                          data-confirm-message="{{ __('Artikel ini akan dihapus permanen.') }}"
                                          data-confirm-confirm-text="{{ __('Ya, Hapus') }}"
                                          data-confirm-cancel-text="{{ __('Batal') }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dashboard.btn variant="danger" size="sm" type="submit" icon="fa-trash">{{ __('Hapus') }}</x-dashboard.btn>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $articles->links() }}</div>
        @endif
    @else
        <x-dashboard.empty icon="fa-newspaper" message="{{ __('Belum ada artikel.') }}" />
    @endif
</x-dashboard.card>
@endsection
