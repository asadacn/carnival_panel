@extends('layouts.app')

@section('title')
    Daily Office Expenses
@endsection

@section('css')
<style>
    .expenses-page {
        background: #f8fafc;
        padding: 1.5rem 0 3rem;
    }

    .expense-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .expense-page-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-create {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px -4px rgba(99, 102, 241, 0.35);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.45);
        color: #fff;
    }

    .btn-report {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px -4px rgba(59, 130, 246, 0.35);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(59, 130, 246, 0.45);
        color: #fff;
    }

    .db-kpi-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: default;
        transition: all 0.3s ease;
        height: 100%;
    }

    .db-kpi-card:hover {
        box-shadow: 0 4px 18px rgba(0, 0, 0, .10);
        transform: translateY(-2px);
    }

    .db-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .db-kpi-body {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .db-kpi-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 2px;
    }

    .db-kpi-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        line-height: 1.1;
    }

    .db-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .db-card-header {
        padding: 16px 20px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .db-card-title {
        font-size: 0.93rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 0;
    }

    .db-card-title i {
        color: #6366f1;
    }

    .db-card-body {
        padding: 18px 20px;
        flex: 1;
    }

    .db-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.78rem;
        color: #475569;
    }

    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .filter-card h5 {
        font-size: 0.93rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 12px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.45rem 0.75rem;
        border: 1px solid #cbd5e1;
        background: #fafbfc;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        background: #fff;
    }

    .btn-filter {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.82rem;
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
        font-size: 0.82rem;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
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
        font-size: 0.88rem;
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
        font-size: 0.72rem;
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
        color: #1e293b;
    }

    .expense-actions {
        display: flex;
        gap: 4px;
    }

    .expense-action {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.75rem;
    }

    .expense-action-view { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .expense-action-view:hover { background: rgba(59, 130, 246, 0.2); color: #2563eb; }
    .expense-action-edit { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .expense-action-edit:hover { background: rgba(245, 158, 11, 0.2); color: #d97706; }
    .expense-action-delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .expense-action-delete:hover { background: rgba(239, 68, 68, 0.2); color: #dc2626; }

    .expense-receipt-link {
        color: #6366f1;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
    }

    .expense-receipt-link:hover {
        text-decoration: underline;
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

    @media (max-width: 768px) {
        .expense-page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .header-actions {
            justify-content: stretch;
        }

        .header-actions > * {
            justify-content: center;
        }

        .db-kpi-card {
            margin-bottom: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="expenses-page">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="expense-page-header">
            <h1>
                <i data-lucide="file-text" style="width:28px;height:28px;color:#6366f1;"></i>
                Daily Office Expenses
            </h1>
            <div class="header-actions">
                <a href="{{ route('office-expenses.report') }}" class="btn-report">
                    <i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i> Report
                </a>
                <a href="{{ route('office-expenses.create') }}" class="btn-create">
                    <i data-lucide="plus" style="width:18px;height:18px;"></i> Add Expense
                </a>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-sm-4 col-lg">
                <div class="db-kpi-card">
                    <div class="db-kpi-icon" style="background:rgba(99,102,241,.12);">
                        <i data-lucide="receipt" style="color:#6366f1;width:22px;height:22px;"></i>
                    </div>
                    <div class="db-kpi-body">
                        <span class="db-kpi-label">Total Expenses</span>
                        <span class="db-kpi-value">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['total'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-lg">
                <div class="db-kpi-card">
                    <div class="db-kpi-icon" style="background:rgba(16,185,129,.12);">
                        <i data-lucide="calendar" style="color:#10b981;width:22px;height:22px;"></i>
                    </div>
                    <div class="db-kpi-body">
                        <span class="db-kpi-label">This Month</span>
                        <span class="db-kpi-value">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['month'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-lg">
                <div class="db-kpi-card">
                    <div class="db-kpi-icon" style="background:rgba(245,158,11,.12);">
                        <i data-lucide="sun" style="color:#f59e0b;width:22px;height:22px;"></i>
                    </div>
                    <div class="db-kpi-body">
                        <span class="db-kpi-label">Today</span>
                        <span class="db-kpi-value">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['today'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-lg">
                <div class="db-kpi-card">
                    <div class="db-kpi-icon" style="background:rgba(139,92,246,.12);">
                        <i data-lucide="list" style="color:#8b5cf6;width:22px;height:22px;"></i>
                    </div>
                    <div class="db-kpi-body">
                        <span class="db-kpi-label">Total Records</span>
                        <span class="db-kpi-value">{{ number_format($stats['count']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-card">
            <h5><i data-lucide="filter" style="width:18px;height:18px;"></i> Filters</h5>
            <form id="expense-filter-form" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="font-size:0.78rem;">From Date</label>
                    <input type="date" id="filter_from_date" name="from_date" class="form-control" value="{{ $filters['from_date'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="font-size:0.78rem;">To Date</label>
                    <input type="date" id="filter_to_date" name="to_date" class="form-control" value="{{ $filters['to_date'] }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold" style="font-size:0.78rem;">Category</label>
                    <select id="filter_category" name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ $filters['category'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold" style="font-size:0.78rem;">Payment</label>
                    <select id="filter_payment_method" name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ $filters['payment_method'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold" style="font-size:0.78rem;">Search</label>
                    <input type="text" id="filter_search" name="search" class="form-control" placeholder="Title, ref..." value="{{ $filters['search'] }}">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="button" id="btn-apply-filters" class="btn-filter">
                        <i data-lucide="search" style="width:16px;height:16px;margin-right:4px;"></i> Apply Filters
                    </button>
                    <button type="button" id="btn-reset-filters" class="btn-reset">
                        <i data-lucide="refresh-cw" style="width:16px;height:16px;"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        {{-- Expenses Table --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <i data-lucide="table" style="width:18px;height:18px;"></i>
                    Expense Ledger
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('office-expenses.export') . '?' . request()->getQueryString() }}" class="btn-reset" style="font-size:0.78rem;">
                        <i data-lucide="download" style="width:14px;height:14px;"></i> Export
                    </a>
                </div>
            </div>
            <div class="db-card-body p-0">
                <div class="table-responsive">
                    <table class="table db-table" id="expenses-table" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Ref #</th>
                                <th>Receipt</th>
                                <th>Added By</th>
                                <th>Action</th>
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
<script src="https://unpkg.com/lucide@latest"></script>

<script>
$(document).ready(function() {
    lucide.createIcons();

    // DataTable
    var table = $('#expenses-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[1, 'desc']],
        ajax: {
            url: "{{ route('office-expenses.index') }}",
            data: function(d) {
                d.from_date = $('#filter_from_date').val();
                d.to_date = $('#filter_to_date').val();
                d.category = $('#filter_category').val();
                d.payment_method = $('#filter_payment_method').val();
                d.search = $('#filter_search').val();
            }
        },
        language: { search: "", searchPlaceholder: "Search expenses..." },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'expense_date_formatted', name: 'expense_date' },
            { data: 'category_badge', name: 'category', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'amount_formatted', name: 'amount', orderable: false, searchable: false },
            { data: 'payment_method_badge', name: 'payment_method', orderable: false, searchable: false },
            { data: 'reference_number', name: 'reference_number', render: function(data) { return data ? data : '<span class="text-muted">-</span>'; } },
            { data: 'receipt_indicator', name: 'receipt_path', orderable: false, searchable: false },
            { data: 'creator_name', name: 'created_by', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        drawCallback: function() { lucide.createIcons(); }
    });

    $('.dataTables_filter input').addClass('form-control').css({'border-radius': '8px', 'padding': '0.45rem 0.75rem'});
    $('.dataTables_length select').addClass('form-select').css('border-radius', '8px');

    $('#btn-apply-filters').on('click', function() { table.ajax.reload(); });

    $('#btn-reset-filters').on('click', function() {
        $('#filter_from_date').val('');
        $('#filter_to_date').val('');
        $('#filter_category').val('').trigger('change');
        $('#filter_payment_method').val('').trigger('change');
        $('#filter_search').val('');
        table.ajax.reload();
    });

    $('#filter_search').on('keypress', function(e) {
        if (e.which === 13) { table.ajax.reload(); }
    });
});
</script>
@endsection