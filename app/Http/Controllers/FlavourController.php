<?php

namespace App\Http\Controllers;

use App\Models\Flavour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FlavourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('flavours.index', [
            'flavours' => Flavour::orderBy('id', 'desc')->get()
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
                'name' => 'required|unique:flavours,name'
            ]);

            Flavour::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return redirect()->route('flavours.index')->with('success', 'Flavour created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('flavours.index')->with('error', 'Failed to create flavour.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Flavour $flavour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flavour $flavour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flavour $flavour)
    {
        try {
            $request->validate([
                'name' => 'required|unique:flavours,name,' . $flavour->id
            ]);

            $flavour->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return redirect()->route('flavours.index')->with('success', 'Flavour updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('flavours.index')->with('error', 'Failed to update flavour.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flavour $flavour)
    {
        //
    }
}
