@extends('layouts.front')

@section('title', 'Artikel Renmote')

@section('content')
<section class="section article-list-section">
    <div class="article-list-head">
        <div class="article-list-intro">
            <h2 class="section-title">Artikel dan Rekomendasi</h2>
            <p class="article-list-subtitle">Kumpulan berita, tips perjalanan, dan panduan sewa motor terbaru dari Renmote.</p>
        </div>
        <form method="GET" action="{{ route('articles.index') }}" class="article-list-search">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel...">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <div class="artikel-grid article-list-grid">
        @forelse($articles as $article)
            <a href="{{ route('articles.show', $article) }}" class="artikel-card artikel-card-link">
                <div class="artikel-img">
                    <img src="{{ $article->cover_image ? Storage::url($article->cover_image) : asset('images/malang-1.png') }}" alt="{{ $article->title }}">
                    <span class="artikel-badge">Artikel Renmote</span>
                </div>
                <div class="artikel-body">
                    <div class="artikel-head-meta">
                        <div class="artikel-date">{{ optional($article->published_at)->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="artikel-title">{{ $article->title }}</div>
                    <div class="artikel-excerpt">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 130) }}</div>
                    <div class="artikel-read-wrap">
                        <span class="artikel-read">Lihat Selengkapnya <i class="fa fa-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        @empty
            <div class="artikel-empty-state">
                <p class="artikel-empty-title">Belum ada artikel yang dipublikasikan.</p>
                <span class="artikel-empty-subtitle">Silakan cek kembali dalam beberapa saat.</span>
            </div>
        @endforelse
    </div>

    @if($articles->hasPages())
        <div class="article-list-pagination">{{ $articles->links() }}</div>
    @endif
</section>
@endsection
