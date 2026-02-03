<?php

namespace App\Http\Controllers;

use App\Mail\ResellerApprovedMail;
use App\Models\Affiliator;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    protected $baseUrlRajaongkir;

    public function __construct()
    {
        $this->baseUrlRajaongkir = env('RAJAONGKIR_URL');
    }

    public function index()
    {
        $types = [
            ['id' => 1, 'type' => 'reseller'],
            ['id' => 2, 'type' => 'affiliate'],
        ];

        $resellers = Reseller::orderBy('id', 'desc')->get();
        $affiliates = Affiliator::orderBy('id', 'desc')->get();

        /*
    |--------------------------------------------------------------------------
    | PROVINCE
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
            Log::error('Province API error: ' . $e->getMessage());
            $provinces = [];
        }

        $provinceMap = collect($provinces)->pluck('name', 'id');

        /*
    |--------------------------------------------------------------------------
    | CITY
    |--------------------------------------------------------------------------
    */
        $cityMap = [];
        foreach ($resellers->groupBy('province_id') as $provinceId => $items) {
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
                Log::error("City API error ($provinceId): " . $e->getMessage());
            }
        }

        /*
    |--------------------------------------------------------------------------
    | DISTRICT
    |--------------------------------------------------------------------------
    */
        $districtMap = [];
        foreach ($resellers->groupBy('city_id') as $cityId => $items) {
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
                Log::error("District API error ($cityId): " . $e->getMessage());
            }
        }

        /*
    |--------------------------------------------------------------------------
    | INJECT NAME
    |--------------------------------------------------------------------------
    */
        $resellers = $resellers->map(function ($item) use ($provinceMap, $cityMap, $districtMap) {

            $item->province_name = $provinceMap[$item->province_id] ?? 'UNKNOWN';
            $item->city_name = $cityMap[$item->city_id] ?? 'UNKNOWN';
            $item->district_name = $districtMap[$item->district_id] ?? 'UNKNOWN';

            return $item;
        });

        return view('partners.index', compact('types', 'resellers', 'affiliates'));
    }

    public function update(Request $request, $id)
    {
        $reseller = Reseller::findOrFail($id);

        // ================= VALIDATION =================
        $request->validate([
            'name'            => 'required|string|max:255',
            'whatsapp'        => 'required|string|max:20|unique:resellers,whatsapp,' . $id,
            'email'           => 'required|email|unique:resellers,email,' . $id,
            'address'         => 'required|string',
            'province_id'     => 'required|integer',
            'city_id'         => 'required|integer',
            'district_id'     => 'required|integer',
            'postal_code'     => 'required|string|max:10',
            'bank'            => 'required|string|max:100',
            'account_number'  => 'required|string|unique:resellers,account_number,' . $id,
            'account_name'    => 'required|string|max:255',
            'status'          => 'required|in:pending,approved,rejected,suspended,inactive',
        ]);

        // ================= UPDATE DATA =================
        $reseller->update([
            'name'           => $request->name,
            'whatsapp'       => $request->whatsapp,
            'email'          => $request->email,
            'address'        => $request->address,
            'province_id'    => $request->province_id,
            'city_id'        => $request->city_id,
            'district_id'    => $request->district_id,
            'postal_code'    => $request->postal_code,
            'bank'           => $request->bank,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'status'         => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data reseller berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,suspended,inactive,rejected'
        ]);

        $reseller = Reseller::findOrFail($id);

        $oldStatus = $reseller->status;

        $reseller->update([
            'status' => $request->status
        ]);

        // 🔔 Kirim email HANYA jika berubah ke approved
        if ($oldStatus !== 'approved' && $request->status === 'approved') {
            Mail::to($reseller->email)->send(
                new ResellerApprovedMail($reseller)
            );
        }

        return redirect()
            ->route('partners.index')
            ->with('success', 'Status reseller berhasil diperbarui.');
    }
}
