@extends('layouts.app')

@section('title')
    Expense Report
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
<style>
    .report-page {
        background: #f1f5f9;
        padding: 1.5rem 0 3rem;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .report-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .report-header p {
        margin: 0;
        color: #64748b;
        font-size: 0.88rem;
    }

    .header-actions {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .btn-create {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px -4px rgba(99, 102, 241, 0.35);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.45);
        color: #fff;
    }

    .btn-export {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1.1rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px -4px rgba(16, 185, 129, 0.35);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.45);
        color: #fff;
    }

    .btn-back {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.55rem 1.1rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-back:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .card-title i {
        color: #6366f1;
        font-size: 1rem;
    }

    .card-body {
        padding: 20px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .kpi-card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .kpi-body {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .kpi-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #64748b;
        margin-bottom: 2px;
    }

    .kpi-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.1;
        white-space: nowrap;
    }

    .kpi-accent-1 .kpi-icon { background: rgba(99, 102, 241, 0.12); color: #6366f1; }
    .kpi-accent-2 .kpi-icon { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .kpi-accent-3 .kpi-icon { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .kpi-accent-4 .kpi-icon { background: rgba(139, 92, 246, 0.12); color: #8b5cf6; }

    .filter-form .form-control,
    .filter-form .form-select {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #cbd5e1;
        background: #fff;
        transition: all 0.2s ease;
    }

    .filter-form .form-control:focus,
    .filter-form .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .filter-form label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.35rem;
    }

    .btn-filter {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px -3px rgba(99, 102, 241, 0.3);
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(99, 102, 241, 0.4);
        color: #fff;
    }

    .btn-reset {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .db-table {
        width: 100%;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }

    .db-table thead {
        background: #f8fafc;
    }

    .db-table th {
        padding: 12px 14px !important;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0 !important;
        vertical-align: middle;
    }

    .db-table td {
        padding: 12px 14px;
        font-size: 0.85rem;
        color: #334155;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .db-table tbody tr {
        transition: all 0.2s ease;
    }

    .db-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .expense-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .category-office_rent { background: rgba(99, 102, 241, 0.1); color: #4338ca; }
    .category-electricity { background: rgba(245, 158, 11, 0.1); color: #92400e; }
    .category-internet { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
    .category-salary { background: rgba(16, 185, 129, 0.1); color: #166534; }
    .category-equipment { background: rgba(244, 63, 94, 0.1); color: #9d174d; }
    .category-maintenance { background: rgba(245, 158, 11, 0.1); color: #b45309; }
    .category-transport { background: rgba(99, 102, 241, 0.1); color: #3730a3; }
    .category-food { background: rgba(245, 158, 11, 0.1); color: #b45309; }
    .category-marketing { background: rgba(139, 92, 246, 0.1); color: #6b21a8; }
    .category-software { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
    .category-bank_charge { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
    .category-other { background: rgba(100, 116, 139, 0.1); color: #475569; }

    .method-cash { background: rgba(245, 158, 11, 0.1); color: #92400e; }
    .method-bkash { background: rgba(16, 185, 129, 0.1); color: #166534; }
    .method-nagad { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
    .method-bank { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
    .method-card { background: rgba(139, 92, 246, 0.1); color: #6b21a8; }
    .method-other { background: rgba(100, 116, 139, 0.1); color: #475569; }

    .expense-amount {
        font-weight: 700;
        color: #0f172a;
    }

    .dataTables_filter input {
        border-radius: 8px !important;
        padding: 0.4rem 0.75rem !important;
        font-size: 0.85rem !important;
        border: 1px solid #cbd5e1 !important;
    }

    .dataTables_length select {
        border-radius: 8px !important;
        font-size: 0.85rem !important;
        border: 1px solid #cbd5e1 !important;
    }

    .dt-buttons {
        margin-bottom: 0;
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .dt-button {
        border-radius: 8px !important;
        padding: 0.4rem 0.8rem !important;
        font-weight: 600 !important;
        font-size: 0.78rem !important;
        border: none !important;
        transition: all 0.2s ease !important;
    }

    .buttons-excel { background: #10b981 !important; color: white !important; }
    .buttons-csv { background: #3b82f6 !important; color: white !important; }
    .buttons-pdf { background: #f43f5e !important; color: white !important; }
    .buttons-print { background: #64748b !important; color: white !important; }

    .chart-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #cbd5e1;
    }

    @media (max-width: 1200px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .report-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions > * {
            justify-content: center;
            flex: 1;
        }

        .chart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="report-page">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="report-header">
            <div>
                <h1><i data-lucide="bar-chart-3" style="width:26px;height:26px;color:#6366f1;"></i> Expense Report</h1>
                <p>Manage, analyze and export daily office expenses with visual insights.</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('office-expenses.export') . '?' . request()->getQueryString() }}" class="btn-export">
                    <i data-lucide="download" style="width:16px;height:16px;"></i> Export CSV
                </a>
                <a href="{{ route('office-expenses.index') }}" class="btn-back">
                    <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back
                </a>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="kpi-grid">
            <div class="kpi-card kpi-accent-1">
                <div class="kpi-icon"><i data-lucide="receipt" style="width:20px;height:20px;"></i></div>
                <div class="kpi-body">
                    <span class="kpi-label">Total Expenses</span>
                    <span class="kpi-value">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['total'], 2) }}</span>
                </div>
            </div>
            <div class="kpi-card kpi-accent-2">
                <div class="kpi-icon"><i data-lucide="calendar" style="width:20px;height:20px;"></i></div>
                <div class="kpi-body">
                    <span class="kpi-label">This Month</span>
                    <span class="kpi-value">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['month'], 2) }}</span>
                </div>
            </div>
            <div class="kpi-card kpi-accent-3">
                <div class="kpi-icon"><i data-lucide="sun" style="width:20px;height:20px;"></i></div>
                <div class="kpi-body">
                    <span class="kpi-label">Today</span>
                    <span class="kpi-value">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['today'], 2) }}</span>
                </div>
            </div>
            <div class="kpi-card kpi-accent-4">
                <div class="kpi-icon"><i data-lucide="list" style="width:20px;height:20px;"></i></div>
                <div class="kpi-body">
                    <span class="kpi-label">Records</span>
                    <span class="kpi-value">{{ number_format($stats['count']) }}</span>
                </div>
            </div>
        </div>

        {{-- Monthly Trend Chart --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i data-lucide="trending-up"></i> Monthly Expense Trend ({{ now()->year }})</h5>
                <span class="db-stat-pill">
                    <i data-lucide="trending-up" style="width:14px;height:14px;color:#6366f1;"></i>
                    Total: {{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['month'], 2) }}
                </span>
            </div>
            <div class="card-body">
                <div class="chart-container"><canvas id="monthlyExpenseChart"></canvas></div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i data-lucide="filter"></i> Filter Expenses</h5>
            </div>
            <div class="card-body">
                <form id="filter-form" class="row g-3 filter-form">
                    <div class="col-md-2">
                        <label>From Date</label>
                        <input type="date" id="filter_from_date" name="from_date" class="form-control" value="{{ $filters['from_date'] }}">
                    </div>
                    <div class="col-md-2">
                        <label>To Date</label>
                        <input type="date" id="filter_to_date" name="to_date" class="form-control" value="{{ $filters['to_date'] }}">
                    </div>
                    <div class="col-md-2">
                        <label>Category</label>
                        <select id="filter_category" name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ $filters['category'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Payment</label>
                        <select id="filter_payment_method" name="payment_method" class="form-select">
                            <option value="">All Methods</option>
                            @foreach($paymentMethods as $key => $label)
                                <option value="{{ $key }}" {{ $filters['payment_method'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Search</label>
                        <input type="text" id="filter_search" name="search" class="form-control" placeholder="Title, ref..." value="{{ $filters['search'] }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="button" id="btn-filter" class="btn-filter">
                            <i data-lucide="search" style="width:14px;height:14px;margin-right:4px;"></i> Filter
                        </button>
                        <button type="button" id="btn-reset" class="btn-reset">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="chart-grid">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i data-lucide="pie-chart"></i> Expense by Category</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="categoryPieChart"></canvas></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i data-lucide="credit-card"></i> By Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="paymentPieChart"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Report Table --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i data-lucide="table"></i> Expense Details</h5>
                <span id="report-subtitle" class="text-muted" style="font-size:0.78rem;">Showing all records</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table db-table" id="report-table" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Ref #</th>
                                <th>Added By</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
$(document).ready(function() {
    lucide.createIcons();

    // Monthly Expense Trend Chart
    var monthlyCtx = document.getElementById('monthlyExpenseChart');
    if (monthlyCtx) {
        var monthlyLabels = @json($monthlyExpenses->pluck('month')->map(function($m) { return \Carbon\Carbon::create(2024, $m, 1)->format('M'); }));
        var monthlyData = @json($monthlyExpenses->pluck('total'));
        var ctx = monthlyCtx.getContext('2d');
        var gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.30)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Expense (৳)',
                    data: monthlyData,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '৳ ' + context.raw.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '৳' + value.toLocaleString('en-IN'); },
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Category Pie Chart
    var pieCtx = document.getElementById('categoryPieChart');
    if (pieCtx) {
        var catLabels = @json($categoryBreakdown->pluck('category')->map(function($c) { return \App\Models\OfficeExpense::CATEGORIES[$c] ?? $c; }));
        var catData = @json($categoryBreakdown->pluck('total'));
        var catColors = ['#6366f1', '#3b82f6', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#64748b', '#94a3b8'];

        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: catColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 10 },
                            padding: 14,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ৳ ' + context.raw.toLocaleString('en-IN', {minimumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        });
    }

    // Payment Method Pie Chart
    var payCtx = document.getElementById('paymentPieChart');
    if (payCtx) {
        var payLabels = @json($paymentBreakdown->pluck('payment_method')->map(function($m) { return \App\Models\OfficeExpense::PAYMENT_METHODS[$m] ?? $m; }));
        var payData = @json($paymentBreakdown->pluck('total'));
        var payColors = ['#f59e0b', '#10b981', '#ef4444', '#3b82f6', '#8b5cf6', '#64748b'];

        new Chart(payCtx, {
            type: 'doughnut',
            data: {
                labels: payLabels,
                datasets: [{
                    data: payData,
                    backgroundColor: payColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 10 },
                            padding: 14,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ৳ ' + context.raw.toLocaleString('en-IN', {minimumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        });
    }

    // DataTable with export
    var table = $('#report-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-3"lip>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'dt-button buttons-excel',
                title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-csv"></i> CSV',
                className: 'dt-button buttons-csv',
                title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                className: 'dt-button buttons-pdf',
                title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                className: 'dt-button buttons-print',
                title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
            }
        ],
        ajax: {
            url: "{{ route('office-expenses.index') }}",
            data: function (d) {
                d.from_date = $('#filter_from_date').val();
                d.to_date = $('#filter_to_date').val();
                d.category = $('#filter_category').val();
                d.payment_method = $('#filter_payment_method').val();
                d.search = $('#filter_search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'expense_date_formatted', name: 'expense_date' },
            { data: 'category_badge', name: 'category', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'amount_formatted', name: 'amount', orderable: false, searchable: false },
            { data: 'payment_method_badge', name: 'payment_method', orderable: false, searchable: false },
            { data: 'reference_number', name: 'reference_number', render: function(data) { return data ? data : '-'; } },
            { data: 'creator_name', name: 'created_by', orderable: false, searchable: false }
        ],
        language: {
            search: "",
            searchPlaceholder: "Quick search..."
        },
        drawCallback: function() { lucide.createIcons(); }
    });

    $('#btn-filter').click(function() {
        updateReportSubtitle();
        table.draw();
    });

    $('#btn-reset').click(function() {
        $('#filter-form')[0].reset();
        table.draw();
        updateReportSubtitle();
    });

    function updateReportSubtitle() {
        var parts = [];
        var from = $('#filter_from_date').val();
        var to = $('#filter_to_date').val();
        var cat = $('#filter_category').find('option:selected').text();
        var method = $('#filter_payment_method').find('option:selected').text();
        var search = $('#filter_search').val();

        if (from || to) {
            parts.push('Date: ' + (from || 'Start') + ' to ' + (to || 'Today'));
        }
        if (cat !== 'All Categories') {
            parts.push('Category: ' + cat);
        }
        if (method !== 'All Methods') {
            parts.push('Method: ' + method);
        }
        if (search) {
            parts.push('Search: "' + search + '"');
        }

        $('#report-subtitle').text(parts.length ? parts.join(' | ') : 'Showing all records');
    }

    $('#filter_search').on('keypress', function(e) {
        if (e.which === 13) {
            table.draw();
        }
    });
});
</script>
@endsection