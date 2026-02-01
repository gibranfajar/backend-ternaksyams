<?php

namespace App\Http\Controllers;

use App\Models\CategoryTutorial;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tutorials = Tutorial::orderBy('id', 'desc')->get();
        $categories = CategoryTutorial::all();
        return view('tutorials.index', compact('tutorials', 'categories'));
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
        $request->validate([
            'title' => 'required',
            'link' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:category_tutorials,id',
        ]);

        try {
            Tutorial::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'link' => $request->link,
                'thumbnail' => $request->file('thumbnail')->store('tutorials', 'public'),
                'category_id' => $request->category_id
            ]);

            return redirect()->route('tutorials.index')->with('success', 'Tutorial created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('tutorials.index')->with('error', 'Failed to create tutorial.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tutorial $tutorial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tutorial $tutorial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'title' => 'required',
            'link' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:category_tutorials,id',
        ]);

        try {

            // jika ada thumbnail baru
            if ($request->hasFile('thumbnail')) {
                // hapus thumbnail lama
                Storage::disk('public')->delete($tutorial->thumbnail);
                $tutorial->thumbnail = $request->file('thumbnail')->store('tutorials', 'public');
            }

            $tutorial->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'link' => $request->link,
                'thumbnail' => $tutorial->thumbnail,
                'category_id' => $request->category_id
            ]);

            return redirect()->route('tutorials.index')->with('success', 'Tutorial updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('tutorials.index')->with('error', 'Failed to update tutorial.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutorial $tutorial)
    {
        //
    }

    /**
     * Toggle status
     */
    public function toggleStatus(Tutorial $tutorial)
    {
        $tutorial->status = $tutorial->status === 'published' ? 'archived' : 'published';
        $tutorial->save();

        return redirect()->route('tutorials.index')->with('success', 'Tutorial status updated successfully.');
    }
}
