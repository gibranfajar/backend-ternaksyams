<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        /** =======================
         *  MASTER DATA
         *  ======================= */
        $categories = ['Goata', 'Goatlyf', 'Gowegen'];
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $flavours = ['Original', 'Coklat', 'Strawberry'];
        foreach ($flavours as $flavour) {
            DB::table('flavours')->insert([
                'name' => $flavour,
                'slug' => Str::slug($flavour),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $sizes = ['250', '500', '1000'];
        foreach ($sizes as $size) {
            DB::table('sizes')->insert([
                'label' => $size,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::transaction(function () {

            /** =======================
             *  PRODUCT
             *  ======================= */
            $productId = DB::table('products')->insertGetId([
                'name'        => 'Goata Milk',
                'slug'        => Str::slug('Goata Milk'),
                'gizi_path'   => 'products/gizi/goata-milk.png',
                'description' => 'Susu kambing premium berkualitas tinggi.',
                'benefits'    => 'Baik untuk pencernaan dan daya tahan tubuh.',
                'brand_id'    => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            /** =======================
             *  VARIANTS (3 RASA)
             *  ======================= */
            $variants = [
                [
                    'name' => 'Goata Milk Original',
                    'flavour_id' => 1,
                    'sku' => 'GOATA-ORI-001',
                    'images' => ['goata-ori-1.png', 'goata-ori-2.png'],
                ],
                [
                    'name' => 'Goata Milk Coklat',
                    'flavour_id' => 2,
                    'sku' => 'GOATA-CHO-001',
                    'images' => ['goata-coklat-1.png'],
                ],
                [
                    'name' => 'Goata Milk Strawberry',
                    'flavour_id' => 3,
                    'sku' => 'GOATA-STR-001',
                    'images' => ['goata-strawberry-1.png'],
                ],
            ];

            foreach ($variants as $variant) {

                $variantId = DB::table('variants')->insertGetId([
                    'name'        => $variant['name'],
                    'slug'        => Str::slug($variant['name']),
                    'product_id'  => $productId,
                    'flavour_id'  => $variant['flavour_id'],
                    'category_id' => 1,
                    'sku'         => $variant['sku'],
                    'status'      => 'active',
                    'view'        => 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                /** =======================
                 *  VARIANT IMAGES
                 *  ======================= */
                foreach ($variant['images'] as $i => $image) {
                    DB::table('variant_images')->insert([
                        'variant_id' => $variantId,
                        'image_path' => 'products/variants/' . $image,
                        'sort'       => $i + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                /** =======================
                 *  VARIANT SIZES
                 *  ======================= */
                DB::table('variant_sizes')->insert([
                    [
                        'variant_id' => $variantId,
                        'size_id' => 1,
                        'stock' => 100,
                        'price' => 150000,
                        'type_disc' => 'percent',
                        'discount' => 10,
                        'price_after_discount' => 135000,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'variant_id' => $variantId,
                        'size_id' => 2,
                        'stock' => 50,
                        'price' => 250000,
                        'type_disc' => 'value',
                        'discount' => 20,
                        'price_after_discount' => 230000,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        });
    }
}
