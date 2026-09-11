@extends('layouts.app')

@section('title')
    Daily Office Expenses
@endsection

@section('css')
<style>
    .expenses-container {
        background: #f8fafc;
        padding: 2rem 0;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .page-title {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .stat-card {
        border-radius: 1rem;
        border: none;
        padding: 1.5rem;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(0,0,0,0.2);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }

    .stat-bg-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .stat-bg-emerald { background: linear-gradient(135deg, #10b981, #047857); }
    .stat-bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-bg-violet { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }

    .stat-content h6 {
        font-weight: 500;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stat-content h3 {
        font-weight: 800;
        font-size: 2rem;
        margin: 0;
    }

    .stat-icon {
        position: absolute;
        right: 1.5rem;
        bottom: 1.5rem;
        opacity: 0.2;
    }

    .btn-create {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .filter-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .expenses-table-wrapper {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    table.dataTable {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }

    .expenses-table thead {
        background: #f8fafc;
        border-radius: 8px;
    }

    .expenses-table thead th {
        padding: 1rem 1.25rem !important;
        font-weight: 600;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0 !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .expenses-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .expenses-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .expenses-table td {
        padding: 1rem 1.25rem;
        color: #334155;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .expense-badge {
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .expense-amount {
        font-weight: 700;
        color: #1e293b;
    }

    .expense-actions {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .expense-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .expense-action-view {
        background: #eff6ff;
        color: #3b82f6;
    }

    .expense-action-view:hover {
        background: #dbeafe;
        color: #2563eb;
    }

    .expense-action-edit {
        background: #fffbeb;
        color: #d97706;
    }

    .expense-action-edit:hover {
        background: #fef3c7;
        color: #b45309;
    }

    .expense-action-delete {
        background: #fef2f2;
        color: #ef4444;
    }

    .expense-action-delete:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .expense-receipt-link {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }

    .expense-receipt-link:hover {
        color: #4f46e5;
        text-decoration: underline;
    }

    .category-breakdown {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .breakdown-item:last-child {
        border-bottom: none;
    }

    .breakdown-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        color: #334155;
    }

    .breakdown-amount {
        font-weight: 700;
        color: #1e293b;
    }

    .breakdown-percent {
        font-size: 0.85rem;
        color: #64748b;
        margin-left: 1rem;
    }

    .recent-expenses {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .recent-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .recent-date {
        background: #f1f5f9;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        white-space: nowrap;
        min-width: 80px;
        text-align: center;
    }

    .recent-info {
        flex: 1;
        min-width: 0;
    }

    .recent-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .recent-category {
        font-size: 0.8rem;
        color: #64748b;
    }

    .recent-amount {
        font-weight: 700;
        color: #dc2626;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .header-actions {
            justify-content: flex-start;
        }

        .stat-card {
            margin-bottom: 1rem;
        }

        .expenses-table-wrapper {
            padding: 1rem;
        }

        .expenses-table thead th,
        .expenses-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<div class="expenses-container">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i data-lucide="file-text" style="width:32px; height:32px; color:#3b82f6;"></i>
                Daily Office Expenses
            </h1>
            <div class="header-actions">
                <a href="{{ route('office-expenses.report') }}" class="btn btn-outline-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; text-decoration: none; padding: 0.6rem 1.2rem;">
                    <i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i> Report
                </a>
                <a href="{{ route('office-expenses.create') }}" class="btn-create">
                    <i data-lucide="plus" style="width:18px;height:18px;"></i> Add Expense
                </a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-bg-blue">
                    <div class="stat-content">
                        <h6>Total Expenses</h6>
                        <h3>{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['total'], 2) }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="receipt" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-bg-emerald">
                    <div class="stat-content">
                        <h6>This Month</h6>
                        <h3>{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['month'], 2) }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="calendar" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-bg-amber">
                    <div class="stat-content">
                        <h6>Today</h6>
                        <h3>{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['today'], 2) }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="sun" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-bg-violet">
                    <div class="stat-content">
                        <h6>Total Records</h6>
                        <h3>{{ number_format($stats['count']) }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="list" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Filters -->
            <div class="col-xl-4 col-lg-12">
                <div class="filter-card">
                    <h5 class="fw-bold mb-3"><i data-lucide="filter" style="width:20px;height:20px;"></i> Filters</h5>
                    <form id="expense-filter-form" class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">From Date</label>
                            <input type="date" id="filter_from_date" name="from_date" class="form-control" value="{{ $filters['from_date'] }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">To Date</label>
                            <input type="date" id="filter_to_date" name="to_date" class="form-control" value="{{ $filters['to_date'] }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Category</label>
                            <select id="filter_category" name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ $filters['category'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select id="filter_payment_method" name="payment_method" class="form-select">
                                <option value="">All Methods</option>
                                @foreach($paymentMethods as $key => $label)
                                    <option value="{{ $key }}" {{ $filters['payment_method'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Search</label>
                            <input type="text" id="filter_search" name="search" class="form-control" placeholder="Title, description, ref..." value="{{ $filters['search'] }}">
                        </div>
                        <div class="col-12 d-grid gap-2">
                            <button type="button" id="btn-apply-filters" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 0.6rem;">
                                <i data-lucide="search" style="width:18px;height:18px;margin-right:5px;"></i> Apply Filters
                            </button>
                            <button type="button" id="btn-reset-filters" class="btn btn-light" style="border-radius: 8px; padding: 0.6rem;">
                                <i data-lucide="refresh-cw" style="width:18px;height:18px;"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Category Breakdown -->
                <div class="category-breakdown mt-4">
                    <h5 class="fw-bold mb-3"><i data-lucide="pie-chart" style="width:20px;height:20px;"></i> By Category</h5>
                    @if($categoryBreakdown->isEmpty())
                        <p class="text-muted text-center mb-0">No data</p>
                    @else
                        @foreach($categoryBreakdown as $item)
                            @php
                                $total = $stats['total'];
                                $percent = $total > 0 ? number_format(($item->total / $total) * 100, 1) : 0;
                            @endphp
                            <div class="breakdown-item">
                                <div class="breakdown-label">
                                    <span class="expense-badge category-{{ $item->category }}">{{ \App\Models\OfficeExpense::CATEGORIES[$item->category] ?? $item->category }}</span>
                                </div>
                                <div>
                                    <span class="breakdown-amount">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($item->total, 2) }}</span>
                                    <span class="breakdown-percent">({{ $percent }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Main Table -->
            <div class="col-xl-8 col-lg-12">
                <div class="expenses-table-wrapper">
                    <div class="table-responsive">
                        <table class="table expenses-table" id="expenses-table" width="100%">
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

                <!-- Recent Expenses (Mobile) -->
                <div class="recent-expenses d-xl-none mt-4">
                    <h5 class="fw-bold mb-3"><i data-lucide="clock" style="width:20px;height:20px;"></i> Recent Expenses</h5>
                    @if($recentExpenses->isEmpty())
                        <p class="text-muted text-center mb-0">No recent expenses</p>
                    @else
                        @foreach($recentExpenses as $expense)
                            <div class="recent-item">
                                <div class="recent-date">{{ $expense->expense_date->format('d M') }}</div>
                                <div class="recent-info">
                                    <div class="recent-title">{{ $expense->title }}</div>
                                    <div class="recent-category">{{ $expense->category_label }}</div>
                                </div>
                                <div class="recent-amount">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($expense->amount, 2) }}</div>
                            </div>
                        @endforeach
                    @endif
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
            language: {
                search: "",
                searchPlaceholder: "Search expenses..."
            },
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
            drawCallback: function() {
                lucide.createIcons();
            }
        });

        $('.dataTables_filter input').addClass('form-control').css({'border-radius': '6px', 'padding': '0.5rem'});
        $('.dataTables_length select').addClass('form-select').css('border-radius', '6px');

        $('#btn-apply-filters').on('click', function() {
            table.ajax.reload();
        });

        $('#btn-reset-filters').on('click', function() {
            $('#filter_from_date').val('');
            $('#filter_to_date').val('');
            $('#filter_category').val('').trigger('change');
            $('#filter_payment_method').val('').trigger('change');
            $('#filter_search').val('');
            table.ajax.reload();
        });

        // Enter key in search
        $('#filter_search').on('keypress', function(e) {
            if (e.which === 13) {
                table.ajax.reload();
            }
        });
    });
</script>
@endsection