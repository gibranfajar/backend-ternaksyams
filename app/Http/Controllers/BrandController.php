<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandAbout;
use App\Models\BrandDetail;
use App\Models\BrandFeature;
use App\Models\BrandHowitwork;
use App\Models\BrandProductsidebar;
use App\Models\BrandSize;
use App\Models\BrandSlider;
use App\Models\BrandTestimonial;
use App\Models\BrandVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // === 1️⃣ Upload utama: image brand ===
            $brandImage = null;
            if ($request->hasFile('image')) {
                $brandImage = $request->file('image')->store('brands', 'public');
            }

            // === 2️⃣ Simpan ke table brands ===
            $brand = Brand::create([
                'brand' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'image' => $brandImage,
                'main_color' => $request->main_color,
                'accent_color' => $request->accent_color
            ]);

            // === 3️⃣ Simpan ke brand_sizes ===
            if ($request->filled('sizes')) {
                foreach ($request->sizes as $size) {
                    BrandSize::create([
                        'brand_id' => $brand->id,
                        'size' => $size,
                    ]);
                }
            }

            // === Simpan ke brand_variants ===
            if (!empty($request->variants['name'])) {
                foreach ($request->variants['name'] as $index => $variantName) {
                    $variantImage = null;
                    $variantDescription = $request->variants['descriptions'][$index] ?? null;

                    if (!empty($request->variants['images'][$index])) {
                        $variantImage = $request->variants['images'][$index]->store('brand_variants', 'public');
                    }

                    BrandVariant::create([
                        'brand_id'    => $brand->id,
                        'variant'     => $variantName,
                        'description' => $variantDescription,
                        'image'       => $variantImage,
                    ]);
                }
            }

            // === 5️⃣ Simpan ke brand_details ===
            $bannerPath = null;
            if ($request->hasFile('detail_banner')) {
                $bannerPath = $request->file('detail_banner')->store('brand_banners', 'public');
            }

            // === Simpan ke brand sliders ===
            if ($request->has('sliders')) {
                foreach ($request->sliders as $slider) {
                    if (isset($slider['image'])) {
                        $path = $slider['image']->store('brand_sliders', 'public');

                        BrandSlider::create([
                            'brand_id' => $brand->id,
                            'image' => $path
                        ]);
                    }
                }
            }


            BrandDetail::create([
                'brand_id' => $brand->id,
                'herotitle' => $request->detail_title,
                'herosubtitle' => $request->detail_subtitle,
                'banner' => $bannerPath,
            ]);

            // === 6️⃣ Simpan ke brand_testimonials ===
            BrandTestimonial::create([
                'brand_id' => $brand->id,
                'quotes' => $request->detail_quotes,
                'textreview' => $request->detail_text_review,
                'textcta' => $request->detail_textcta_review,
                'linkcta' => $request->detail_linkcta_review,
                'cardcolor' => $request->detail_cardcolor_review,
                'textcolor' => $request->detail_textcolor_review,
            ]);

            // === 7️⃣ Simpan ke brand_features ===
            BrandFeature::create([
                'brand_id' => $brand->id,
                'marquebgcolor' => $request->detail_marque_bgcolor,
                'marquetextcolor' => $request->detail_marque_textcolor,
                'features' => $request->detail_marque,
            ]);

            // === 8️⃣ Simpan ke brand_productsidebars ===
            BrandProductsidebar::create([
                'brand_id' => $brand->id,
                'headline' => $request->detail_headline_product,
                'description' => $request->detail_description_product,
                'ctatext' => $request->detail_ctatext_product,
                'ctalink' => $request->detail_ctalink_product,
                'cardcolor' => $request->detail_cardcolor_product,
                'textcolor' => $request->detail_textcolor_product,
            ]);

            // === 9️⃣ Simpan ke brand_abouts ===
            $aboutImage = null;
            if ($request->hasFile('detail_about_image')) {
                $aboutImage = $request->file('detail_about_image')->store('brand_about', 'public');
            }

            BrandAbout::create([
                'brand_id' => $brand->id,
                'title' => $request->detail_title_about,
                'description' => $request->{'detail_description-about'},
                'image' => $aboutImage,
                'ctatext' => $request->detail_about_ctatext,
                'ctalink' => $request->detail_about_ctalink,
            ]);

            // === 🔟 Simpan ke brand_howitworks ===
            $howImage = null;
            if ($request->hasFile('detail_howitwork_image')) {
                $howImage = $request->file('detail_howitwork_image')->store('brand_howitwork', 'public');
            }

            BrandHowitwork::create([
                'brand_id' => $brand->id,
                'tagline' => $request->detail_tagline_howitwork,
                'image' => $howImage,
                'headline' => $request->detail_headline_howitwork,
                'steps' => $request->detail_steps_howitwork,
                'ctatext' => $request->detail_ctatext_product ?? '', // opsional jika belum ada di form
                'ctalink' => $request->detail_ctalink_product ?? '',
            ]);

            DB::commit();

            return redirect()->route('brands.index')->with('success', 'Brand berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan brand: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Brand $brand)
    {
        $brand->load('sizes', 'variants', 'detail', 'testimonial', 'feature', 'productsidebar', 'about', 'howitwork');
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $brand = Brand::findOrFail($id);

            // === 1️⃣ Update image utama (brand image) ===
            $brandImage = $brand->image;
            if ($request->hasFile('image')) {
                if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                    Storage::disk('public')->delete($brand->image);
                }
                $brandImage = $request->file('image')->store('brands', 'public');
            }

            $brand->update([
                'brand' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'image' => $brandImage,
                'main_color' => $request->main_color,
                'accent_color' => $request->accent_color
            ]);

            // === 2️⃣ Sizes ===
            $brand->sizes()->delete();
            if ($request->filled('sizes')) {
                foreach ($request->sizes as $size) {
                    BrandSize::create([
                        'brand_id' => $brand->id,
                        'size' => $size,
                    ]);
                }
            }

            // === 3️⃣ Variants ===
            $brand->variants()->delete();

            if (!empty($request->variants['name'])) {
                foreach ($request->variants['name'] as $index => $variantName) {
                    $variantDescription = $request->variants['descriptions'][$index] ?? null;
                    $oldImage = $request->variants['old_images'][$index] ?? null;
                    $variantImage = $oldImage; // default: gambar lama

                    // kalau upload baru, replace yang lama
                    if (
                        !empty($request->variants['images'][$index]) &&
                        $request->variants['images'][$index] instanceof \Illuminate\Http\UploadedFile
                    ) {

                        // hapus gambar lama kalau ada
                        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                        }

                        $variantImage = $request->variants['images'][$index]->store('brand_variants', 'public');
                    }

                    // fallback aman kalau masih null, bisa isi string kosong
                    BrandVariant::create([
                        'brand_id'    => $brand->id,
                        'variant'     => $variantName,
                        'description' => $variantDescription,
                        'image'       => $variantImage ?? '', // biar gak null
                    ]);
                }
            }

            // UPDATE OLD SLIDERS
            if ($request->has('old_sliders')) {
                foreach ($request->old_sliders as $id => $file) {
                    if ($file) {
                        $slider = BrandSlider::find($id);

                        if ($slider) {
                            // upload dulu
                            $path = $file->store('brand_sliders', 'public');

                            // hapus file lama
                            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                                Storage::disk('public')->delete($slider->image);
                            }

                            // update db
                            $slider->update(['image' => $path]);
                        }
                    }
                }
            }


            // DELETE SLIDERS
            if ($request->has('delete_sliders')) {
                $sliders = BrandSlider::whereIn('id', $request->delete_sliders)->get();

                foreach ($sliders as $slider) {
                    if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                        Storage::disk('public')->delete($slider->image);
                    }

                    $slider->delete();
                }
            }


            // ADD NEW SLIDERS
            if ($request->has('sliders')) {
                foreach ($request->sliders as $item) {
                    if (!empty($item['image'])) {
                        $path = $item['image']->store('brand_sliders', 'public');

                        BrandSlider::create([
                            'brand_id' => $brand->id,
                            'image' => $path
                        ]);
                    }
                }
            }

            // === 4️⃣ Detail ===
            $detail = $brand->detail;
            $bannerPath = $detail?->banner;

            if ($request->hasFile('detail_banner')) {
                if ($bannerPath && Storage::disk('public')->exists($bannerPath)) {
                    Storage::disk('public')->delete($bannerPath);
                }
                $bannerPath = $request->file('detail_banner')->store('brand_banners', 'public');
            }

            $brand->detail()->updateOrCreate(
                ['brand_id' => $brand->id],
                [
                    'herotitle' => $request->detail_title,
                    'herosubtitle' => $request->detail_subtitle,
                    'banner' => $bannerPath,
                ]
            );

            // === 5️⃣ Testimonial ===
            $brand->testimonial()->updateOrCreate(
                ['brand_id' => $brand->id],
                [
                    'quotes' => $request->detail_quotes,
                    'textreview' => $request->detail_text_review,
                    'textcta' => $request->detail_textcta_review,
                    'linkcta' => $request->detail_linkcta_review,
                    'cardcolor' => $request->detail_cardcolor_review,
                    'textcolor' => $request->detail_textcolor_review,
                ]
            );

            // === 6️⃣ Feature ===
            $brand->feature()->updateOrCreate(
                ['brand_id' => $brand->id],
                [
                    'marquebgcolor' => $request->detail_marque_bgcolor,
                    'marquetextcolor' => $request->detail_marque_textcolor,
                    'features' => $request->detail_marque,
                ]
            );

            // === 7️⃣ Product Sidebar ===
            $brand->productsidebar()->updateOrCreate(
                ['brand_id' => $brand->id],
                [
                    'headline' => $request->detail_headline_product,
                    'description' => $request->detail_description_product,
                    'ctatext' => $request->detail_ctatext_product,
                    'ctalink' => $request->detail_ctalink_product,
                    'cardcolor' => $request->detail_cardcolor_product,
                    'textcolor' => $request->detail_textcolor_product,
                ]
            );

            // === 8️⃣ About ===
            $about = $brand->about;
            $aboutImage = $about?->image;

            if ($request->hasFile('detail_about_image')) {
                if ($aboutImage && Storage::disk('public')->exists($aboutImage)) {
                    Storage::disk('public')->delete($aboutImage);
                }
                $aboutImage = $request->file('detail_about_image')->store('brand_about', 'public');
            }

            $brand->about()->updateOrCreate(
                ['brand_id' => $brand->id],
                [
                    'title' => $request->detail_title_about,
                    'description' => $request->input('detail_description-about'),
                    'image' => $aboutImage,
                    'ctatext' => $request->detail_about_ctatext,
                    'ctalink' => $request->detail_about_ctalink,
                ]
            );

            // === 9️⃣ How It Work ===
            $howitwork = $brand->howitwork;
            $howImage = $howitwork?->image;

            if ($request->hasFile('detail_howitwork_image')) {
                if ($howImage && Storage::disk('public')->exists($howImage)) {
                    Storage::disk('public')->delete($howImage);
                }
                $howImage = $request->file('detail_howitwork_image')->store('brand_howitwork', 'public');
            }

            $brand->howitwork()->updateOrCreate(
                ['brand_id' => $brand->id],
                [
                    'tagline' => $request->detail_tagline_howitwork,
                    'image' => $howImage,
                    'headline' => $request->detail_headline_howitwork,
                    'steps' => $request->detail_steps_howitwork,
                    'ctatext' => $request->detail_ctatext_product ?? '',
                    'ctalink' => $request->detail_ctalink_product ?? '',
                ]
            );

            DB::commit();

            return redirect()->route('brands.index')->with('success', 'Brand berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui brand: ' . $e->getMessage())->withInput();
        }
    }
}
