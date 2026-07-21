@extends('layouts.app')

@section('title', 'Ticket Analytics')

@section('css')
<style>
/* ── Google Font ────────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --bg-dark:    #0d1117;
    --bg-card:    #161b22;
    --bg-card2:   #1c2128;
    --border:     #30363d;
    --text-main:  #e6edf3;
    --text-muted: #8b949e;
    --blue:       #58a6ff;
    --green:      #3fb950;
    --orange:     #f78166;
    --yellow:     #e3b341;
    --purple:     #bc8cff;
    --cyan:       #39d353;
    --red:        #f85149;
}

body { font-family: 'Inter', sans-serif; }

/* ── Analytics wrapper ────────────────────────────── */
.analytics-wrap { background: var(--bg-dark); min-height: 100vh; padding: 2rem 1.5rem; }

/* ── Page header ──────────────────────────────────── */
.analytics-header { margin-bottom: 2rem; }
.analytics-header h2 {
    font-size: 1.6rem; font-weight: 800;
    background: linear-gradient(90deg,#58a6ff,#bc8cff);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.analytics-header p { color: var(--text-muted); font-size: .9rem; margin: 0; }

/* ── Back button ──────────────────────────────────── */
.btn-back {
    background: var(--bg-card2); border: 1px solid var(--border);
    color: var(--text-muted); border-radius: 8px; padding: .4rem 1rem;
    font-size: .85rem; text-decoration: none; transition: all .2s;
}
.btn-back:hover { color: var(--blue); border-color: var(--blue); }

/* ── KPI Cards ────────────────────────────────────── */
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.kpi-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 12px; padding: 1.2rem 1rem; position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.4); }
.kpi-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--accent, #58a6ff);
}
.kpi-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); margin-bottom: .5rem; }
.kpi-value { font-size: 2rem; font-weight: 800; color: var(--text-main); line-height: 1; }
.kpi-sub   { font-size: .75rem; color: var(--text-muted); margin-top: .3rem; }
.kpi-icon  { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); font-size: 2rem; opacity: .08; }

