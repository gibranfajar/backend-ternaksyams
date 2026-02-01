<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::orderBy('id', 'desc')->get();
        return view('promotions.index', compact('promotions'));
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
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        try {
            $thumbnailPath = $request->file('thumbnail')->store('promotions', 'public');

            // buatkan untuk status jika memang start date tanggal sekarang maka active
            $status = $request->start_date <= now() ? 'active' : 'inactive';

            Promotion::create([
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $thumbnailPath,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $status,
            ]);

            return redirect()->route('promotions.index')->with('success', 'Promotion created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('promotions.index')->with('error', 'Failed to create promotion.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        try {
            if ($request->hasFile('thumbnail')) {
                Storage::disk('public')->delete($promotion->thumbnail);
                $thumbnailPath = $request->file('thumbnail')->store('promotions', 'public');
            }
            // buatkan untuk status jika memang start date tanggal sekarang maka active
            $status = $request->start_date <= now() ? 'active' : 'inactive';

            $promotion->update([
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $thumbnailPath ?? $promotion->thumbnail,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $status,
            ]);

            return redirect()->route('promotions.index')->with('success', 'Promotion created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('promotions.index')->with('error', 'Failed to create promotion.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        //
    }

    /**
     * Toggle the is_popup status of the specified promotion.
     */
    public function togglePopup($id)
    {
        $promotion = Promotion::findOrFail($id);

        // jika lagi ON → matikan
        if ($promotion->is_popup) {
            $promotion->is_popup = false;
        } else {
            // kalau mau hanya 1 popup aktif:
            Promotion::where('is_popup', true)->update(['is_popup' => false]);

            // aktifkan yang diklik
            $promotion->is_popup = true;
        }

        $promotion->save();

        return back()->with('success', 'Popup status updated.');
    }
}
