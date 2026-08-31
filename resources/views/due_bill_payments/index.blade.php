@extends('layouts.app')

@section('title')
    Payment Records
@endsection

@section('css')
<style>
    .payments-container { background: #f8f9fa; padding: 2rem 0; }
    .payments-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding: 0 1rem; }
    .payments-header h1 { font-size: 2rem; font-weight: 600; color: #1f2937; margin: 0; }
    .btn-create { background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2); text-decoration: none; }
    .btn-create:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); color: white; }
    .payments-table-wrapper { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
    .payments-table { margin: 0; }
    .payments-table thead { background: #f3f4f6; }
    .payments-table thead th { padding: 1rem 1.25rem; font-weight: 600; color: #6b7280; border: none; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .payments-table tbody tr { border-top: 1px solid #e5e7eb; transition: all 0.2s ease; }
    .payments-table tbody tr:hover { background-color: #f9fafb; }
    .payments-table td { padding: 1rem 1.25rem; color: #374151; font-size: 0.95rem; vertical-align: middle; }
    .amount { font-weight: 600; color: #10b981; }
    .method-badge { display: inline-block; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500; }
    .action-btn { padding: 0.5rem 0.9rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.2s ease; font-weight: 500; text-decoration: none; }
    .btn-view { background: #dbeafe; color: #1e40af; }
    .btn-view:hover { background: #bfdbfe; }
    .btn-delete { background: #fee2e2; color: #991b1b; }
    .btn-delete:hover { background: #fecaca; }
    .status-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .status-paid { background: #dcfce7; color: #166534; }
    .status-partial { background: #fef9c3; color: #854d0e; }
    .status-unpaid { background: #fee2e2; color: #991b1b; }
    .status-overdue { background: #fecaca; color: #7f1d1d; }
    .timestamp-cell { font-size: 0.85rem; }
    .timestamp-relative { font-size: 0.75rem; color: #6b7280; margin-top: 2px; }
    .client-cell { display: flex; align-items: center; gap: 10px; }
    .client-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; flex-shrink: 0; }
    .client-info { min-width: 0; }
    .client-name { font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .client-meta { font-size: 12px; color: #6b7280; margin-top: 2px; }
    .metric-card { border-radius: 1rem; border: none; padding: 1.5rem; color: white; transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative; overflow: hidden; }
    .metric-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -5px rgba(0,0,0,0.2); }
    .metric-card::after { content: ''; position: absolute; top: -50%; right: -30%; width: 150px; height: 150px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; pointer-events: none; }
    .metric-bg-emerald { background: linear-gradient(135deg, #10b981, #047857); }
    .metric-bg-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .metric-bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .metric-bg-violet { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .metric-content h6 { font-weight: 500; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; margin-bottom: 0.5rem; }
    .metric-content h3 { font-weight: 800; font-size: 2rem; margin: 0; }
    .metric-icon { position: absolute; right: 1.5rem; bottom: 1.5rem; opacity: 0.2; }
    .filter-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
    .dt-action-btns { display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .transaction-id { font-family: 'Courier New', monospace; font-weight: 600; font-size: 0.85rem; color: #4b5563; }
    .remaining-positive { color: #dc2626; font-weight: 600; }
    .remaining-zero { color: #16a34a; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="payments-container">
    <div class="container-lg">
        <div class="payments-header">
            <h1>Payment Records</h1>
            <div class="header-actions">
                <a href="{{ route('due-bill-payments.report') }}" class="btn btn-outline-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; text-decoration: none; padding: 0.6rem 1.2rem;">
                    <i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i> View Report
                </a>
                <a href="{{ route('due-bill-payments.create') }}" class="btn-create">
                    <i data-lucide="plus" style="width:18px;height:18px;"></i> Record Payment
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card metric-bg-emerald">
                    <div class="metric-content">
                        <h6>Total Collected</h6>
                        <h3>৳ {{ number_format($totalCollected ?? 0, 0) }}</h3>
                    </div>
                    <div class="metric-icon">
                        <i data-lucide="wallet" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card metric-bg-blue">
                    <div class="metric-content">
                        <h6>This Month</h6>
                        <h3>৳ {{ number_format($monthCollections ?? 0, 0) }}</h3>
                    </div>
                    <div class="metric-icon">
                        <i data-lucide="calendar" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card metric-bg-amber">
                    <div class="metric-content">
                        <h6>Today</h6>
                        <h3>৳ {{ number_format($todayCollections ?? 0, 0) }}</h3>
                    </div>
                    <div class="metric-icon">
                        <i data-lucide="sun" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card metric-bg-violet">
                    <div class="metric-content">
                        <h6>Total Payments</h6>
                        <h3>{{ number_format($totalPayments ?? 0) }}</h3>
                    </div>
                    <div class="metric-icon">
                        <i data-lucide="receipt" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-card mb-4">
            <form id="payment-filter-form" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Client</label>
                    <select id="filter_client_id" class="form-select">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->username }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Payment Method</label>
                    <select id="filter_payment_method" class="form-select">
                        <option value="">All Methods</option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>{{ ucfirst($method) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" id="filter_from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" id="filter_to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="button" id="btn-apply-filters" class="btn btn-primary w-100" style="border-radius: 8px; font-weight: 600; padding: 0.6rem;">
                        <i data-lucide="search" style="width:18px;height:18px;margin-right:5px;"></i> Filter
                    </button>
                    <button type="button" id="btn-reset-filters" class="btn btn-light w-100" style="border-radius: 8px; padding: 0.6rem;">
                        <i data-lucide="refresh-cw" style="width:18px;height:18px;"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <div class="payments-table-wrapper">
            <div class="table-responsive">
                <table class="table payments-table" id="payments-table" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Bill Month</th>
                            <th>Amount</th>
                            <th>Payment Time</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Remaining</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
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

        var table = $('#payments-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [[4, 'desc']],
            ajax: {
                url: "{{ route('due-bill-payments.index') }}",
                data: function(d) {
                    d.client_id = $('#filter_client_id').val();
                    d.payment_method = $('#filter_payment_method').val();
                    d.from_date = $('#filter_from_date').val();
                    d.to_date = $('#filter_to_date').val();
                }
            },
            language: {
                search: "",
                searchPlaceholder: "Search payments..."
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'client_name', name: 'client_name', render: function(data, type, row) {
                    return `
                        <div class="client-cell">
                            <div class="client-avatar" style="background: #dbeafe; color: #1e40af;">
                                ${data ? data.charAt(0).toUpperCase() : '?'}
                            </div>
                            <div class="client-info">
                                <div class="client-name">${data}</div>
                            </div>
                        </div>
                    `;
                }},
                { data: 'bill_month', name: 'bill_month', searchable: false },
                { data: 'amount_formatted', name: 'amount', render: function(data) { return `<span class="amount">৳ ${data}</span>`; } },
                { data: 'payment_date_formatted', name: 'payment_date', render: function(data, type, row) {
                    return `<div class="timestamp-cell">${data}</div>`;
                }},
                { data: 'method_badge', name: 'payment_method', orderable: false, searchable: false },
                { data: 'bill_status', name: 'bill_status', orderable: false, searchable: false, render: function(data, type, row) {
                    var cls = 'status-unpaid';
                    var label = 'Unpaid';
                    if (data === 'paid') { cls = 'status-paid'; label = 'Paid'; }
                    else if (data === 'partially_paid') { cls = 'status-partial'; label = 'Partial'; }
                    else if (data === 'overdue') { cls = 'status-overdue'; label = 'Overdue'; }
                    return `<span class="status-badge ${cls}">${label}</span>`;
                }},
                { data: 'transaction_id', name: 'transaction_id', searchable: false, render: function(data) {
                    return data && data !== '-' ? `<span class="transaction-id">${data}</span>` : '<span class="text-muted">-</span>';
                }},
                { data: 'remaining', name: 'remaining', searchable: false, orderable: false, render: function(data) {
                    var val = parseFloat(data) || 0;
                    var cls = val > 0 ? 'remaining-positive' : 'remaining-zero';
                    return `<span class="${cls}">৳ ${val.toFixed(2)}</span>`;
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    return `
                        <div class="dt-action-btns">
                            <a href="/due-bill-payments/${row.id}" class="action-btn btn-view" title="View Details"><i data-lucide="eye" style="width:16px;height:16px;"></i></a>
                            <a href="/due-bill-payments/${row.id}/invoice" class="action-btn" title="Print Invoice" style="background:#eff6ff;color:#2563eb;"><i data-lucide="printer" style="width:16px;height:16px;"></i></a>
                            <button class="action-btn btn-edit" onclick="editPayment(${row.id})" title="Edit"><i data-lucide="edit-2" style="width:16px;height:16px;"></i></button>
                            <button class="action-btn btn-delete" onclick="deletePayment(${row.id})" title="Delete"><i data-lucide="trash-2" style="width:16px;height:16px;"></i></button>
                        </div>
                    `;
                }}
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
            $('#filter_client_id').val('');
            $('#filter_payment_method').val('');
            $('#filter_from_date').val('');
            $('#filter_to_date').val('');
            table.ajax.reload();
        });
    });

    function viewPayment(id) {
        window.location.href = `/due-bill-payments/${id}`;
    }

    function sharePaymentInvoice(id) {
        window.open(`/due-bill-payments/${id}/invoice`, '_blank');
    }

    function printInvoice(id) {
        window.location.href = `/due-bill-payments/${id}/invoice`;
    }

    function editPayment(id) {
        window.location.href = `/due-bill-payments/${id}/edit`;
    }

    function deletePayment(id) {
        Swal.fire({
            title: 'Delete this payment?',
            text: 'You will not be able to recover this record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/due-bill-payments/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message || 'The payment has been deleted.',
                            icon: 'success',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            $('#payments-table').DataTable().ajax.reload(null, false);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                });
            }
        });
    }
</script>
@endsection
