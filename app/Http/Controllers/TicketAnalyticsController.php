<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\ComplainType;
use App\Models\Technician;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketAnalyticsController extends Controller
{
    public function index()
    {
        /* ─────────────────────────────────────────────────────
         *  KPI CARDS
         * ───────────────────────────────────────────────────── */
        $totalTickets       = Ticket::count();
        $openTickets        = Ticket::whereIn('status', ['open', 'pending', 'in_progress'])->count();
        $resolvedTickets    = Ticket::where('status', 'resolved')->count();
        $closedTickets      = Ticket::where('status', 'closed')->count();
        $totalAreas         = Ticket::join('clients', 'tickets.client_id', '=', 'clients.id')
                                ->whereNotNull('clients.address')
                                ->where('clients.address', '!=', '')
                                ->distinct('clients.address')
                                ->count('clients.address');
        $totalTechnicians   = Technician::where('status', 'active')->count();

        // Repeat customers: clients with more than 1 ticket
        $repeatCustomers = Ticket::select('client_id', DB::raw('count(*) as cnt'))
            ->groupBy('client_id')
            ->having('cnt', '>', 1)
            ->count();

        $avgResolutionHours = null;
        $resolved = Ticket::whereNotNull('closed_at')->where('status', 'closed')->get();
        if ($resolved->count()) {
            $avg = $resolved->avg(fn($t) => Carbon::parse($t->opened_at)->diffInHours($t->closed_at));
            $avgResolutionHours = round($avg, 1);
        }

        /* ─────────────────────────────────────────────────────
         *  COMPLAINT TYPE ANALYSIS
         * ───────────────────────────────────────────────────── */
        $complainTypeStats = ComplainType::withCount('tickets')
            ->orderByDesc('tickets_count')
            ->get()
            ->map(fn($t) => [
                'label' => $t->name,
                'count' => $t->tickets_count,
            ])
            ->toArray();

        /* ─────────────────────────────────────────────────────
         *  AREA-WISE ANALYSIS (using client address as area)
         * ───────────────────────────────────────────────────── */
        $areaStats = Ticket::join('clients', 'tickets.client_id', '=', 'clients.id')
            ->select('clients.address as area', DB::raw('count(tickets.id) as total'))
            ->whereNotNull('clients.address')
            ->where('clients.address', '!=', '')
            ->groupBy('clients.address')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['label' => $r->area, 'count' => $r->total])
            ->toArray();

        // Top 10 problem areas
        $top10Areas = array_slice($areaStats, 0, 10);

        /* ─────────────────────────────────────────────────────
         *  TECHNICIAN-WISE ANALYSIS
         * ───────────────────────────────────────────────────── */
        $technicianStats = Technician::withCount([
                'tickets',
                'tickets as open_count'     => fn($q) => $q->whereIn('status', ['open', 'pending', 'in_progress']),
                'tickets as closed_count'   => fn($q) => $q->where('status', 'closed'),
                'tickets as resolved_count' => fn($q) => $q->where('status', 'resolved'),
            ])
            ->orderByDesc('tickets_count')
            ->get()
            ->map(fn($t) => [
                'label'    => $t->name,
                'total'    => $t->tickets_count,
                'open'     => $t->open_count,
                'closed'   => $t->closed_count,
                'resolved' => $t->resolved_count,
            ])
            ->toArray();

        /* ─────────────────────────────────────────────────────
         *  MONTHLY TREND (last 12 months)
         * ───────────────────────────────────────────────────── */
        $monthlyTrend = Ticket::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status = 'closed' then 1 else 0 end) as closed"),
                DB::raw("sum(case when status in ('open','pending','in_progress') then 1 else 0 end) as open")
            )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'month'  => $r->month,
                'total'  => $r->total,
                'closed' => $r->closed,
                'open'   => $r->open,
            ])
            ->toArray();

        /* ─────────────────────────────────────────────────────
         *  PIVOT RAW DATA (for JS pivot tables & slicers)
         *  Each row: { id, client, area, complain_type, technician, status, priority, month }
         * ───────────────────────────────────────────────────── */
        $pivotRows = Ticket::join('clients',      'tickets.client_id',       '=', 'clients.id')
            ->leftJoin('complain_types', 'tickets.complain_type_id', '=', 'complain_types.id')
            ->leftJoin('technicians',    'tickets.technician_id',    '=', 'technicians.id')
            ->select(
                'tickets.id',
                'clients.name as client_name',
                'clients.address as area',
                'complain_types.name as complain_type',
                'technicians.name as technician',
                'tickets.status',
                'tickets.priority',
                DB::raw("DATE_FORMAT(tickets.created_at,'%Y-%m') as month"),
                DB::raw("DATE_FORMAT(tickets.created_at,'%b %Y') as month_label")
            )
            ->orderByDesc('tickets.created_at')
            ->get()
            ->toArray();

        // Distinct slicer options
        $slicerAreas      = collect($pivotRows)->pluck('area')->filter()->unique()->sort()->values()->toArray();
        $slicerMonths     = collect($pivotRows)->map(fn($r) => ['key' => $r['month'], 'label' => $r['month_label']])->unique('key')->sortBy('key')->values()->toArray();
        $slicerTypes      = collect($complainTypeStats)->pluck('label')->filter()->unique()->sort()->values()->toArray();
        $slicerTechs      = collect($technicianStats)->pluck('label')->filter()->unique()->sort()->values()->toArray();

        return view('tickets.analytics', compact(
            'totalTickets', 'openTickets', 'resolvedTickets', 'closedTickets',
            'totalAreas', 'totalTechnicians', 'repeatCustomers', 'avgResolutionHours',
            'complainTypeStats', 'areaStats', 'top10Areas',
            'technicianStats', 'monthlyTrend',
            'pivotRows',
            'slicerAreas', 'slicerMonths', 'slicerTypes', 'slicerTechs'
        ));
    }
}
