<?php

namespace App\Http\Controllers;

use App\Models\CategoryFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryFaq::orderBy('id', 'desc')->get();
        return view('faqs.category.index', compact('categories'));
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
            CategoryFaq::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);
            return redirect()->route('category-faqs.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category-faqs.index')->with('error', 'Failed to create category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryFaq $categoryFaq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryFaq $categoryFaq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryFaq $categoryFaq)
    {
        try {
            $categoryFaq->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);
            return redirect()->route('category-faqs.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category-faqs.index')->with('error', 'Failed to update category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryFaq $categoryFaq)
    {
        //
    }
}
