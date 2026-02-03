<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    protected $baseUrlRajaongkir;

    public function __construct()
    {
        $this->baseUrlRajaongkir = env('RAJAONGKIR_URL');
    }

    private function growthPercentage($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboardChart()
    {
        /* ================= WEEK (7 DAYS) ================= */
        $weekLabels = [];
        $weekVisitors = [];
        $weekUsers = [];
        $weekOrders = [];
        $weekSales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $weekLabels[] = Carbon::parse($date)->format('D');

            // Unique Visitors
            // $weekVisitors[] = PageView::whereDate('created_at', $date)
            //     ->distinct('session_id')
            //     ->count();

            // Users
            $weekUsers[] = User::where('role', '!=', 'admin')->whereDate('created_at', $date)->count();

            // Orders
            $weekOrders[] = Order::whereHas(
                'payment',
                fn($q) =>
                $q->where('status', 'settlement')->whereDate('created_at', $date)
            )->count();

            // Sales
            $weekSales[] = Order::whereHas(
                'payment',
                fn($q) =>
                $q->where('status', 'settlement')->whereDate('created_at', $date)
            )->sum('total');
        }

        /* ================= MONTH (12 MONTHS) ================= */
        $monthLabels = [];
        $monthVisitors = [];
        $monthUsers = [];
        $monthOrders = [];
        $monthSales = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);

            $monthLabels[] = $month->format('M');

            // $monthVisitors[] = PageView::whereYear('created_at', $month->year)
            //     ->whereMonth('created_at', $month->month)
            //     ->distinct('session_id')
            //     ->count();

            $monthUsers[] = User::where('role', '!=', 'admin')->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $monthOrders[] = Order::whereHas(
                'payment',
                fn($q) =>
                $q->where('status', 'settlement')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
            )->count();

            $monthSales[] = Order::whereHas(
                'payment',
                fn($q) =>
                $q->where('status', 'settlement')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
            )->sum('total');
        }

        return response()->json([
            'week' => [
                'labels' => $weekLabels,
                // 'visitors' => $weekVisitors,
                'users' => $weekUsers,
                'orders' => $weekOrders,
                'sales' => $weekSales,
            ],
            'month' => [
                'labels' => $monthLabels,
                // 'visitors' => $monthVisitors,
                'users' => $monthUsers,
                'orders' => $monthOrders,
                'sales' => $monthSales,
            ],
        ]);
    }

    public function incomeOverview()
    {
        $startWeek = now()->startOfWeek();
        $endWeek   = now()->endOfWeek();

        // SALES (paid)
        $sales = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('status', 'settlement')
                ->whereBetween('created_at', [$startWeek, $endWeek])
        )->sum('total');

        // ORDERS
        $orders = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('status', 'settlement')
                ->whereBetween('created_at', [$startWeek, $endWeek])
        )->count();

        // USERS
        $users = User::where('role', '!=', 'admin')->whereBetween('created_at', [$startWeek, $endWeek])->count();

        // UNIQUE VISITORS
        // $visitors = PageView::whereBetween('created_at', [$startWeek, $endWeek])
        //     ->distinct('session_id')
        //     ->count();

        return response()->json([
            'total' => $sales,
            'labels' => ['Sales', 'Orders', 'Users'],
            'series' => [
                (float) $sales,
                (float) $orders,
                (float) $users,
                // (float) $visitors
            ]
        ]);
    }

    public function dashboard()
    {
        $year = now()->year;
        $lastYear = now()->year - 1;

        /* ================= PAGE VIEWS ================= */
        // $pageViewsNow  = PageView::whereYear('created_at', $year)->count();
        // $pageViewsLast = PageView::whereYear('created_at', $lastYear)->count();

        // $pageViewsGrowth = $this->growthPercentage($pageViewsNow, $pageViewsLast);
        // $pageViewsExtra  = $pageViewsNow - $pageViewsLast;

        /* ================= USERS ================= */
        $usersNow  = User::where('role', '!=', 'admin')->whereYear('created_at', $year)->count();
        $usersLast = User::where('role', '!=', 'admin')->whereYear('created_at', $lastYear)->count();

        $usersGrowth = $this->growthPercentage($usersNow, $usersLast);
        $usersExtra  = $usersNow - $usersLast;

        /* ================= ORDERS ================= */
        $ordersNow = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('status', 'settlement')->whereYear('created_at', $year)
        )->count();

        $ordersLast = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('status', 'settlement')->whereYear('created_at', $lastYear)
        )->count();

        $ordersGrowth = $this->growthPercentage($ordersNow, $ordersLast);
        $ordersExtra  = $ordersNow - $ordersLast;

        /* ================= SALES ================= */
        $salesNow = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('status', 'settlement')->whereYear('created_at', $year)
        )->sum('total');

        $salesLast = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('status', 'settlement')->whereYear('created_at', $lastYear)
        )->sum('total');

        $salesGrowth = $this->growthPercentage($salesNow, $salesLast);
        $salesExtra  = $salesNow - $salesLast;


        $orders = Order::orderBy('id', 'desc')->limit(10)->get();

        return view('dashboard.index', compact(
            // 'pageViewsNow',
            // 'pageViewsGrowth',
            'usersNow',
            'usersGrowth',
            'usersExtra',
            'ordersNow',
            'ordersGrowth',
            'ordersExtra',
            'salesNow',
            'salesGrowth',
            'salesExtra',
            'orders'
        ));
    }

    public function settings()
    {
        $shipper = Shipper::first();

        if (!$shipper || !$shipper->province) {
            $shipper = new Shipper();
        }

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

        return view('dashboard.settings', compact('provinces', 'shipper'));
    }

    public function cities($provinceId)
    {
        return Cache::remember("rajaongkir_cities_$provinceId", 86400, function () use ($provinceId) {
            return Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get("{$this->baseUrlRajaongkir}/api/v1/destination/city/$provinceId")
                ->json('data');
        });
    }

    public function districts($cityId)
    {
        return Cache::remember("rajaongkir_districts_$cityId", 86400, function () use ($cityId) {
            return Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get("{$this->baseUrlRajaongkir}/api/v1/destination/district/$cityId")
                ->json('data');
        });
    }

    public function subdistricts($districtId)
    {
        return Cache::remember("rajaongkir_subdistricts_$districtId", 86400, function () use ($districtId) {
            return Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get("{$this->baseUrlRajaongkir}/api/v1/destination/sub-district/$districtId")
                ->json('data');
        });
    }

    public function updateShipper(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'shipper_name' => 'required|string|max:255',
            'shipper_phone' => 'required|string|max:20',
            'shipper_email' => 'required|email',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'shipper_address' => 'required|string',
        ]);

        try {
            $shipper = Shipper::first();

            if ($shipper) {
                $shipper->update($request->only([
                    'brand_name',
                    'shipper_name',
                    'shipper_phone',
                    'shipper_email',
                    'province',
                    'city',
                    'district',
                    'subdistrict',
                    'shipper_address',
                ]));
            } else {
                Shipper::create($request->only([
                    'brand_name',
                    'shipper_name',
                    'shipper_phone',
                    'shipper_email',
                    'province',
                    'city',
                    'district',
                    'subdistrict',
                    'shipper_address',
                ]));
            }

            return back()->with('success', 'Shipper address updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error: ' . $th->getMessage());
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'confirmed',
            'password_confirmation' => 'required_with:password',
        ]);

        $user = User::findOrFail($id);

        if ($request->password) {
            $user->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
        } else {
            $user->update([
                'name' => $request->name,
            ]);
        }

        return redirect()->route('settings')->with('success', 'Profile updated successfully.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
