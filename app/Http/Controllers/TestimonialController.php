<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::orderByRaw("CASE WHEN status = 'true' THEN 1 ELSE 2 END")
            ->orderBy('id', 'desc')
            ->get();

        $types = [
            ['id' => 1, 'target' => 'user'],
            ['id' => 2, 'target' => 'affiliate'],
            ['id' => 3, 'target' => 'reseller'],
        ];

        return view('testimonials.index', compact('testimonials', 'types'));
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
                'name' => 'required',
                'city_age' => 'required',
                'social_media' => 'required',
                'message' => 'required',
                'target' => 'required|in:user,reseller,affiliate',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('testimonials', 'public');
            }

            Testimonial::create([
                'name' => $request->name,
                'city_age' => $request->city_age,
                'social_media' => $request->social_media,
                'message' => $request->message,
                'target' => $request->target,
                'image' => $imagePath ?? null,
                'status' => true,
            ]);
            return redirect()->route('testimonials.index')->with('success', 'Testimonial created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('testimonials.index')->with('error', 'Failed to create testimonial.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        try {

            $request->validate([
                'name' => 'required',
                'social_media' => 'required',
                'city_age' => 'required',
                'message' => 'required',
                'target' => 'required|in:user,reseller,affiliate',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($testimonial->image);
                $imagePath = $request->file('image')->store('testimonials', 'public');
                $testimonial->image = $imagePath;
            }

            $testimonial->name = $request->name;
            $testimonial->city_age = $request->city_age;
            $testimonial->social_media = $request->social_media;
            $testimonial->message = $request->message;
            $testimonial->target = $request->target;
            $testimonial->save();

            return redirect()->route('testimonials.index')->with('success', 'Testimonial updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('testimonials.index')->with('error', 'Failed to update testimonial.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        //
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Testimonial $testimonial)
    {
        try {
            $testimonial->status = $testimonial->status === true ? false : true;
            $testimonial->save();

            return redirect()->route('testimonials.index')->with('success', 'Testimonial status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('testimonials.index')->with('error', 'Failed to update testimonial status.');
        }
    }
}
