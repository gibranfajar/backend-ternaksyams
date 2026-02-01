<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sizes.index', [
            'sizes' => Size::orderBy('id', 'desc')->get()
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
                'label' => 'required|numeric|min:1',
            ]);
            Size::create($request->all());
            return redirect()->route('sizes.index')->with('success', 'Size created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sizes.index')->with('error', 'Failed to create size.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        try {
            $request->validate([
                'label' => 'required|numeric|min:1',
            ]);
            $size->update($request->all());
            return redirect()->route('sizes.index')->with('success', 'Size updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sizes.index')->with('error', 'Failed to update size.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        //
    }
}