/* ── Slicer panel ─────────────────────────────────── */
.slicer-panel {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px;
    padding: 1.25rem 1.5rem; margin-bottom: 2rem;
}
.slicer-panel h6 { color: var(--blue); font-weight: 700; margin-bottom: 1rem; font-size: .85rem; letter-spacing: .05em; text-transform: uppercase; }
.slicer-row { display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end; }
.slicer-group { display: flex; flex-direction: column; gap: .3rem; min-width: 160px; flex: 1; }
.slicer-group label { font-size: .72rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
.slicer-select {
    background: var(--bg-card2); border: 1px solid var(--border); color: var(--text-main);
    border-radius: 8px; padding: .45rem .75rem; font-size: .82rem; outline: none; cursor: pointer;
    transition: border-color .2s;
}
.slicer-select:focus, .slicer-select:hover { border-color: var(--blue); }
.slicer-select option { background: #1c2128; }
.btn-reset-slicer {
    background: transparent; border: 1px solid var(--border); color: var(--text-muted);
    border-radius: 8px; padding: .45rem 1rem; font-size: .8rem; cursor: pointer;
    transition: all .2s; align-self: flex-end;
}
.btn-reset-slicer:hover { border-color: var(--red); color: var(--red); }

/* ── Section headings ─────────────────────────────── */
.section-title {
    font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-muted); margin-bottom: 1rem; display: flex; align-items: center; gap: .5rem;
}
.section-title span { display: inline-block; width: 3px; height: 14px; border-radius: 2px; background: var(--blue); }

/* ── Chart cards ──────────────────────────────────── */
.chart-card {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px;
    padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
}
.chart-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.chart-card-title { font-size: .9rem; font-weight: 700; color: var(--text-main); }
.chart-badge {
    font-size: .68rem; font-weight: 600; border-radius: 999px;
    padding: .2rem .65rem; border: 1px solid; letter-spacing: .04em;
}

/* ── Pivot table ──────────────────────────────────── */
.pivot-table-wrap { overflow-x: auto; }
.pivot-table {
    width: 100%; border-collapse: collapse; font-size: .8rem;
}
.pivot-table th {
    background: var(--bg-card2); color: var(--text-muted); font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em; font-size: .7rem;
    padding: .6rem .9rem; border-bottom: 1px solid var(--border); text-align: left;
    white-space: nowrap; cursor: pointer; user-select: none;
}
.pivot-table th:hover { color: var(--blue); }
.pivot-table th .sort-icon { opacity: .4; margin-left: .3rem; font-size: .6rem; }
.pivot-table th.sorted-asc .sort-icon::after  { content: '▲'; opacity: 1; }
.pivot-table th.sorted-desc .sort-icon::after { content: '▼'; opacity: 1; }
.pivot-table th .sort-icon::after { content: '⇅'; }
.pivot-table td { padding: .55rem .9rem; border-bottom: 1px solid var(--border); color: var(--text-main); white-space: nowrap; }
.pivot-table tbody tr:hover td { background: rgba(88,166,255,.05); }
.pivot-table tbody tr:last-child td { border-bottom: none; }

/* ── Status / priority badges ─────────────────────── */
.badge-status, .badge-prio {
    display: inline-block; border-radius: 999px; font-size: .68rem; font-weight: 700;
    padding: .15rem .6rem; letter-spacing: .04em;
}
.s-open      { background:rgba(88,166,255,.15); color:#58a6ff; }
.s-pending   { background:rgba(227,179,65,.15);  color:#e3b341; }
.s-in_progress { background:rgba(188,140,255,.15);color:#bc8cff;}
.s-resolved  { background:rgba(63,185,80,.15);   color:#3fb950; }
.s-closed    { background:rgba(139,148,158,.15);  color:#8b949e;}
.p-high      { background:rgba(248,81,73,.15);   color:#f85149; }
.p-medium    { background:rgba(227,179,65,.15);   color:#e3b341; }
.p-low       { background:rgba(139,148,158,.15);  color:#8b949e; }

/* ── Top-10 bar rows ──────────────────────────────── */
.top10-row { display: flex; align-items: center; gap: .75rem; margin-bottom: .6rem; }
.top10-rank { font-size: .7rem; font-weight: 700; color: var(--text-muted); width: 22px; text-align: center; }
.top10-label { font-size: .82rem; color: var(--text-main); flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.top10-bar-wrap { width: 140px; height: 8px; background: var(--bg-card2); border-radius: 99px; overflow: hidden; }
.top10-bar { height: 100%; border-radius: 99px; background: linear-gradient(90deg,#58a6ff,#bc8cff); transition: width .6s ease; }
.top10-count { font-size: .78rem; font-weight: 700; color: var(--blue); min-width: 28px; text-align: right; }

/* ── Pagination ───────────────────────────────────── */
.pivot-pagination { display: flex; gap: .4rem; justify-content: center; margin-top: 1rem; flex-wrap: wrap; }
.pag-btn {
    background: var(--bg-card2); border: 1px solid var(--border); color: var(--text-muted);
    border-radius: 6px; padding: .3rem .65rem; font-size: .75rem; cursor: pointer; transition: all .2s;
}
.pag-btn:hover, .pag-btn.active { background: var(--blue); border-color: var(--blue); color: #fff; }
.pag-btn:disabled { opacity: .4; cursor: not-allowed; }

/* ── Empty state ──────────────────────────────────── */
.empty-state { text-align: center; padding: 2rem; color: var(--text-muted); font-size: .85rem; }

/* ── Responsive ────────────────────────────────────── */
@media(max-width:768px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .slicer-row { flex-direction: column; }
}
</style>
@endsection

@section('content')
<div class="analytics-wrap">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="analytics-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>📊 Ticket Analytics Dashboard</h2>
            <p>Interactive analysis of all support tickets — filter, pivot, and explore insights.</p>
        </div>
        <a href="{{ route('tickets.live') }}" class="btn-back">← Back to Tickets</a>
    </div>

    {{-- ── KPI Cards ───────────────────────────────────────────── --}}
    <div class="kpi-grid">
        <div class="kpi-card" style="--accent:#58a6ff">
            <div class="kpi-label">Total Complaints</div>
            <div class="kpi-value">{{ number_format($totalTickets) }}</div>
            <div class="kpi-sub">All time tickets</div>
            <div class="kpi-icon">🎫</div>
        </div>
        <div class="kpi-card" style="--accent:#f78166">
            <div class="kpi-label">Open / Active</div>
            <div class="kpi-value">{{ number_format($openTickets) }}</div>
            <div class="kpi-sub">Pending resolution</div>
            <div class="kpi-icon">🔴</div>
        </div>
        <div class="kpi-card" style="--accent:#3fb950">
            <div class="kpi-label">Resolved</div>
            <div class="kpi-value">{{ number_format($resolvedTickets) }}</div>
            <div class="kpi-sub">Successfully resolved</div>
            <div class="kpi-icon">✅</div>
        </div>
        <div class="kpi-card" style="--accent:#8b949e">
            <div class="kpi-label">Closed</div>
            <div class="kpi-value">{{ number_format($closedTickets) }}</div>
            <div class="kpi-sub">Closed tickets</div>
            <div class="kpi-icon">🔒</div>
        </div>
        <div class="kpi-card" style="--accent:#e3b341">
            <div class="kpi-label">Total Areas</div>
            <div class="kpi-value">{{ number_format($totalAreas) }}</div>
            <div class="kpi-sub">Unique client areas</div>
            <div class="kpi-icon">📍</div>
        </div>
        <div class="kpi-card" style="--accent:#bc8cff">
            <div class="kpi-label">Technicians</div>
            <div class="kpi-value">{{ number_format($totalTechnicians) }}</div>
            <div class="kpi-sub">Active technicians</div>
            <div class="kpi-icon">🔧</div>
        </div>
        <div class="kpi-card" style="--accent:#f85149">
            <div class="kpi-label">Repeat Customers</div>
            <div class="kpi-value">{{ number_format($repeatCustomers) }}</div>
            <div class="kpi-sub">Clients with 2+ tickets</div>
            <div class="kpi-icon">🔁</div>
        </div>
        <div class="kpi-card" style="--accent:#39d353">
            <div class="kpi-label">Avg. Resolution</div>
            <div class="kpi-value">{{ $avgResolutionHours ?? '—' }}{{ $avgResolutionHours ? 'h' : '' }}</div>
            <div class="kpi-sub">Average hours to close</div>
            <div class="kpi-icon">⏱</div>
        </div>
    </div>

    {{-- ── Interactive Slicers ─────────────────────────────────── --}}
    <div class="slicer-panel">
        <h6>🎛️ Interactive Slicers — filter all charts & tables below</h6>
        <div class="slicer-row">
            <div class="slicer-group">
                <label>📍 Area</label>
                <select id="slArea" class="slicer-select">
                    <option value="">All Areas</option>
                    @foreach($slicerAreas as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="slicer-group">
                <label>📅 Month</label>
                <select id="slMonth" class="slicer-select">
                    <option value="">All Months</option>
                    @foreach($slicerMonths as $m)
                        <option value="{{ $m['key'] }}">{{ $m['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="slicer-group">
                <label>⚙️ Complaint Type</label>
                <select id="slType" class="slicer-select">
                    <option value="">All Types</option>
                    @foreach($slicerTypes as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="slicer-group">
                <label>🔧 Technician</label>
                <select id="slTech" class="slicer-select">
                    <option value="">All Technicians</option>
                    @foreach($slicerTechs as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="slicer-group">
                <label>🚦 Status</label>
                <select id="slStatus" class="slicer-select">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div class="slicer-group">
                <label>🔥 Priority</label>
                <select id="slPriority" class="slicer-select">
                    <option value="">All Priorities</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <button class="btn-reset-slicer" onclick="resetSlicers()">✕ Reset All</button>
        </div>
        <div class="mt-2" style="font-size:.78rem; color:var(--text-muted);">
            Showing <strong id="filteredCount" style="color:var(--blue);">{{ count($pivotRows) }}</strong> of
            <strong>{{ count($pivotRows) }}</strong> records
        </div>
    </div>

    {{-- ── Row 1: Complaint Type + Monthly Trend ──────────────── --}}
    <div class="row g-4 mb-4">

        {{-- Complaint Type Analysis --}}
        <div class="col-lg-5">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">⚙️ Complaint Type Analysis</div>
                    <span class="chart-badge" style="color:#58a6ff;border-color:rgba(88,166,255,.3);">By Category</span>
                </div>
                <div class="section-title"><span></span> Tickets per Complain Type</div>
                <div style="position:relative;height:280px;">
                    <canvas id="chartComplainType"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Trend --}}
        <div class="col-lg-7">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">📈 Monthly Trend (Last 12 Months)</div>
                    <span class="chart-badge" style="color:#3fb950;border-color:rgba(63,185,80,.3);">Over Time</span>
                </div>
                <div class="section-title"><span></span> Created vs Closed per Month</div>
                <div style="position:relative;height:280px;">
                    <canvas id="chartMonthly"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 2: Area Analysis + Technician Analysis ─────────── --}}
    <div class="row g-4 mb-4">

        {{-- Top 10 Problem Areas --}}
        <div class="col-lg-5">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">📍 Top 10 Problem Areas</div>
                    <span class="chart-badge" style="color:#e3b341;border-color:rgba(227,179,65,.3);">Area-wise</span>
                </div>
                <div class="section-title"><span></span> Areas with most complaints</div>
                <div id="top10List">
                    @foreach($top10Areas as $i => $a)
                    <div class="top10-row">
                        <div class="top10-rank">#{{ $i+1 }}</div>
                        <div class="top10-label" title="{{ $a['label'] }}">{{ $a['label'] }}</div>
                        <div class="top10-bar-wrap">
                            <div class="top10-bar" style="width:{{ $top10Areas[0]['count'] > 0 ? round($a['count']/$top10Areas[0]['count']*100) : 0 }}%"></div>
                        </div>
                        <div class="top10-count">{{ $a['count'] }}</div>
                    </div>
                    @endforeach
                    @if(empty($top10Areas))
                        <div class="empty-state">No area data available</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Technician Analysis --}}
        <div class="col-lg-7">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">🔧 Technician-wise Analysis</div>
                    <span class="chart-badge" style="color:#bc8cff;border-color:rgba(188,140,255,.3);">By Technician</span>
                </div>
                <div class="section-title"><span></span> Tickets assigned per technician</div>
                <div style="position:relative;height:260px;">
                    <canvas id="chartTechnician"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 3: Area-wise Pivot Chart ───────────────────────── --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-card-title">🗺️ Area-wise Pivot Chart</div>
                    <span class="chart-badge" style="color:#f78166;border-color:rgba(247,129,102,.3);">Interactive</span>
                </div>
                <div class="section-title"><span></span> Complaint count by area (filtered by slicers)</div>
                <div style="position:relative;height:300px;">
                    <canvas id="chartArea"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pivot Tables ────────────────────────────────────────── --}}
    <div class="row g-4 mb-4">

        {{-- Pivot Table: Complain Type × Status --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-card-title">📋 Pivot: Complaint Type × Status</div>
                    <span class="chart-badge" style="color:#58a6ff;border-color:rgba(88,166,255,.3);">Pivot Table</span>
                </div>
                <div class="pivot-table-wrap">
                    <table class="pivot-table" id="pivotTypeStatus">
                        <thead>
                            <tr>
                                <th>Complaint Type</th>
                                <th>Open</th>
                                <th>Pending</th>
                                <th>In Progress</th>
                                <th>Resolved</th>
                                <th>Closed</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="pivotTypeStatusBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pivot Table: Technician × Priority --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-card-title">📋 Pivot: Technician × Priority</div>
                    <span class="chart-badge" style="color:#bc8cff;border-color:rgba(188,140,255,.3);">Pivot Table</span>
                </div>
                <div class="pivot-table-wrap">
                    <table class="pivot-table" id="pivotTechPriority">
                        <thead>
                            <tr>
                                <th>Technician</th>
                                <th>🔴 High</th>
                                <th>🟡 Medium</th>
                                <th>⚪ Low</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="pivotTechPriorityBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Full Data Table ─────────────────────────────────────── --}}
    <div class="chart-card mb-4">
        <div class="chart-card-header">
            <div class="chart-card-title">📑 Full Ticket Data Table</div>
            <div class="d-flex gap-2 align-items-center">
                <input type="text" id="tableSearch" placeholder="Search records…"
                    style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-main);border-radius:8px;padding:.35rem .75rem;font-size:.78rem;outline:none;width:180px;">
                <span class="chart-badge" style="color:#3fb950;border-color:rgba(63,185,80,.3);" id="tableCount">0 rows</span>
            </div>
        </div>
        <div class="pivot-table-wrap">
            <table class="pivot-table" id="fullTable">
                <thead>
                    <tr>
                        <th data-col="id">#<span class="sort-icon"></span></th>
                        <th data-col="client_name">Client<span class="sort-icon"></span></th>
                        <th data-col="area">Area<span class="sort-icon"></span></th>
                        <th data-col="complain_type">Complaint Type<span class="sort-icon"></span></th>
                        <th data-col="technician">Technician<span class="sort-icon"></span></th>
                        <th data-col="status">Status<span class="sort-icon"></span></th>
                        <th data-col="priority">Priority<span class="sort-icon"></span></th>
                        <th data-col="month">Month<span class="sort-icon"></span></th>
                    </tr>
                </thead>
                <tbody id="fullTableBody"></tbody>
            </table>
        </div>
        <div class="pivot-pagination" id="tablePagination"></div>
    </div>

</div>{{-- end analytics-wrap --}}
@endsection

@section('scripts')
{{-- Raw data injected for JS --}}
<script>
const RAW_DATA      = @json($pivotRows);
const MONTHLY_DATA  = @json($monthlyTrend);
const TECH_STATS    = @json($technicianStats);
const COMPLAIN_DATA = @json($complainTypeStats);
const AREA_DATA     = @json($areaStats);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
'use strict';

/* ─── Colour palette ─────────────────────────────────────────── */
const PALETTE = ['#58a6ff','#bc8cff','#3fb950','#e3b341','#f78166',
                 '#39d353','#f85149','#79c0ff','#d2a8ff','#56d364'];
const STATUS_COLOR = {open:'#58a6ff',pending:'#e3b341',in_progress:'#bc8cff',resolved:'#3fb950',closed:'#8b949e'};
const PRIO_COLOR   = {high:'#f85149',medium:'#e3b341',low:'#8b949e'};

/* ─── Chart defaults ─────────────────────────────────────────── */
Chart.defaults.color      = '#8b949e';
Chart.defaults.borderColor = '#30363d';

/* ─── Shared chart instances ─────────────────────────────────── */
let cType = null, cMonthly = null, cTech = null, cArea = null;

/* ─── Slicer state ───────────────────────────────────────────── */
const slicers = { area:'', month:'', type:'', tech:'', status:'', priority:'' };

['slArea','slMonth','slType','slTech','slStatus','slPriority'].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
        const map = { slArea:'area', slMonth:'month', slType:'type', slTech:'tech', slStatus:'status', slPriority:'priority' };
        slicers[map[id]] = this.value;
        applyAll();
    });
});

document.getElementById('tableSearch').addEventListener('input', function() {
    tableSearchTerm = this.value.toLowerCase();
    currentPage = 1;
    renderTable();
});

window.resetSlicers = function() {
    ['slArea','slMonth','slType','slTech','slStatus','slPriority'].forEach(id => {
        document.getElementById(id).value = '';
    });
    Object.keys(slicers).forEach(k => slicers[k] = '');
    tableSearchTerm = '';
    document.getElementById('tableSearch').value = '';
    applyAll();
};

/* ─── Filter function ────────────────────────────────────────── */
function filterData() {
    return RAW_DATA.filter(r => {
        if (slicers.area   && r.area         !== slicers.area)   return false;
        if (slicers.month  && r.month        !== slicers.month)  return false;
        if (slicers.type   && r.complain_type !== slicers.type)  return false;
        if (slicers.tech   && r.technician   !== slicers.tech)   return false;
        if (slicers.status && r.status       !== slicers.status) return false;
        if (slicers.priority && r.priority   !== slicers.priority) return false;
        return true;
    });
}

/* ─── Apply all updates ──────────────────────────────────────── */
function applyAll() {
    const data = filterData();
    document.getElementById('filteredCount').textContent = data.length;
    updateComplainChart(data);
    updateAreaChart(data);
    updatePivotTypeStatus(data);
    updatePivotTechPriority(data);
    currentPage = 1;
    renderTable();
}

/* ─── Chart builder helpers ──────────────────────────────────── */
function groupBy(arr, key) {
    return arr.reduce((acc, r) => {
        const k = r[key] || 'Unknown';
        acc[k] = (acc[k] || 0) + 1;
        return acc;
    }, {});
}

function makeHBarData(grouped, limit = 20) {
    const sorted = Object.entries(grouped).sort((a,b)=>b[1]-a[1]).slice(0, limit);
    return { labels: sorted.map(e=>e[0]), counts: sorted.map(e=>e[1]) };
}

/* ─── 1. Complaint Type Chart ───────────────────────────────── */
function updateComplainChart(data) {
    const grouped = groupBy(data, 'complain_type');
    const { labels, counts } = makeHBarData(grouped);
    const colors = labels.map((_,i) => PALETTE[i % PALETTE.length]);
    const canvas = document.getElementById('chartComplainType');
    if (!canvas) return;
    if (cType) cType.destroy();
    cType = new Chart(canvas, {
        type: 'bar',
        data: { labels, datasets: [{ label:'Tickets', data:counts, backgroundColor:colors, borderRadius:6, borderSkipped:false }] },
        options: {
            indexAxis: 'y', responsive:true, maintainAspectRatio:false,
            plugins: { legend:{ display:false } },
            scales: {
                x: { grid:{ color:'rgba(255,255,255,.05)' }, ticks:{ color:'#8b949e' }, beginAtZero:true, precision:0 },
                y: { grid:{ display:false }, ticks:{ color:'#e6edf3', font:{ size:11 } } }
            }
        }
    });
}

/* ─── 2. Monthly Trend Chart (static — uses server data) ────── */
function buildMonthlyChart() {
    const canvas = document.getElementById('chartMonthly');
    if (!canvas) return;
    if (cMonthly) cMonthly.destroy();
    const labels  = MONTHLY_DATA.map(r=>r.month);
    const totals  = MONTHLY_DATA.map(r=>r.total);
    const closed  = MONTHLY_DATA.map(r=>r.closed);
    const open    = MONTHLY_DATA.map(r=>r.open);
    cMonthly = new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [
                { label:'Total', data:totals, borderColor:'#58a6ff', backgroundColor:'rgba(88,166,255,.1)', tension:.4, fill:true, pointRadius:4, pointHoverRadius:6 },
                { label:'Closed', data:closed, borderColor:'#3fb950', backgroundColor:'rgba(63,185,80,.08)', tension:.4, fill:false, pointRadius:3 },
                { label:'Open',  data:open,   borderColor:'#f78166', backgroundColor:'rgba(247,129,102,.08)', tension:.4, fill:false, pointRadius:3, borderDash:[4,4] },
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction: { mode:'index', intersect:false },
            plugins: { legend:{ labels:{ color:'#8b949e', boxWidth:12, font:{ size:11 } } } },
            scales: {
                x: { grid:{ color:'rgba(255,255,255,.04)' }, ticks:{ color:'#8b949e', font:{ size:10 } } },
                y: { grid:{ color:'rgba(255,255,255,.05)' }, ticks:{ color:'#8b949e' }, beginAtZero:true, precision:0 }
            }
        }
    });
}

/* ─── 3. Technician Chart (static — uses server data) ───────── */
function buildTechnicianChart() {
    const canvas = document.getElementById('chartTechnician');
    if (!canvas) return;
    if (cTech) cTech.destroy();
    const labels   = TECH_STATS.map(t=>t.label || 'Unassigned');
    const totals   = TECH_STATS.map(t=>t.total);
    const openArr  = TECH_STATS.map(t=>t.open);
    const closedArr= TECH_STATS.map(t=>t.closed);
    cTech = new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label:'Total',  data:totals,   backgroundColor:'rgba(88,166,255,.4)',  borderColor:'#58a6ff', borderWidth:1, borderRadius:4 },
                { label:'Open',   data:openArr,  backgroundColor:'rgba(247,129,102,.4)', borderColor:'#f78166', borderWidth:1, borderRadius:4 },
                { label:'Closed', data:closedArr,backgroundColor:'rgba(63,185,80,.4)',   borderColor:'#3fb950', borderWidth:1, borderRadius:4 },
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins: { legend:{ labels:{ color:'#8b949e', boxWidth:12, font:{ size:11 } } } },
            scales: {
                x: { grid:{ display:false }, ticks:{ color:'#8b949e', font:{ size:11 } } },
                y: { grid:{ color:'rgba(255,255,255,.05)' }, ticks:{ color:'#8b949e' }, beginAtZero:true, precision:0 }
            }
        }
    });
}

/* ─── 4. Area Pivot Chart ───────────────────────────────────── */
function updateAreaChart(data) {
    const grouped = groupBy(data, 'area');
    const { labels, counts } = makeHBarData(grouped, 15);
    const colors = labels.map((_,i) => PALETTE[i % PALETTE.length]);
    const canvas = document.getElementById('chartArea');
    if (!canvas) return;
    if (cArea) cArea.destroy();
    cArea = new Chart(canvas, {
        type: 'bar',
        data: { labels, datasets:[{ label:'Tickets', data:counts, backgroundColor:colors, borderRadius:5, borderSkipped:false }] },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display:false } },
            scales:{
                x:{ grid:{ color:'rgba(255,255,255,.04)' }, ticks:{ color:'#8b949e', font:{ size:10 } }, beginAtZero:true, precision:0 },
                y:{ grid:{ display:false }, ticks:{ color:'#e6edf3', font:{ size:10 } } }
            }
        }
    });
}

