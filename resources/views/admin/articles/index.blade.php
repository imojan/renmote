@extends('layouts.dashboard')

@section('title', 'Kelola Artikel')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Artikel</h2>
            <p class="text-sm text-slate-500">Kelola konten artikel yang tampil di beranda.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Artikel
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dipublikasikan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($articles as $article)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $article->title }}</div>
                                <div class="text-sm text-slate-500">Oleh {{ $article->author->name ?? 'Admin' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $article->slug }}</td>
                            <td class="px-6 py-4">
                                @if($article->is_published)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Published</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ optional($article->published_at)->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('articles.show', $article) }}" target="_blank" class="text-slate-600 hover:text-slate-800">Lihat</a>
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">{{ $articles->links() }}</div>
        @endif
    </div>
@endsection
