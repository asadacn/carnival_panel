<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Package;
use App\Models\HotspotClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkMasterPassword(Request $request)
    {
        // Validation ensures password and revenue are provided
        $request->validate([
            'password' => 'required|string',
            'totalRevenue' => 'required|numeric',
        ]);

        $user = Auth::user();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password for your session account. Access denied.'
            ], 403);
        }

        // Verification successful: calculate and return the sensitive data
        $totalRevenue = (float) $request->input('totalRevenue');
        $commissionRate = 0.40; // 40%

        $commissionAmount = $totalRevenue * $commissionRate;
        $remainingAmount = $totalRevenue - $commissionAmount;

        return response()->json([
            'success' => true,
            'commissionAmount' => $commissionAmount,
            'remainingAmount' => $remainingAmount,
        ]);
    }

    public function index(Request $request)
    {
        $today = Carbon::today('Asia/Dhaka');
        $tomorrow = Carbon::tomorrow('Asia/Dhaka');

        // Check for manual refresh request
        if ($request->has('refresh')) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_data');
            // Redirect to clean URL
            return redirect()->route('dashboard');
        }

        // Cache the entire dashboard data array for 10 minutes
        $dashboardData = \Illuminate\Support\Facades\Cache::remember('dashboard_data', 600, function () use ($today, $tomorrow) {
            
            // As requested, keeping the full collections
            $clients = Client::all();
            $lastUpdated = Client::latest('updated_at')->value('updated_at');

            $expiring_soon = Client::where('expiration', $tomorrow)->get();
            $expired_today = Client::where('expiration', $today)->get();
            $expired_this_month = Client::where('status', 'Expired')
                ->whereYear('expiration', date('Y'))
                ->whereMonth('expiration', date('m'))
                ->get();

            $package = Package::pluck('price','title');

            $clients_by_package = DB::table('clients')
                ->join('packages', function ($join) {
                    $join->on('clients.package', '=', 'packages.title')
                         ->whereColumn('clients.isp_code', 'packages.isp_code');
                })
                ->where('clients.status', 'Active')
                ->select(
                    'clients.package',
                    DB::raw('count(*) as total_clients'),
                    DB::raw('sum(packages.price) as total_amount')
                )
                ->groupBy('clients.package')
                ->get();

            $total = $clients_by_package->sum('total_amount');

            $Active_clients = DB::table('clients')
                ->where('status','Active')->count();

            $expiredPostpaidClients = Client::where('billing_type', 'postpaid')
                ->whereDate('expiration', '>=', $today)
                ->whereDate('expiration', '<=', $tomorrow)
                ->get();

            $freeOnuExpiredClients = Client::where('onu_free', 1)
                ->where('onu_returned', 0)
                ->whereDate('expiration', '<', $today)
                ->get();

            // Hotspot expired clients
            $expiredHotspotClients = HotspotClient::where('expires_at', '<', $today)
                ->orderBy('expires_at', 'asc')
                ->get();

            $currentYear = now()->year;
            $previousYear = $currentYear - 1;

            // Current Year Monthly Expired Clients
            $currentYearData = Client::whereYear('expiration', $currentYear)
                ->where('status', 'Expired')
                ->select(DB::raw('MONTH(expiration) as month'), DB::raw('COUNT(*) as total'))
                ->groupBy(DB::raw('MONTH(expiration)'))
                ->pluck('total','month')
                ->toArray();

            // Previous Year Monthly Expired Clients
            $previousYearData = Client::whereYear('expiration', $previousYear)
                ->where('status', 'Expired')
                ->select(DB::raw('MONTH(expiration) as month'), DB::raw('COUNT(*) as total'))
                ->groupBy(DB::raw('MONTH(expiration)'))
                ->pluck('total','month')
                ->toArray();

            // Fill missing months with 0
            $months = range(1,12);
            $currentYearDataFilled = [];
            $previousYearDataFilled = [];

            foreach($months as $month){
                $currentYearDataFilled[$month] = $currentYearData[$month] ?? 0;
                $previousYearDataFilled[$month] = $previousYearData[$month] ?? 0;
            }

            return [
                'clients' => $clients,
                'clients_by_package' => $clients_by_package,
                'package' => $package,
                'Active_clients' => $Active_clients,
                'expiring_soon' => $expiring_soon,
                'expired_today' => $expired_today,
                'expired_this_month' => $expired_this_month,
                'expiredPostpaidClients' => $expiredPostpaidClients,
                'freeOnuExpiredClients' => $freeOnuExpiredClients,
                'expiredHotspotClients' => $expiredHotspotClients,
                'monthlyExpiresChartData' => [
                    'current' => array_values($currentYearDataFilled),
                    'previous' => array_values($previousYearDataFilled),
                ],
                'lastUpdated' => $lastUpdated ? Carbon::parse($lastUpdated)->format('d-m-Y H:i:s') : null,
                'total' => $total,
            ];
        });

        return view('home', $dashboardData);
    }
}