/* ─── 5. Pivot Table: Complaint Type × Status ───────────────── */
function updatePivotTypeStatus(data) {
    const types   = [...new Set(RAW_DATA.map(r=>r.complain_type||'Unknown'))].sort();
    const statuses= ['open','pending','in_progress','resolved','closed'];

    const map = {};
    data.forEach(r => {
        const t = r.complain_type || 'Unknown';
        const s = r.status        || 'unknown';
        if (!map[t]) map[t] = {};
        map[t][s] = (map[t][s] || 0) + 1;
    });

    const tbody = document.getElementById('pivotTypeStatusBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    let hasData = false;
    types.forEach(t => {
        const row = map[t];
        if (!row) return;
        hasData = true;
        const total = statuses.reduce((s,st)=>(s+(row[st]||0)),0);
        const tr = document.createElement('tr');
        tr.innerHTML = `<td style="color:var(--text-main);font-weight:500;">${t}</td>`
            + statuses.map(st => `<td style="color:${STATUS_COLOR[st]||'#fff'};font-weight:600;">${row[st]||0}</td>`).join('')
            + `<td style="color:var(--blue);font-weight:700;">${total}</td>`;
        tbody.appendChild(tr);
    });
    if (!hasData) tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No data for current filters</td></tr>';
}

/* ─── 6. Pivot Table: Technician × Priority ────────────────── */
function updatePivotTechPriority(data) {
    const techs = [...new Set(RAW_DATA.map(r=>r.technician||'Unassigned'))].sort();
    const prios = ['high','medium','low'];

    const map = {};
    data.forEach(r => {
        const t = r.technician || 'Unassigned';
        const p = r.priority   || 'low';
        if (!map[t]) map[t] = {};
        map[t][p] = (map[t][p] || 0) + 1;
    });

    const tbody = document.getElementById('pivotTechPriorityBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    let hasData = false;
    techs.forEach(t => {
        const row = map[t];
        if (!row) return;
        hasData = true;
        const total = prios.reduce((s,p)=>(s+(row[p]||0)),0);
        const tr = document.createElement('tr');
        tr.innerHTML = `<td style="color:var(--text-main);font-weight:500;">${t}</td>`
            + prios.map(p => `<td style="color:${PRIO_COLOR[p]};font-weight:600;">${row[p]||0}</td>`).join('')
            + `<td style="color:var(--blue);font-weight:700;">${total}</td>`;
        tbody.appendChild(tr);
    });
    if (!hasData) tbody.innerHTML = '<tr><td colspan="5" class="empty-state">No data for current filters</td></tr>';
}

/* ─── 7. Full data table with sort + search + pagination ─────── */
let currentPage = 1;
const PAGE_SIZE = 15;
let tableSortCol  = 'id';
let tableSortDir  = 'desc';
let tableSearchTerm = '';

// Sortable headers
document.querySelectorAll('#fullTable thead th[data-col]').forEach(th => {
    th.addEventListener('click', function() {
        const col = this.dataset.col;
        if (tableSortCol === col) {
            tableSortDir = tableSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            tableSortCol = col; tableSortDir = 'asc';
        }
        document.querySelectorAll('#fullTable thead th').forEach(h => h.classList.remove('sorted-asc','sorted-desc'));
        this.classList.add('sorted-' + tableSortDir);
        currentPage = 1;
        renderTable();
    });
});

function renderTable() {
    let data = filterData();

    // search
    if (tableSearchTerm) {
        data = data.filter(r =>
            Object.values(r).some(v => String(v||'').toLowerCase().includes(tableSearchTerm))
        );
    }

    // sort
    data = [...data].sort((a,b) => {
        let va = a[tableSortCol] || '', vb = b[tableSortCol] || '';
        if (!isNaN(va) && !isNaN(vb)) { va = +va; vb = +vb; }
        else { va = String(va).toLowerCase(); vb = String(vb).toLowerCase(); }
        if (va < vb) return tableSortDir === 'asc' ? -1 : 1;
        if (va > vb) return tableSortDir === 'asc' ?  1 : -1;
        return 0;
    });

    document.getElementById('tableCount').textContent = data.length + ' rows';

    const totalPages = Math.max(1, Math.ceil(data.length / PAGE_SIZE));
    if (currentPage > totalPages) currentPage = totalPages;
    const slice = data.slice((currentPage-1)*PAGE_SIZE, currentPage*PAGE_SIZE);

    const tbody = document.getElementById('fullTableBody');
    if (!slice.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="empty-state">No records match the current filters.</td></tr>';
    } else {
        tbody.innerHTML = slice.map(r => `
            <tr>
                <td style="color:var(--blue);font-weight:600;">#${r.id}</td>
                <td>${r.client_name||'—'}</td>
                <td>${r.area||'—'}</td>
                <td>${r.complain_type||'—'}</td>
                <td>${r.technician||'<span style="color:var(--text-muted)">Unassigned</span>'}</td>
                <td><span class="badge-status s-${r.status}">${(r.status||'').replace('_',' ')}</span></td>
                <td><span class="badge-status p-${r.priority}">${r.priority||'—'}</span></td>
                <td style="color:var(--text-muted);">${r.month_label||'—'}</td>
            </tr>`).join('');
    }

    // Pagination
    const pag = document.getElementById('tablePagination');
    pag.innerHTML = '';
    if (totalPages <= 1) return;

    const pages = [];
    pages.push(1);
    for (let p = Math.max(2, currentPage-2); p <= Math.min(totalPages-1, currentPage+2); p++) pages.push(p);
    if (totalPages > 1) pages.push(totalPages);

    let prev = 0;
    pages.forEach(p => {
        if (p - prev > 1) {
            const dots = document.createElement('span');
            dots.textContent = '…'; dots.style.color = 'var(--text-muted)'; dots.style.padding = '0 .3rem';
            pag.appendChild(dots);
        }
        const btn = document.createElement('button');
        btn.className = 'pag-btn' + (p === currentPage ? ' active' : '');
        btn.textContent = p;
        btn.addEventListener('click', () => { currentPage = p; renderTable(); });
        pag.appendChild(btn);
        prev = p;
    });
}

/* ─── Bootstrap ─────────────────────────────────────────────── */
window.addEventListener('DOMContentLoaded', function() {
    const initialData = filterData();
    updateComplainChart(initialData);
    updateAreaChart(initialData);
    updatePivotTypeStatus(initialData);
    updatePivotTechPriority(initialData);
    buildMonthlyChart();
    buildTechnicianChart();
    renderTable();
});

})();
</script>
@endsection
