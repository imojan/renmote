@extends('layouts.front')

@section('title', 'Artikel Renmote')

@section('content')
<section class="article-page-wrap">
    <div class="article-page-head">
        <div>
            <h1>Articles</h1>
            <p>Latest news and recommendations from Renmote.</p>
        </div>
        <form method="GET" action="{{ route('articles.index') }}" class="article-search-form">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles...">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <div class="article-grid">
        @forelse($articles as $article)
            <article class="article-card">
                <a href="{{ route('articles.show', $article) }}" class="article-cover-link">
                    <img src="{{ $article->cover_image ? Storage::url($article->cover_image) : asset('images/malang-1.png') }}" alt="{{ $article->title }}" class="article-cover">
                </a>
                <div class="article-body">
                    <div class="article-date">{{ optional($article->published_at)->translatedFormat('d M Y') }}</div>
                    <h2>
                        <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                    </h2>
                    <p>{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 160) }}</p>
                    <a href="{{ route('articles.show', $article) }}" class="article-read">Read More <i class="fa fa-arrow-right"></i></a>
                </div>
            </article>
        @empty
            <div class="article-empty">Belum ada artikel yang dipublikasikan.</div>
        @endforelse
    </div>

    @if($articles->hasPages())
        <div class="article-pagination">{{ $articles->links() }}</div>
    @endif
</section>
@endsection
