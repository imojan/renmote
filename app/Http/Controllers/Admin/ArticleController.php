<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('author')
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(ArticleRequest $request)
    {
        $data = $this->buildPayload($request);
        $data['user_id'] = $request->user()->id;

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $data = $this->buildPayload($request, $article);

        if ($request->hasFile('cover_image') && $article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }

    private function buildPayload(ArticleRequest $request, ?Article $article = null): array
    {
        $validated = $request->validated();
        $isPublished = (bool)($validated['is_published'] ?? false);
        $sourceDateTime = $request->input('published_at');

        $titleChanged = !$article || $article->title !== $validated['title'];

        $payload = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'is_published' => $isPublished,
            'published_at' => $isPublished
                ? ($sourceDateTime
                    ? Carbon::parse($sourceDateTime)
                    : ($article?->published_at ?? now()))
                : null,
        ];

        if (!$article || $titleChanged) {
            $payload['slug'] = $this->generateUniqueSlug($validated['title'], $article?->id);
        }

        if ($request->hasFile('cover_image')) {
            $payload['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        return $payload;
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Article::where('slug', $slug)
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
