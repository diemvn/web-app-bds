<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $branding = SystemSetting::branding();
        $categories = ArticleCategory::where('is_active', true)->orderBy('sort_order')->get();
        
        $query = Article::where('is_published', true)->with('category');
        
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        $articles = $query->latest('published_at')->paginate(12);

        return view('public.news.index', compact('branding', 'categories', 'articles'));
    }

    public function show($slug)
    {
        $branding = SystemSetting::branding();
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->with(['category', 'author'])
            ->firstOrFail();

        // Increment views
        $article->increment('views_count');

        $relatedArticles = Article::where('article_category_id', $article->article_category_id)
            ->where('id', '!=', $article->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.news.show', compact('branding', 'article', 'relatedArticles'));
    }
}
