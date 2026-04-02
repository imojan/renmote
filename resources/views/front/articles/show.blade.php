@extends('layouts.front')

@section('title', $article->title)

@section('content')
<section class="article-detail-wrap">
    <article class="article-detail-main">
        <img src="{{ $article->cover_image ? Storage::url($article->cover_image) : asset('images/malang-2.png') }}" alt="{{ $article->title }}" class="article-detail-cover">

        <div class="article-detail-body">
            <h1>{{ $article->title }}</h1>
            <p class="article-detail-meta">{{ optional($article->published_at)->translatedFormat('d M Y') }} • Renmote Editorial</p>

            <div class="article-detail-content">
                {!! nl2br(e($article->content)) !!}
            </div>

            <a href="{{ route('articles.index') }}" class="article-back-link">
                <i class="fa fa-arrow-left"></i> Kembali ke daftar artikel
            </a>
        </div>
    </article>

    @if($relatedArticles->count() > 0)
        <section class="article-related-wrap">
            <div class="article-related-head">
                <h2>Artikel Lainnya</h2>
                <a href="{{ route('articles.index') }}">Lihat Semua <i class="fa fa-arrow-right"></i></a>
            </div>

            <div class="article-related-grid">
                @foreach($relatedArticles as $related)
                    <article class="article-related-card">
                        <img src="{{ $related->cover_image ? Storage::url($related->cover_image) : asset('images/malang-1.png') }}" alt="{{ $related->title }}">
                        <div>
                            <p>{{ optional($related->published_at)->translatedFormat('d M Y') }}</p>
                            <a href="{{ route('articles.show', $related) }}">{{ $related->title }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</section>
@endsection
