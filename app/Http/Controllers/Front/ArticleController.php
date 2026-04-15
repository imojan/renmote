<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $rawKeyword = trim((string) $request->query('q', ''));
        $keyword = preg_replace('/\s+/', ' ', $rawKeyword);
        $likeKeyword = $keyword !== '' ? '%' . str_replace(' ', '%', $keyword) . '%' : null;

        $articles = Article::with('author')
            ->published()
            ->when($likeKeyword, function ($query) use ($likeKeyword) {
                $query->where(function ($innerQuery) use ($likeKeyword) {
                    $innerQuery->where('title', 'like', $likeKeyword)
                        ->orWhere('excerpt', 'like', $likeKeyword)
                        ->orWhere('content', 'like', $likeKeyword);
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('front.articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_unless(
            $article->is_published && $article->published_at && $article->published_at->lte(now()),
            404
        );

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('front.articles.show', compact('article', 'relatedArticles'));
    }
}
