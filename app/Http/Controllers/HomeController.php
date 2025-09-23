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

        $expiring_soon = Client::where('expiration', $tomorrow);
        $expired_today = Client::where('expiration', $today);
        $expired_this_month = Client::where('status', 'expired')
            ->whereYear('expiration', date('Y'))
            ->whereMonth('expiration', date('m'));

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

        return view('home', compact(
            'clients',
            'clients_by_package',
            'package',
            'registered_clients',
            'expiring_soon',
            'expired_today',
            'expired_this_month',
            'lastUpdated',
            'expiredPostpaidClients',
            'freeOnuExpiredClients',
            'expiredHotspotClients'
        ));
    }
}
