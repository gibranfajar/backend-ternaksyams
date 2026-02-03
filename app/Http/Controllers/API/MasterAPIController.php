<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\BenefitResource;
use App\Http\Resources\FlashSaleDetailResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\PromotionResource;
use App\Http\Resources\TutorialResource;
use App\Http\Resources\FlashSaleProductResource;
use App\Models\About;
use App\Models\Affiliator;
use App\Models\Article;
use App\Models\Benefit;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Faq;
use App\Models\FlashSaleItem;
use App\Models\Footer;
use App\Models\PricelistReseller;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Reseller;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\Tutorial;
use App\Models\User;
use App\Models\Variant;
use App\Models\VideoPlayer;
use App\Models\Voucher;
use App\Models\VoucherProduct;
use App\Models\VoucherUsage;
use App\Models\VoucherUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MasterAPIController extends Controller
{

    protected $baseUrlRajaongkir;

    public function __construct()
    {
        $this->baseUrlRajaongkir = env('RAJAONGKIR_URL');
    }

    /**
     * Get User Info Authentication
     */
    public function getUser()
    {
        $user = Auth::user();
        $data = User::with('profile')->where('id', $user->id)->first();

        $json = [
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'email_verified_at' => $data->email_verified_at,
            'role' => $data->role,
            'google_id' => $data->google_id,
            'whatsapp' => $data->profile?->whatsapp,
            'address' => $data->address,
            'province' => $data->profile?->province,
            'city' => $data->profile?->city,
            'district' => $data->profile?->district,
            'postal_code' => $data->profile?->postal_code,
        ];

        return response()->json($json, 200);
    }


    /*
     * Create Resellers Account
     */
    public function createReseller(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'whatsapp' => 'required',
            'email' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'postal_code' => 'required',
            'bank' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ]);

        try {
            $reseller = Reseller::create($request->all());
            return response()->json(['message' => 'Reseller created successfully', 'reseller' => $reseller], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create reseller', 'error' => $e], 500);
        }
    }

    /**
     * Get All Resellers
     */
    public function getResellers()
    {
        $resellers = Reseller::where('status', 'approved')->get();

        /*
    |--------------------------------------------------------------------------
    | GET PROVINCES (CACHE 1 DAY)
    |--------------------------------------------------------------------------
    */
        try {
            $provinces = Cache::remember('rajaongkir_provinces', 86400, function () {
                return Http::withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY')
                ])->timeout(10)
                    ->get("{$this->baseUrlRajaongkir}/api/v1/destination/province")
                    ->json('data');
            });
        } catch (\Exception $e) {
            Log::error('Provincia API error: ' . $e->getMessage());
            $provinces = [];
        }

        $provinceMap = collect($provinces)->pluck('name', 'id');

        /*
    |--------------------------------------------------------------------------
    | GET CITIES PER PROVINCE (CACHE)
    |--------------------------------------------------------------------------
    */
        $cityMap = [];
        $groupedByProvince = $resellers->groupBy('province_id');

        foreach ($groupedByProvince as $provinceId => $items) {
            try {
                $cities = Cache::remember("rajaongkir_cities_$provinceId", 86400, function () use ($provinceId) {
                    return Http::withHeaders([
                        'key' => env('RAJAONGKIR_API_KEY')
                    ])->timeout(10)
                        ->get("{$this->baseUrlRajaongkir}/api/v1/destination/city/$provinceId")
                        ->json('data');
                });

                foreach ($cities as $city) {
                    $cityMap[$city['id']] = $city['name'];
                }
            } catch (\Exception $e) {
                Log::error("City API error for province $provinceId: " . $e->getMessage());
            }
        }

        /*
    |--------------------------------------------------------------------------
    | GET DISTRICTS PER CITY (CACHE)
    |--------------------------------------------------------------------------
    */
        $districtMap = [];
        $groupedByCity = $resellers->groupBy('city_id');

        foreach ($groupedByCity as $cityId => $items) {
            try {
                $districts = Cache::remember("rajaongkir_districts_$cityId", 86400, function () use ($cityId) {
                    return Http::withHeaders([
                        'key' => env('RAJAONGKIR_API_KEY')
                    ])->timeout(10)
                        ->get("{$this->baseUrlRajaongkir}/api/v1/destination/district/$cityId")
                        ->json('data');
                });

                foreach ($districts as $district) {
                    $districtMap[$district['id']] = $district['name'];
                }
            } catch (\Exception $e) {
                Log::error("District API error for city $cityId: " . $e->getMessage());
            }
        }

        /*
    |--------------------------------------------------------------------------
    | INJECT FINAL RESPONSE
    |--------------------------------------------------------------------------
    */
        $resellers = $resellers->map(function ($item) use ($provinceMap, $cityMap, $districtMap) {

            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'whatsapp' => $item->whatsapp,
                'address' => $item->address,

                'province_id' => $item->province_id,
                'province_name' => $provinceMap[$item->province_id] ?? 'UNKNOWN',

                'city_id' => $item->city_id,
                'city_name' => $cityMap[$item->city_id] ?? 'UNKNOWN',

                'district_id' => $item->district_id,
                'district_name' => $districtMap[$item->district_id] ?? 'UNKNOWN',

                'postal_code' => $item->postal_code,
                'bank' => $item->bank,
                'account_number' => $item->account_number,
                'account_name' => $item->account_name,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'data' => $resellers
        ], 200);
    }

    /**
     * Pricelist Resellers
     */
    public function getPricelistResellers()
    {
        $priceListResellers = PricelistReseller::all();

        return response()->json([
            'data' => $priceListResellers
        ], 200);
    }


    /*
     * Create Affiliate Account
     */
    public function createAffiliate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'whatsapp' => 'required',
            'email' => 'required',
            'province' => 'required',
            'city' => 'required',
            'sosmed_account' => 'nullable',
            'shopee_account' => 'nullable',
            'tokopedia_account' => 'nullable',
            'tiktok_account' => 'nullable',
            'lazada_account' => 'nullable',
        ]);

        try {
            $affiliate = Affiliator::create($request->all());
            return response()->json(['message' => 'Affiliate created successfully', 'affiliate' => $affiliate], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create affiliate'], 500);
        }
    }


    /**
     * Get All Affiliates
     */
    public function getAffiliates()
    {
        $affiliates = Affiliator::all();
        return response()->json(['data' => $affiliates], 200);
    }


    /**
     * Hardsellings
     */
    public function hardsellings()
    {
        $hardsellings = DB::table('hardsellings')->get();
        $hardsellingCta = DB::table('hardselling_ctas')->first();
        $hardsellingFooter = DB::table('hardselling_footers')->first();

        $data = [
            'hardsellings' => $hardsellings,
            'hardsellingCta' => $hardsellingCta,
            'hardsellingFooter' => $hardsellingFooter,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * Get All Brands
     */
    public function brands()
    {
        $brands = Brand::with('sizes', 'variants')->get();
        return response()->json(['data' => $brands], 200);
    }

    /**
     * Get Detail Brand
     */
    public function detailBrand($slug)
    {
        $brand = Brand::with('sizes', 'variants', 'detail', 'sliders', 'testimonial', 'feature', 'productsidebar', 'about', 'howitwork', 'testimonialBrands')->where('slug', $slug)->first();
        return response()->json(['data' => $brand], 200);
    }

    /**
     * Get Variant All Brand
     */
    public function variantAllBrand()
    {
        $variants = \App\Models\BrandVariant::with('brand:id,brand,description')->get();

        $data = $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'brand' => $variant->brand->brand ?? null,
                'variant' => $variant->variant,
                'image' => asset('storage/' . $variant->image),
                'description' => $variant->description,
                'created_at' => $variant->created_at,
                'updated_at' => $variant->updated_at,
            ];
        });

        return response()->json(['data' => $data], 200);
    }


    /*
     * Get All Categories
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json(['data' => $categories], 200);
    }

    /*
     * Get All Products
     */
    public function products()
    {
        $variants = Variant::with(['sizes.size', 'product.brand', 'category', 'images'])
            ->whereDoesntHave('flashSaleItems')
            ->orderBy('id', 'desc')
            ->get();

        $data = ProductResource::collection($variants);

        return response()->json(['data' => $data], 200);
    }


    /*
     * Get Detail Product
     */
    public function detailProduct($slug)
    {
        $variants = Variant::with(['sizes', 'flavour', 'product'])->where('slug', $slug)->whereDoesntHave('flashSaleItems')->get();
        $data = ProductDetailResource::collection($variants);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Get All Variants
     */
    public function variants()
    {
        $products = Product::with('variants.sizes.size', 'variants.images')
            ->orderBy('id', 'desc')
            ->get();

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'description' => $product->description,
                'image' => $product->variants->first()?->images->first()
                    ? asset('storage/' . $product->variants->first()->images->first()->image_path)
                    : null, // thumbnail utama dari variant pertama
                'sizes' => $product->variants->first()?->sizes->map(function ($vs) {
                    return $vs->size?->label . ' ' . $vs->size?->unit;
                }),
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id'    => $variant->id,
                        'name'  => $variant->name,
                        'image' => $variant->images->first()
                            ? asset('storage/' . $variant->images->first()->image_path)
                            : null
                    ];
                }),
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    /**
     * Get ALl Flash Sales Products
     */
    public function flashSaleProducts()
    {
        $now = now();

        $items = FlashSaleItem::with([
            'variant.product.brand',
            'variant.category',
            'variant.images',
            'variantSize.size',
            'flashSale',
        ])
            ->whereHas('flashSale', function ($q) use ($now) {
                $q->where('status', 'ongoing')
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            })
            ->get()

            // 🔥 GROUP PER VARIANT
            ->groupBy('variant_id')

            // 🔥 AMBIL YANG PALING MURAH
            ->map(function ($group) {
                return $group->sortBy('flashsale_price')->first();
            })

            ->values(); // reset index

        return response()->json([
            'data' => FlashsaleProductResource::collection($items)
        ]);
    }


    /**
     * Get Detail Flash Sale Product
     */
    public function detailFlashSaleProduct($slug)
    {
        $variants = Variant::with([
            'sizes.size',
            'flavour',
            'product.brand',
            'product',
            'flashSaleItems.flashSale',
        ])
            ->where('slug', $slug)
            ->get();

        if ($variants->isEmpty()) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'data' => FlashSaleDetailResource::collection($variants)
        ], 200);
    }

    /*
     * Get All Articles
     */
    public function articles()
    {
        $articles = Article::with('category')->orderBy('id', 'desc')->get();
        $data = ArticleResource::collection($articles);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Get Detail Article
     */
    public function detailArticle($slug)
    {
        $article = Article::with('category')->where('slug', $slug)->first();
        $data = new ArticleResource($article);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Get All Tutorials
     */
    public function tutorials()
    {
        $tutorials = Tutorial::with('category')->orderBy('id', 'desc')->get();
        $data = TutorialResource::collection($tutorials);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Get Benefits Resellers
     */
    public function benefitResellers()
    {
        $benefits = Benefit::where('type', 'reseller')->get();
        $data = BenefitResource::collection($benefits);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Get Benefits Affiliates
     */
    public function benefitAffiliates()
    {
        $benefits = Benefit::where('type', 'affiliate')->get();
        $data = BenefitResource::collection($benefits);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Get Promotions
     */
    public function promotions()
    {
        $promotions = Promotion::orderBy('id', 'desc')->get();
        $data = PromotionResource::collection($promotions);

        return response()->json(['data' => $data], 200);
    }

    /*
     * Apply Voucher
     */
    public function applyVoucher(Request $request)
    {
        $voucher = Voucher::where('code', $request->code)->first();

        // 1. Cek voucher ada
        if (!$voucher) {
            return response()->json([
                'message' => 'Voucher not found',
            ], 404);
        }

        // 2. Cek status & tanggal berlaku
        if ($voucher->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Voucher is not active',
            ], 400);
        }

        // cek apakah kuota masih tersedia dari count voucher usage berdasarkan voucher_id
        $countVoucherUsage = VoucherUsage::where('voucher_id', $voucher->id)->count();
        if ($countVoucherUsage >= $voucher->quota) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher quota is full',
            ], 400);
        }

        // 3. Cek target user
        if ($voucher->target !== 'all') {

            // voucher users
            $voucherUsers = $voucher->users()->where('user_id', $request->user_id)->first();

            if (!$voucherUsers) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher is not for you',
                ], 400);
            }
        }

        // 4. Cek minimum belanja (kalau ada di request total belanja)
        if ($voucher->min_transaction_value && $request->total < $voucher->min_transaction_value) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount is ' . $voucher->min_transaction_value,
            ], 400);
        }

        // 5. Cek kuota
        $limit = VoucherUsage::where('voucher_id', $voucher->id)->where('user_id', $request->user_id)->where('session', $request->session)->count();
        if ($limit >= $voucher->limit) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher usage limit reached',
            ], 400);
        }


        // 6. Voucher untuk product
        if ($voucher->type === 'product') {
            $found = false;

            foreach ($request->product as $item) {
                $voucherProduct = VoucherProduct::where('variantsize_id', $item['id'])
                    ->where('voucher_id', $voucher->id)
                    ->first();
                if ($voucherProduct) {
                    $found = true;
                    break; // cukup 1 product cocok
                }
            }

            if (!$found) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in voucher',
                ], 400);
            }
        }


        // Jika semua valid
        return response()->json([
            'success' => true,
            'message' => 'Voucher applied successfully',
            'data'    => [
                'voucher_type'  => $voucher->type,
                'discount_type'  => $voucher->amount_type,
                'discount_value' => $voucher->amount,
                'max_discount'   => $voucher->max_value,
                'min_transaction' => $voucher->min_transaction_value
            ],
        ], 200);
    }

    public function abouts()
    {
        $about = About::with([
            'partnerSection',
            'whyUsFeatures',
            'profileSection'
        ])->first();

        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'Data About belum tersedia.'
            ], 404);
        }

        return response()->json($about, 200);
    }

    // get voucher users
    public function myVouchers()
    {
        $user = Auth::user();

        // pastikan user login
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.'
            ], 401);
        }

        // ambil semua voucher yang dimiliki user + relasi voucher dan user
        $vouchers = VoucherUser::with(['voucher', 'user'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json($vouchers, 200);
    }

    // get faqs
    public function getFaqs()
    {
        $faqs = Faq::with(['category'])->get();
        return response()->json($faqs, 200);
    }

    // get slider
    public function getSliders()
    {
        $sliders = Slider::where('status', true)->get();
        return response()->json($sliders, 200);
    }

    // get footers
    public function getFooters()
    {
        $footers = Footer::with(['informations', 'etawas'])->get();
        return response()->json($footers, 200);
    }

    // get video players
    public function getVideoPlayers()
    {
        $videoPlayers = VideoPlayer::first();
        return response()->json($videoPlayers, 200);
    }

    // get testimonials
    public function getTestimonials()
    {
        $testimonials = Testimonial::where('status', true)->get();
        return response()->json($testimonials, 200);
    }
}
