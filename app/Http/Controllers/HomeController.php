<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clients = Client::all();
        $expiring_soon = Client::where('expiration',Carbon::tomorrow('Asia/Dhaka'))->count();
        $expired_today = Client::where('expiration',Carbon::today('Asia/Dhaka'))->count();
        $expired_this_month = Client::where('status','expired')->whereYear('expiration', date('Y'))->whereMonth('expiration', date('m'))->count();

        $package = Package::pluck('price','title');

        $clients_by_package = DB::table('clients')
        ->where('status','Registered')
                 ->select('package', DB::raw('count(*) as total'))
                 ->groupBy('package')
                 ->get();
        $registered_clients = DB::table('clients')
                                    ->where('status','Registered')->count();
              //   dd($registered_clients);
        return view('home', compact('clients','clients_by_package','package','registered_clients','expiring_soon','expired_today','expired_this_month'));
    }
}
