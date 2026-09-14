<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Isp;
use App\Models\Package;
use App\Models\HotspotClient;
use App\Models\SMSLOG;
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
            'isp_code' => 'nullable|string|max:50',
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
        $requestedIspCode = $request->input('isp_code');
        $isp = Isp::forCode($requestedIspCode);
        $commissionPercentage = (float) ($isp?->commission_percentage ?? 40.00);
        $commissionRate = max(0, min(100, $commissionPercentage)) / 100;

        $commissionAmount = $totalRevenue * $commissionRate;
        $remainingAmount = $totalRevenue - $commissionAmount;

        return response()->json([
            'success' => true,
            'commissionAmount' => $commissionAmount,
            'remainingAmount' => $remainingAmount,
            'commissionPercentage' => $commissionPercentage,
        ]);
    }

    public function index(Request $request)
    {
        $today = Carbon::today('Asia/Dhaka');
        $tomorrow = Carbon::tomorrow('Asia/Dhaka');

        // Check for manual refresh request
        if ($request->has('refresh')) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_data');
            \Illuminate\Support\Facades\Cache::forget('sms_balance');
            return redirect()->route('dashboard');
        }

        $selectedIspCode = $request->input('isp_code');

        $dashboardData = \Illuminate\Support\Facades\Cache::remember('dashboard_data', 600, function () use ($today, $tomorrow, $selectedIspCode) {
            $clientQuery = Client::query();
            if ($selectedIspCode) {
                $clientQuery->where('isp_code', $selectedIspCode);
            }

            $clients = $clientQuery->get();
            $lastUpdated = $clientQuery->latest('updated_at')->value('updated_at');

            $expiring_soon = Client::query()->where('expiration', $tomorrow);
            $expired_today = Client::query()->where('expiration', $today);
            $expired_this_month = Client::query()->where('status', 'Expired')
                ->whereYear('expiration', date('Y'))
                ->whereMonth('expiration', date('m'));

            if ($selectedIspCode) {
                $expiring_soon->where('isp_code', $selectedIspCode);
                $expired_today->where('isp_code', $selectedIspCode);
                $expired_this_month->where('isp_code', $selectedIspCode);
            }

            $expiring_soon = $expiring_soon->get();
            $expired_today = $expired_today->get();
            $expired_this_month = $expired_this_month->get();

            $package = Package::pluck('price', 'title');

            $clientsByPackageQuery = DB::table('clients')
                ->join('packages', function ($join) {
                    $join->on('clients.package', '=', 'packages.title')
                         ->whereColumn('clients.isp_code', 'packages.isp_code');
                })
                ->where('clients.status', 'Active');

            if ($selectedIspCode) {
                $clientsByPackageQuery->where('clients.isp_code', $selectedIspCode);
            }

            $clients_by_package = $clientsByPackageQuery
                ->select(
                    'clients.package',
                    DB::raw('count(*) as total_clients'),
                    DB::raw('sum(packages.price) as total_amount')
                )
                ->groupBy('clients.package')
                ->get();

            $revenueByIspQuery = DB::table('clients')
                ->join('packages', function ($join) {
                    $join->on('clients.package', '=', 'packages.title')
                         ->whereColumn('clients.isp_code', 'packages.isp_code');
                })
                ->join('isps', 'clients.isp_code', '=', 'isps.isp_code')
                ->where('clients.status', 'Active');

            if ($selectedIspCode) {
                $revenueByIspQuery->where('clients.isp_code', $selectedIspCode);
            }

            $revenue_by_isp = $revenueByIspQuery
                ->select(
                    'clients.isp_code',
                    'isps.isp_name',
                    'isps.commission_percentage',
                    DB::raw('sum(packages.price) as total_amount'),
                    DB::raw('count(*) as total_clients')
                )
                ->groupBy('clients.isp_code', 'isps.isp_name', 'isps.commission_percentage')
                ->orderBy('total_amount', 'desc')
                ->get()
                ->map(function ($row) {
                    $commissionPct = (float) ($row->commission_percentage ?? 40.00);
                    $commissionPct = max(0, min(100, $commissionPct));
                    $row->commission_amount = (float) $row->total_amount * ($commissionPct / 100);
                    return $row;
                });

            $total = $clients_by_package->sum('total_amount');

            $Active_clients = DB::table('clients')
                ->where('status', 'Active');

            if ($selectedIspCode) {
                $Active_clients->where('isp_code', $selectedIspCode);
            }

            $Active_clients = $Active_clients->count();

            $expiredPostpaidClients = Client::query()->where('billing_type', 'postpaid')
                ->whereDate('expiration', '>=', $today)
                ->whereDate('expiration', '<=', $tomorrow);

            if ($selectedIspCode) {
                $expiredPostpaidClients->where('isp_code', $selectedIspCode);
            }

            $expiredPostpaidClients = $expiredPostpaidClients->get();

            $freeOnuExpiredClients = Client::query()->where('onu_free', 1)
                ->where('onu_returned', 0)
                ->whereDate('expiration', '<', $today);

            if ($selectedIspCode) {
                $freeOnuExpiredClients->where('isp_code', $selectedIspCode);
            }

            $freeOnuExpiredClients = $freeOnuExpiredClients->get();

            $expiredHotspotClients = HotspotClient::query()
                ->where('expires_at', '<', $today)
                ->orderBy('expires_at', 'asc')
                ->get();

            $currentYear = now()->year;
            $previousYear = $currentYear - 1;

            $currentYearData = Client::query()->whereYear('expiration', $currentYear)
                ->where('status', 'Expired')
                ->select(DB::raw('MONTH(expiration) as month'), DB::raw('COUNT(*) as total'))
                ->groupBy(DB::raw('MONTH(expiration)'))
                ->pluck('total', 'month')
                ->toArray();

            $previousYearData = Client::query()->whereYear('expiration', $previousYear)
                ->where('status', 'Expired')
                ->select(DB::raw('MONTH(expiration) as month'), DB::raw('COUNT(*) as total'))
                ->groupBy(DB::raw('MONTH(expiration)'))
                ->pluck('total', 'month')
                ->toArray();

            if ($selectedIspCode) {
                $currentYearData = Client::query()->where('isp_code', $selectedIspCode)
                    ->whereYear('expiration', $currentYear)
                    ->where('status', 'Expired')
                    ->select(DB::raw('MONTH(expiration) as month'), DB::raw('COUNT(*) as total'))
                    ->groupBy(DB::raw('MONTH(expiration)'))
                    ->pluck('total', 'month')
                    ->toArray();

                $previousYearData = Client::query()->where('isp_code', $selectedIspCode)
                    ->whereYear('expiration', $previousYear)
                    ->where('status', 'Expired')
                    ->select(DB::raw('MONTH(expiration) as month'), DB::raw('COUNT(*) as total'))
                    ->groupBy(DB::raw('MONTH(expiration)'))
                    ->pluck('total', 'month')
                    ->toArray();
            }

            $months = range(1,12);
            $currentYearDataFilled = [];
            $previousYearDataFilled = [];

            foreach($months as $month){
                $currentYearDataFilled[$month] = $currentYearData[$month] ?? 0;
                $previousYearDataFilled[$month] = $previousYearData[$month] ?? 0;
            }

            $smsStartDate = Carbon::now('Asia/Dhaka')->startOfMonth();
            $smsEndDate   = Carbon::today('Asia/Dhaka');

            $smsStatsRaw = SMSLOG::whereBetween('created_at', [
                    $smsStartDate->copy()->startOfDay(),
                    $smsEndDate->copy()->endOfDay()
                ])
                ->select(
                    DB::raw('DATE(created_at) as log_date'),
                    DB::raw("SUM(CASE WHEN status = '1' OR status = 'sent' OR status = 'delivered' THEN 1 ELSE 0 END) as sent_count"),
                    DB::raw("SUM(CASE WHEN status = '0' OR status = 'failed' THEN 1 ELSE 0 END) as failed_count"),
                    DB::raw('COUNT(*) as total_count')
                )
                ->groupBy(DB::raw('DATE(created_at)'))
                ->get()
                ->keyBy('log_date');

            $smsDatesLabels  = [];
            $smsSentSeries   = [];
            $smsFailedSeries = [];

            for ($date = $smsStartDate->copy(); $date->lte($smsEndDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                $smsDatesLabels[] = $date->format('d M');

                $sent   = isset($smsStatsRaw[$dateStr]) ? (int)$smsStatsRaw[$dateStr]->sent_count : 0;
                $failed = isset($smsStatsRaw[$dateStr]) ? (int)$smsStatsRaw[$dateStr]->failed_count : 0;

                $smsSentSeries[]   = $sent;
                $smsFailedSeries[] = $failed;
            }

            $runningMonthStart = Carbon::now('Asia/Dhaka')->startOfMonth();
            $runningMonthStats = SMSLOG::where('created_at', '>=', $runningMonthStart)
                ->select(
                    DB::raw("SUM(CASE WHEN status = '1' OR status = 'sent' OR status = 'delivered' THEN 1 ELSE 0 END) as sent_count"),
                    DB::raw("SUM(CASE WHEN status = '0' OR status = 'failed' THEN 1 ELSE 0 END) as failed_count"),
                    DB::raw('COUNT(*) as total_count')
                )
                ->first();

            $smsTodaySent = SMSLOG::whereDate('created_at', Carbon::today('Asia/Dhaka'))
                ->where(function($q) {
                    $q->where('status', '1')->orWhere('status', 'sent')->orWhere('status', 'delivered');
                })
                ->count();

            $smsMonthSent   = (int)($runningMonthStats->sent_count ?? 0);
            $smsMonthFailed = (int)($runningMonthStats->failed_count ?? 0);
            $smsMonthTotal  = $smsMonthSent + $smsMonthFailed;
            $smsDeliveryRate = $smsMonthTotal > 0 ? round(($smsMonthSent / $smsMonthTotal) * 100, 1) : 100;

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
                'smsChartData' => [
                    'labels' => $smsDatesLabels,
                    'sent'   => $smsSentSeries,
                    'failed' => $smsFailedSeries,
                ],
                'smsTodaySent'    => $smsTodaySent,
                'smsMonthSent'    => $smsMonthSent,
                'smsMonthFailed'  => $smsMonthFailed,
                'smsDeliveryRate' => $smsDeliveryRate,
                'lastUpdated' => $lastUpdated ? Carbon::parse($lastUpdated)->format('d-m-Y H:i:s') : null,
                'total' => $total,
                'revenue_by_isp' => $revenue_by_isp,
                'selectedIspCode' => $selectedIspCode,
            ];
        });

        $dashboardData['selectedIspCode'] = $selectedIspCode;

        return view('home', $dashboardData);
    }
}
