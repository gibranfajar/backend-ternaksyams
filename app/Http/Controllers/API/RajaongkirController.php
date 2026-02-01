<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RajaongkirController extends Controller
{
    private string $baseUrl = 'https://rajaongkir.komerce.id/api/v1';

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
                    ->get("{$this->baseUrl}/destination/province")
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
                    ->get("{$this->baseUrl}/destination/city/$id")
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
                    ->get("{$this->baseUrl}/destination/district/$id")
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
                    ->get("{$this->baseUrl}/destination/sub-district/$id")
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
    public function calculateCost($destination, $weight, $courier)
    {
        try {
            $response = Http::asForm()
                ->timeout(15)
                ->withHeaders([
                    'accept' => 'application/json',
                    'key' => env('RAJAONGKIR_API_KEY'),
                ])->post("{$this->baseUrl}/calculate/district/domestic-cost", [
                    'origin' => 2163,
                    'destination' => $destination,
                    'weight' => $weight,
                    'courier' => $courier
                ]);

            return response()->json([
                'data' => $response->json('data')
            ]);
        } catch (\Exception $e) {
            Log::error("Cost API error: " . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menghitung ongkir'
            ], 500);
        }
    }
}
