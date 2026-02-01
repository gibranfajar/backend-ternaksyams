<?php

namespace App\Http\Controllers;

use App\Models\CategoryTutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryTutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryTutorial::orderBy('id', 'desc')->get();
        return view('tutorials.category.index', compact('categories'));
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
            CategoryTutorial::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);
            return redirect()->route('category-tutorials.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category-tutorials.index')->with('error', 'Failed to create category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryTutorial $categoryTutorial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryTutorial $categoryTutorial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryTutorial $categoryTutorial)
    {
        try {
            $categoryTutorial->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);
            return redirect()->route('category-tutorials.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category-tutorials.index')->with('error', 'Failed to update category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryTutorial $categoryTutorial)
    {
        //
    }
}
