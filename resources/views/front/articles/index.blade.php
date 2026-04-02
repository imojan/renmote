@extends('layouts.front')

@section('title', 'Artikel Renmote')

@section('content')
<section class="article-page-wrap">
    <div class="article-page-head">
        <div>
            <h1>Artikel</h1>
            <p>Kumpulan berita dan rekomendasi terbaru dari Renmote.</p>
        </div>
        <form method="GET" action="{{ route('articles.index') }}" class="article-search-form">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel...">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <div class="article-grid">
        @forelse($articles as $article)
            <a href="{{ route('articles.show', $article) }}" class="article-card article-card-link">
                <div class="article-cover-link">
                    <img src="{{ $article->cover_image ? Storage::url($article->cover_image) : asset('images/malang-1.png') }}" alt="{{ $article->title }}" class="article-cover">
                </div>
                <div class="article-body">
                    <div class="article-meta-row">
                        <div class="article-date">{{ optional($article->published_at)->translatedFormat('d M Y') }}</div>
                    </div>
                    <h2>
                        {{ $article->title }}
                    </h2>
                    <p>{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 130) }}</p>
                    <div class="article-action-row">
                        <span class="article-read">Lihat Selengkapnya <i class="fa fa-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        @empty
            <div class="article-empty">Belum ada artikel yang dipublikasikan.</div>
        @endforelse
    </div>

    @if($articles->hasPages())
        <div class="article-pagination">{{ $articles->links() }}</div>
    @endif
</section>
@endsection
