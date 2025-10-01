<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Package;
use App\Models\HotspotClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = Carbon::today('Asia/Dhaka');
        $tomorrow = Carbon::tomorrow('Asia/Dhaka');

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
            ->join('packages', 'clients.package', '=', 'packages.title')
            ->where('clients.status', 'Registered')
            ->select(
                'clients.package',
                DB::raw('count(*) as total_clients'),
                DB::raw('sum(packages.price) as total_amount')
            )
            ->groupBy('clients.package')
            ->get();

        $registered_clients = DB::table('clients')
            ->where('status','Registered')->count();

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

        return view('home', [
            'clients' => $clients,
            'clients_by_package' => $clients_by_package,
            'package' => $package,
            'registered_clients' => $registered_clients,
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
        ]);
    }
}
