<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Flavour;
use App\Models\Product;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('variants')->orderBy('id', 'desc')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flavours = Flavour::all();
        $categories = Category::all();
        $sizes = Size::all();
        $brands = Brand::all();
        return view('products.create', compact('flavours', 'categories', 'sizes', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                // Simpan gizi image
                $giziPath = null;
                if ($request->hasFile('gizi_path')) {
                    $giziPath = $request->file('gizi_path')->store('gizi', 'public');
                }

                // 1. Simpan produk utama
                $product = Product::create([
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name),
                    'description' => $request->description,
                    'benefits'    => $request->benefits,
                    'gizi_path'   => $giziPath,
                    'brand_id'    => $request->brand
                ]);

                // 2. Loop variant
                foreach ($request->variants as $variantData) {

                    $flavour = Flavour::find($variantData['flavour']);
                    $name    = $product->name . ' - ' . $flavour->name;

                    $variant = $product->variants()->create([
                        'name'        => $name,
                        'slug'        => Str::slug($name),
                        'product_id'  => $product->id,
                        'flavour_id'  => $variantData['flavour'],
                        'category_id' => $variantData['category'],
                        'sku'         => $variantData['sku'],
                    ]);

                    // 3. Sizes
                    foreach ($variantData['sizes'] as $sizeData) {
                        $variant->sizes()->create([
                            'size_id'  => $sizeData['id'],
                            'stock' => $sizeData['stock'],
                            'price' => $sizeData['price'],
                            'discount' => $sizeData['discount'] ?? 0,
                            'price_after_discount' => $sizeData['discount'] != null ? $sizeData['real_price'] : null,
                        ]);
                    }

                    // 4. Images
                    foreach ($variantData['images'] as $imageFile) {
                        $path = $imageFile->store('products', 'public');

                        $variant->images()->create([
                            'image_path' => $path,
                        ]);
                    }
                }
            });

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('products.index')->with('error', 'Failed to create product.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $flavours = Flavour::all();
        $categories = Category::all();
        $sizes = Size::all();
        $brands = Brand::all();
        return view('products.edit', compact('product', 'flavours', 'categories', 'sizes', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {

            DB::transaction(function () use ($request, $product) {

                // 1. Update gizi image
                if ($request->hasFile('gizi_path')) {
                    if ($product->gizi_path) {
                        Storage::disk('public')->delete($product->gizi_path);
                    }
                    $product->gizi_path = $request->file('gizi_path')->store('gizi', 'public');
                }

                // 2. Update produk utama
                $product->update([
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name),
                    'description' => $request->description,
                    'benefits'    => $request->benefits,
                    'brand_id'    => $request->brand
                ]);

                $variantIds = [];

                foreach ($request->variants as $variantData) {

                    $flavour = Flavour::findOrFail($variantData['flavour']);
                    $name = $product->name . ' - ' . $flavour->name;

                    // =========================
                    // UPDATE / CREATE VARIANT
                    // =========================
                    if (isset($variantData['id'])) {
                        // UPDATE
                        $variant = $product->variants()
                            ->where('id', $variantData['id'])
                            ->firstOrFail();

                        $variant->update([
                            'name'        => $name,
                            'slug'        => Str::slug($name),
                            'category_id' => $variantData['category'],
                            'flavour_id'  => $variantData['flavour'],
                            'sku'         => $variantData['sku'],
                        ]);
                    } else {
                        // CREATE
                        $variant = $product->variants()->create([
                            'name'        => $name,
                            'slug'        => Str::slug($name),
                            'category_id' => $variantData['category'],
                            'flavour_id'  => $variantData['flavour'],
                            'sku'         => $variantData['sku'],
                        ]);
                    }

                    $variantIds[] = $variant->id;

                    // =========================
                    // SIZES
                    // =========================
                    $sizeIds = [];
                    if (isset($variantData['sizes']) && is_array($variantData['sizes'])) {
                        foreach ($variantData['sizes'] as $sizeData) {

                            $size = $variant->sizes()->updateOrCreate(
                                ['size_id' => $sizeData['id']],
                                [
                                    'stock' => $sizeData['stock'],
                                    'price' => $sizeData['price'],
                                    'discount' => $sizeData['discount'] ?? 0,
                                    'price_after_discount' => $sizeData['discount'] != null ? $sizeData['real_price'] : null,
                                ]
                            );

                            $sizeIds[] = $size->id;
                        }

                        $variant->sizes()->whereNotIn('id', $sizeIds)->delete();
                    }

                    // =========================
                    // IMAGES
                    // =========================
                    if (isset($variantData['images']) && is_array($variantData['images'])) {

                        $variant->images->each(function ($img) {
                            Storage::disk('public')->delete($img->image_path);
                            $img->delete();
                        });

                        foreach ($variantData['images'] as $imageFile) {
                            $path = $imageFile->store('products', 'public');
                            $variant->images()->create(['image_path' => $path]);
                        }
                    }
                }

                // 4. Hapus variant lama
                $product->variants()->whereNotIn('id', $variantIds)->delete();
            });

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Throwable $th) {

            Log::error('FAILED UPDATE PRODUCT', [
                'product_id' => $product->id,
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);

            // Mapping error ke bahasa user
            if (str_contains($th->getMessage(), 'variants_sku_unique')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'SKU sudah digunakan. Silakan gunakan SKU lain.');
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan produk. Periksa data variant.');
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    /**
     * Toggle the status of a variant.
     */
    public function toggleStatus(Variant $variant)
    {
        $variant->status = $variant->status == 'active' ? 'inactive' : 'active';
        $variant->save();

        return redirect()->back()->with('success', 'Variant status updated successfully.');
    }
}
