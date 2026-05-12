@extends('layouts.app')

@section('title')
    Due Bills Management
@endsection

@section('css')
<style>
    .bills-container { padding: 2rem 0; }
    
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
    
    .metric-card {
        border-radius: 1rem;
        border: none;
        padding: 1.5rem;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(0,0,0,0.2);
    }
    .metric-card::after {
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
    .metric-bg-violet { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .metric-bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .metric-bg-rose { background: linear-gradient(135deg, #f43f5e, #be123c); }
    .metric-bg-emerald { background: linear-gradient(135deg, #10b981, #047857); }

    .metric-content h6 {
        font-weight: 500;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    .metric-content h3 {
        font-weight: 800;
        font-size: 2rem;
        margin: 0;
    }
    .metric-icon {
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

    .bills-table-wrapper {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    
    /* DataTable Stylings */
    table.dataTable { border-collapse: collapse !important; border-spacing: 0 !important; }
    .bills-table thead { background: #f8fafc; border-radius: 8px; }
    .bills-table thead th { 
        padding: 1rem 1.25rem !important; 
        font-weight: 600; 
        color: #64748b; 
        border-bottom: 2px solid #e2e8f0 !important; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
    }
    .bills-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease; }
    .bills-table tbody tr:hover { background-color: #f8fafc; }
    .bills-table td { padding: 1rem 1.25rem; color: #334155; font-size: 0.95rem; vertical-align: middle; }
    
    /* Status Badges */
    .badge {
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .bg-success { background: #dcfce7 !important; color: #166534 !important; }
    .bg-warning { background: #fef9c3 !important; color: #854d0e !important; }
    .bg-danger { background: #fee2e2 !important; color: #991b1b !important; }
    .bg-secondary { background: #f1f5f9 !important; color: #475569 !important; }

    /* Action Buttons */
    .dt-action-btns {
        display: flex;
        gap: 0.4rem;
    }
    .action-btn { 
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none; 
        border-radius: 6px; 
        cursor: pointer; 
        transition: all 0.2s ease; 
    }
    .btn-view { background: #eff6ff; color: #3b82f6; }
    .btn-view:hover { background: #dbeafe; color: #2563eb; }
    
    .btn-edit { background: #fffbeb; color: #d97706; }
    .btn-edit:hover { background: #fef3c7; color: #b45309; }
    
    .btn-delete { background: #fef2f2; color: #ef4444; }
    .btn-delete:hover { background: #fee2e2; color: #dc2626; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="bills-container">
        
        <div class="page-header">
            <h1 class="page-title">
                <i data-lucide="receipt" style="width:32px; height:32px; color:#3b82f6;"></i>
                Due Bills Management
            </h1>
            <div class="header-actions">
                <a href="{{ route('due-bills.report') }}" class="btn btn-outline-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; text-decoration: none; padding: 0.6rem 1.2rem;">
                    <i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i> View Report
                </a>
                <a href="{{ route('due-bills.create') }}" class="btn-create">
                    <i data-lucide="plus" style="width:18px;height:18px;"></i> Create Bill
                </a>
            </div>
        </div>
        
        <!-- Dashboard Metrics Row -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('due-bills.index') }}" class="text-decoration-none">
                    <div class="metric-card metric-bg-violet">
                        <div class="metric-content">
                            <h6>Total Due</h6>
                            <h3>৳ {{ number_format($totalDue, 0) }}</h3>
                        </div>
                        <div class="metric-icon">
                            <i data-lucide="wallet" style="width:64px; height:64px;"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('due-bills.index', ['status' => 'unpaid']) }}" class="text-decoration-none">
                    <div class="metric-card metric-bg-amber">
                        <div class="metric-content">
                            <h6>Unpaid Bills</h6>
                            <h3>{{ $unpaidCount }}</h3>
                        </div>
                        <div class="metric-icon">
                            <i data-lucide="file-warning" style="width:64px; height:64px;"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('due-bills.index', ['status' => 'overdue']) }}" class="text-decoration-none">
                    <div class="metric-card metric-bg-rose">
                        <div class="metric-content">
                            <h6>Overdue Bills</h6>
                            <h3>{{ $overdueCount }}</h3>
                        </div>
                        <div class="metric-icon">
                            <i data-lucide="alert-circle" style="width:64px; height:64px;"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('due-bills.index', ['status' => 'paid']) }}" class="text-decoration-none">
                    <div class="metric-card metric-bg-emerald">
                        <div class="metric-content">
                            <h6>Paid This Month</h6>
                            <h3>৳ {{ number_format($paidThisMonth, 0) }}</h3>
                        </div>
                        <div class="metric-icon">
                            <i data-lucide="check-circle" style="width:64px; height:64px;"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="bills-table-wrapper">
            <div class="table-responsive">
                <table class="table bills-table" id="bills-table" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Customer ID</th>
                            <th>Month/Year</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Status</th>
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

        var table = $('#bills-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [],
            ajax: "{{ route('due-bills.index') }}",
            language: {
                search: "",
                searchPlaceholder: "Search bills..."
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'client_name', name: 'client_name', render: function(data, type, row) {
                    return `<div class="fw-bold text-dark">${data}</div>`;
                }},
                { data: 'customer_id', name: 'customer_id', render: function(data, type, row) {
                    if (data && data !== '-') {
                        return `<div class="text-secondary small fw-bold d-flex align-items-center gap-1" style="cursor:pointer; transition: color 0.1s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color=''" onclick="copyToClipboard('${data}')" title="Copy ID">
                                    ${data} <i data-lucide="copy" style="width:12px;height:12px;opacity:0.6;"></i>
                                </div>`;
                    }
                    return `<div class="text-secondary small fw-bold">${data}</div>`;
                }},
                { data: 'month_year', name: 'month_year', searchable: false },
                { data: 'amount', name: 'amount', render: function(data) { return `৳ ${data}`; } },
                { data: 'paid_amount', name: 'paid_amount', searchable: false, render: function(data) { return `৳ ${data}`; } },
                { data: 'remaining', name: 'remaining', searchable: false, render: function(data) { 
                    return `<span class="fw-bold ${data > 0 ? 'text-danger' : 'text-success'}">৳ ${data}</span>`; 
                }},
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    return `
                        <div class="dt-action-btns">
                            <button class="action-btn btn-view" onclick="viewBill(${row.id})" title="View"><i data-lucide="eye" style="width:16px;height:16px;"></i></button>
                            <button class="action-btn" onclick="payBill(${row.id})" title="Pay Now" style="background:#10b981;color:white;"><i data-lucide="banknote" style="width:16px;height:16px;"></i></button>
                            <button class="action-btn btn-edit" onclick="editBill(${row.id})" title="Edit"><i data-lucide="edit-2" style="width:16px;height:16px;"></i></button>
                            <button class="action-btn btn-delete" onclick="deleteBill(${row.id})" title="Delete"><i data-lucide="trash-2" style="width:16px;height:16px;"></i></button>
                        </div>
                    `;
                }}
            ],
            drawCallback: function() {
                // Re-initialize lucide icons for elements created by datatable
                lucide.createIcons();
            }
        });

        // Add some Bootstrap styling classes to Datatable wrapper elements
        $('.dataTables_filter input').addClass('form-control').css({'border-radius': '6px', 'padding': '0.5rem'});
        $('.dataTables_length select').addClass('form-select').css('border-radius', '6px');
    });

    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Copied to clipboard!',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#10b981',
                        color: 'white',
                        iconColor: 'white'
                    });
                }
            });
        } else {
            // Fallback
            var textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("Copy");
            textArea.remove();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Copied to clipboard!',
                    showConfirmButton: false,
                    timer: 1500,
                    background: '#10b981',
                    color: 'white',
                    iconColor: 'white'
                });
            }
        }
    }

    function viewBill(billId) {
        window.location.href = "{{ route('due-bills.show', ':id') }}".replace(':id', billId);
    }
    
    function payBill(billId) {
        Swal.fire({
            title: 'Mark as Paid?',
            text: 'Are you sure you want to mark this bill as fully paid?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Pay Now!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('due-bills.mark-paid', ':id') }}".replace(':id', billId);
            }
        });
    }

    function editBill(billId) {
        window.location.href = "{{ route('due-bills.edit', ':id') }}".replace(':id', billId);
    }

    function deleteBill(billId) {
        Swal.fire({
            title: 'Delete this bill?',
            text: 'You will not be able to recover this record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('due-bills.destroy', ':id') }}".replace(':id', billId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The bill has been deleted.',
                            icon: 'success',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            $('#bills-table').DataTable().ajax.reload(null, false);
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
