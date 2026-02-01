<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index', [
            'categories' => Category::orderBy('id', 'desc')->get()
        ]);
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
            $request->validate([
                'name' => 'required|unique:categories,name'
            ]);

            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('categories.index')->with('error', 'Failed to create category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id
            ]);

            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('categories.index')->with('error', 'Failed to update category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
