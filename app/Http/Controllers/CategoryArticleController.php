<?php

namespace App\Http\Controllers;

use App\Models\CategoryArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryArticle::orderBy('id', 'desc')->get();
        return view('articles.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            CategoryArticle::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);
            return redirect()->route('category-articles.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category-articles.index')->with('error', 'Failed to create category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryArticle $categoryArticle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryArticle $categoryArticle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryArticle $categoryArticle)
    {
        try {
            $categoryArticle->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);
            return redirect()->route('category-articles.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category-articles.index')->with('error', 'Failed to update category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryArticle $categoryArticle)
    {
        //
    }
}
