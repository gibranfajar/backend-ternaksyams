<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::all();
        return view('sliders.index', compact('sliders'));
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
            // Validasi awal (termasuk image kalau ada)
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'ctatext' => 'required|string',
                'ctalink' => 'required|string',
            ]);

            // Upload image jika ada
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('sliders', 'public');
            }

            // Simpan ke database
            Slider::create($validated);

            return redirect()->route('sliders.index')->with('success', 'Slider created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sliders.index')->with('error', 'Failed to create slider.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        try {
            // Validasi awal (termasuk image kalau ada)
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'ctatext' => 'required|string',
                'ctalink' => 'required|string',
            ]);

            // Upload image jika ada
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($slider->image);
                $validated['image'] = $request->file('image')->store('sliders', 'public');
            }

            // Simpan ke database
            $slider->update($validated);

            return redirect()->route('sliders.index')->with('success', 'Slider updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sliders.index')->with('error', 'Failed to update slider.');
        }
    }

    public function toggleStatus(Slider $slider)
    {
        try {
            $slider->status = !$slider->status;
            $slider->save();
            return redirect()->route('sliders.index')->with('success', 'Slider status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sliders.index')->with('error', 'Failed to update slider status.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        try {
            Storage::disk('public')->delete($slider->image);
            $slider->delete();
            return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sliders.index')->with('error', 'Failed to delete slider.');
        }
    }
}
