<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CategoryArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::orderBy('id', 'desc')->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryArticle::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:category_articles,id',
        ]);

        try {
            Article::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'thumbnail' => $request->file('thumbnail')->store('articles', 'public'),
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('articles.index')->with('success', 'Article created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the article.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = CategoryArticle::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:category_articles,id',
        ]);

        try {

            // update thumbnail jika ada
            if ($request->hasFile('thumbnail')) {
                // hapus thumbnail lama
                Storage::disk('public')->delete($article->thumbnail);
                $article->thumbnail = $request->file('thumbnail')->store('articles', 'public');
            }

            $article->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'thumbnail' => $article->thumbnail,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('articles.index')->with('success', 'Article updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the article.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Article $article)
    {
        $article->status = $article->status === 'published' ? 'archived' : 'published';
        $article->save();

        return redirect()->route('articles.index')->with('success', 'Article status updated successfully.');
    }
}
