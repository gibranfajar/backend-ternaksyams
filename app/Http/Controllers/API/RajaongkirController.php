<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shipper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class RajaongkirController extends Controller
{
    protected string $baseUrl;
    protected string $baseUrlKonship;

    public function __construct()
    {
        $this->baseUrl = env('RAJAONGKIR_URL');
        $this->baseUrlKonship = env('RAJAONGKIR_URL_KOMSHIP');
    }

    /**
     * List of provinces (CACHE)
     */
    public function provinces()
    {
        try {
            $data = Cache::remember('rajaongkir_provinces', 86400, function () {
                return Http::withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY')
                ])->timeout(10)
                    ->get("{$this->baseUrl}/api/v1/destination/province")
                    ->json('data');
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error('Province API error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil data provinsi'
            ], 500);
        }
    }

    /**
     * List of cities (CACHE per province)
     */
    public function cities($id)
    {
        try {
            $data = Cache::remember("rajaongkir_cities_$id", 86400, function () use ($id) {
                return Http::withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY')
                ])->timeout(10)
                    ->get("{$this->baseUrl}/api/v1/destination/city/$id")
                    ->json('data');
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error("City API error ($id): " . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil data kota'
            ], 500);
        }
    }

    /**
     * List of districts (CACHE per city)
     */
    public function districts($id)
    {
        try {
            $data = Cache::remember("rajaongkir_districts_$id", 86400, function () use ($id) {
                return Http::withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY')
                ])->timeout(10)
                    ->get("{$this->baseUrl}/api/v1/destination/district/$id")
                    ->json('data');
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error("District API error ($id): " . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil data kecamatan'
            ], 500);
        }
    }

    /**
     * List of subdistricts (CACHE per district)
     */
    public function subdistricts($id)
    {
        try {
            $data = Cache::remember("rajaongkir_subdistricts_$id", 86400, function () use ($id) {
                return Http::withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY')
                ])->timeout(10)
                    ->get("{$this->baseUrl}/api/v1/destination/sub-district/$id")
                    ->json('data');
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error("SubDistrict API error ($id): " . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil data kelurahan'
            ], 500);
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|numeric',       // gram
            'total_price' => 'required|integer',
            'courier' => 'nullable|string',
        ]);

        $shipper = Shipper::first();

        // convert gram → kg
        $weightKg = round($request->weight / 1000, 2);

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'x-api-key' => env('RAJAONGKIR_DELIVERY_API_KEY'),
            ])->get("{$this->baseUrlKonship}/tariff/api/v1/calculate", [
                'shipper_destination_id' => (int) $shipper->subdistrict,
                'receiver_destination_id' => (int) $request->destination,
                'weight' => $weightKg,
                'item_value' => (int) $request->total_price,
                'cod' => 'no',
            ]);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Gagal menghitung ongkir',
                    'error' => $response->json(),
                ], 400);
            }

            $data = $response->json('data.calculate_reguler') ?? [];

            // 🔥 FILTER COURIER (KUNCI JAWABAN)
            if ($request->filled('courier')) {
                $courier = strtoupper($request->courier);

                $data = collect($data)->filter(function ($item) use ($courier) {
                    return strtoupper($item['shipping_name']) === $courier;
                })->values();
            }

            return response()->json([
                'meta' => [
                    'destination' => (int) $request->destination,
                    'weight_gram' => (int) $request->weight,
                    'weight_kg' => $weightKg,
                    'total_price' => (int) $request->total_price,
                    'courier' => $request->courier,
                ],
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghitung ongkir',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
