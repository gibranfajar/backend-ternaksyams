<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $benefits = Benefit::orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END")
            ->orderBy('id', 'desc')
            ->get();

        $types = [
            ['id' => 1, 'type' => 'reseller'],
            ['id' => 2, 'type' => 'affiliate'],
        ];
        return view('benefits.index', compact('benefits', 'types'));
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
            'benefit' => 'required|string|max:255',
            'type' => 'required|string|in:reseller,affiliate',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            Benefit::create([
                'benefit' => $request->benefit,
                'type' => $request->type,
                'thumbnail' => $request->file('thumbnail')->store('thumbnails', 'public'),
            ]);

            return redirect()->route('benefits.index')->with('success', 'Benefit created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('benefits.index')->with('error', 'Failed to create benefit.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Benefit $benefit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Benefit $benefit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Benefit $benefit)
    {

        $request->validate([
            'benefit' => 'required|string|max:255',
            'type' => 'required|string|in:reseller,affiliate',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {

            if ($request->hasFile('thumbnail')) {
                Storage::disk('public')->delete($benefit->thumbnail);
                $benefit->update([
                    'thumbnail' => $request->file('thumbnail')->store('thumbnails', 'public'),
                ]);
            }

            $benefit->update([
                'benefit' => $request->benefit,
                'type' => $request->type,
                'thumbnail' => $benefit->thumbnail,
            ]);

            return redirect()->route('benefits.index')->with('success', 'Benefit updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('benefits.index')->with('error', 'Failed to update benefit.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Benefit $benefit)
    {
        //
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Benefit $benefit)
    {
        $benefit->status = $benefit->status === 'active' ? 'inactive' : 'active';
        $benefit->save();

        return redirect()->route('benefits.index')->with('success', 'Benefit status updated successfully.');
    }
}
