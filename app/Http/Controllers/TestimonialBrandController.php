<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\TestimonialBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialBrandController extends Controller
{
    public function index()
    {
        $testimonials = TestimonialBrand::orderByRaw("CASE WHEN status = 'true' THEN 1 ELSE 2 END")
            ->orderBy('id', 'desc')
            ->get();

        $brands = Brand::all();

        return view('testimonial-brands.index', compact('testimonials', 'brands'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'city_age' => 'required',
                'social_media' => 'required',
                'message' => 'required',
                'brand_id' => 'required|exists:brands,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('testimonialBrands', 'public');
            }

            TestimonialBrand::create([
                'name' => $request->name,
                'city_age' => $request->city_age,
                'social_media' => $request->social_media,
                'message' => $request->message,
                'brand_id' => $request->brand_id,
                'image' => $imagePath ?? null,
                'status' => true,
            ]);
            return redirect()->route('testimonial-brands.index')->with('success', 'Testimonial brand created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('testimonial-brands.index')->with('error', 'Failed to create testimonial brand.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'city_age' => 'required',
                'social_media' => 'required',
                'message' => 'required',
                'brand_id' => 'required|exists:brands,id',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $testimonial = TestimonialBrand::findOrFail($id);

            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($testimonial->image);
                $imagePath = $request->file('image')->store('testimonialBrands', 'public');
                $testimonial->image = $imagePath;
            }

            $testimonial->name = $request->name;
            $testimonial->city_age = $request->city_age;
            $testimonial->social_media = $request->social_media;
            $testimonial->message = $request->message;
            $testimonial->brand_id = $request->brand_id;
            $testimonial->save();

            return redirect()->route('testimonial-brands.index')->with('success', 'Testimonial brand updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('testimonial-brands.index')->with('error', 'Failed to update testimonial brand.');
        }
    }

    public function toggleStatus(TestimonialBrand $testimonial)
    {
        try {
            $testimonial->status = $testimonial->status === true ? false : true;
            $testimonial->save();

            return redirect()->route('testimonial-brands.index')->with('success', 'Testimonial status updated successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('testimonial-brands.index')->with('error', 'Failed to update testimonial status.');
        }
    }
}
