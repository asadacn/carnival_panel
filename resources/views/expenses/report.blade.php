@extends('layouts.app')

@section('title')
    Expense Report
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
<style>
    .report-container { padding: 2rem 0; }

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

    .filter-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .summary-card {
        border-radius: 1rem;
        border: none;
        padding: 1.5rem;
        color: white;
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .summary-bg-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .summary-bg-emerald { background: linear-gradient(135deg, #10b981, #047857); }
    .summary-bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .summary-bg-violet { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }

    .summary-content h6 {
        font-weight: 500;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    .summary-content h3 {
        font-weight: 800;
        font-size: 1.75rem;
        margin: 0;
    }
    .summary-icon {
        position: absolute;
        right: 1rem;
        bottom: 1rem;
        opacity: 0.15;
    }

    .report-table-wrapper {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .dt-buttons {
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .dt-button {
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        border: none !important;
        transition: all 0.2s ease !important;
    }
    .buttons-excel { background: #10b981 !important; color: white !important; }
    .buttons-csv { background: #3b82f6 !important; color: white !important; }
    .buttons-pdf { background: #f43f5e !important; color: white !important; }
    .buttons-print { background: #64748b !important; color: white !important; }

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

    .breakdown-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
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

    @media print {
        .filter-card, .page-header, .dt-buttons, .main-sidebar, .main-footer, .navbar {
            display: none !important;
        }
        .main-content { padding: 0 !important; margin: 0 !important; }
        .report-table-wrapper { box-shadow: none !important; padding: 0 !important; }
        .summary-card { color: black !important; border: 1px solid #ddd !important; background: none !important; }
        .summary-icon { display: none !important; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="report-container">

        <div class="page-header">
            <h1 class="page-title">
                <i data-lucide="bar-chart-3" style="width:32px; height:32px; color:#3b82f6;"></i>
                <div>
                    Expense Report
                    <div id="report-subtitle" style="font-size: 0.9rem; font-weight: 500; color: #64748b; margin-top: 2px;">
                        Showing all records
                    </div>
                </div>
            </h1>
            <div class="header-actions">
                <a href="{{ route('office-expenses.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600;">
                    <i data-lucide="arrow-left" style="width:18px;height:18px;"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form id="filter-form" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" id="filter_from_date" name="from_date" class="form-control" value="{{ $filters['from_date'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" id="filter_to_date" name="to_date" class="form-control" value="{{ $filters['to_date'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Category</label>
                    <select id="filter_category" name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ $filters['category'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Payment Method</label>
                    <select id="filter_payment_method" name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ $filters['payment_method'] == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Search</label>
                    <input type="text" id="filter_search" name="search" class="form-control" placeholder="Title, description, ref..." value="{{ $filters['search'] }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="button" id="btn-filter" class="btn btn-primary w-100" style="border-radius: 8px; font-weight: 600; padding: 0.6rem;">
                        <i data-lucide="search" style="width:18px;height:18px;margin-right:5px;"></i> Filter
                    </button>
                    <button type="button" id="btn-reset" class="btn btn-light" style="border-radius: 8px; padding: 0.6rem;" title="Reset">
                        <i data-lucide="refresh-cw" style="width:18px;height:18px;"></i>
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <a href="{{ route('office-expenses.export') . '?' . request()->getQueryString() }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; font-weight: 600; padding: 0.6rem; text-decoration: none;">
                        <i data-lucide="download" style="width:18px;height:18px;"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="summary-card summary-bg-blue">
                    <div class="summary-content">
                        <h6>Total Expenses</h6>
                        <h3 id="stat-total">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['total'], 2) }}</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="receipt" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card summary-bg-emerald">
                    <div class="summary-content">
                        <h6>This Month</h6>
                        <h3 id="stat-month">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['month'], 2) }}</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="calendar" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card summary-bg-amber">
                    <div class="summary-content">
                        <h6>Today</h6>
                        <h3 id="stat-today">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($stats['today'], 2) }}</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="sun" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card summary-bg-violet">
                    <div class="summary-content">
                        <h6>Total Records</h6>
                        <h3 id="stat-count">{{ number_format($stats['count']) }}</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="list" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="breakdown-card">
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
            <div class="col-lg-6">
                <div class="breakdown-card">
                    <h5 class="fw-bold mb-3"><i data-lucide="credit-card" style="width:20px;height:20px;"></i> By Payment Method</h5>
                    @if($paymentBreakdown->isEmpty())
                        <p class="text-muted text-center mb-0">No data</p>
                    @else
                        @foreach($paymentBreakdown as $item)
                            @php
                                $total = $stats['total'];
                                $percent = $total > 0 ? number_format(($item->total / $total) * 100, 1) : 0;
                            @endphp
                            <div class="breakdown-item">
                                <div class="breakdown-label">
                                    <span class="expense-badge method-{{ $item->payment_method }}">{{ \App\Models\OfficeExpense::PAYMENT_METHODS[$item->payment_method] ?? $item->payment_method }}</span>
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
        </div>

        <!-- Table -->
        <div class="report-table-wrapper">
            <div class="table-responsive">
                <table class="table" id="report-table" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Ref #</th>
                            <th>Notes</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables Buttons JS -->
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
        $('.select2').select2();

        var table = $('#report-table').DataTable({
            processing: true,
            serverSide: true,
            order: [[1, 'desc']],
            dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-3"lip>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'buttons-excel',
                    title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-csv"></i> CSV',
                    className: 'buttons-csv',
                    title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'buttons-pdf',
                    title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] },
                    customize: function(doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'buttons-print',
                    title: function() { return 'Expense Report - ' + new Date().toISOString().split('T')[0]; },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
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
                { data: 'description', name: 'description', render: function(data) { return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '-'; } },
                { data: 'amount_formatted', name: 'amount', orderable: false, searchable: false },
                { data: 'payment_method_badge', name: 'payment_method', orderable: false, searchable: false },
                { data: 'reference_number', name: 'reference_number', render: function(data) { return data ? data : '-'; } },
                { data: 'notes', name: 'notes', render: function(data) { return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '-'; } },
                { data: 'creator_name', name: 'created_by', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "Quick search..."
            },
            drawCallback: function() {
                lucide.createIcons();
            }
        });

        $('#btn-filter').click(function() {
            updateReportSubtitle();
            table.draw();
        });

        $('#btn-reset').click(function() {
            $('#filter-form')[0].reset();
            $('.select2').val('').trigger('change');
            updateReportSubtitle();
            table.draw();
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

        // Enter key in search
        $('#filter_search').on('keypress', function(e) {
            if (e.which === 13) {
                table.draw();
            }
        });
    });
</script>
@endsection