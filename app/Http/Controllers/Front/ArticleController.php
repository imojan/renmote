<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with('author')
            ->published()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($innerQuery) use ($request) {
                    $innerQuery->where('title', 'like', '%' . $request->q . '%')
                        ->orWhere('excerpt', 'like', '%' . $request->q . '%')
                        ->orWhere('content', 'like', '%' . $request->q . '%');
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
