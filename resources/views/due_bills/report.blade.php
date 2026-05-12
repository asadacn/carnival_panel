@extends('layouts.app')

@section('title')
    Due Bills Report
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
    .summary-bg-rose { background: linear-gradient(135deg, #f43f5e, #be123c); }

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
    
    .badge {
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .bg-success { background: #dcfce7 !important; color: #166534 !important; }
    .bg-warning { background: #fef9c3 !important; color: #854d0e !important; }
    .bg-danger { background: #fee2e2 !important; color: #991b1b !important; }
    .bg-secondary { background: #f1f5f9 !important; color: #475569 !important; }

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
                    Due Bills Report
                    <div id="report-subtitle" style="font-size: 0.9rem; font-weight: 500; color: #64748b; margin-top: 2px;">
                        Showing all records
                    </div>
                </div>
            </h1>
            <div class="header-actions">
                <a href="{{ route('due-bills.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600;">
                    <i data-lucide="arrow-left" style="width:18px;height:18px;"></i> Back to Management
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form id="filter-form" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Client</label>
                    <select id="client_id" class="form-select select2">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->username }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partially_paid">Partially Paid</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Month</label>
                    <select id="month" class="form-select">
                        <option value="">All Months</option>
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Year</label>
                    <select id="year" class="form-select">
                        <option value="">All Years</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="button" id="btn-filter" class="btn btn-primary w-100" style="border-radius: 8px; font-weight: 600; padding: 0.6rem;">
                        <i data-lucide="search" style="width:18px;height:18px;margin-right:5px;"></i> Filter
                    </button>
                    <button type="button" id="btn-reset" class="btn btn-light" style="border-radius: 8px; padding: 0.6rem;" title="Reset">
                        <i data-lucide="refresh-cw" style="width:18px;height:18px;"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-card summary-bg-blue">
                    <div class="summary-content">
                        <h6>Total Billed</h6>
                        <h3 id="stat-total-billed">৳ 0.00</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="receipt" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card summary-bg-emerald">
                    <div class="summary-content">
                        <h6>Total Paid</h6>
                        <h3 id="stat-total-paid">৳ 0.00</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="check-circle" style="width:64px; height:64px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card summary-bg-rose">
                    <div class="summary-content">
                        <h6>Total Due</h6>
                        <h3 id="stat-total-due">৳ 0.00</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="alert-triangle" style="width:64px; height:64px;"></i>
                    </div>
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
                            <th>Client</th>
                            <th>Customer ID</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Month/Year</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Status</th>
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
            order: [],
            dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-3"lip>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'buttons-excel',
                    title: function() { return getReportTitle(); },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-csv"></i> CSV',
                    className: 'buttons-csv',
                    title: function() { return getReportTitle(); },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'buttons-pdf',
                    title: function() { return getReportTitle(); },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] },
                    customize: function(doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'buttons-print',
                    title: function() { return getReportTitle(); },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                }
            ],
            ajax: {
                url: "{{ route('due-bills.report') }}",
                data: function (d) {
                    d.client_id = $('#client_id').val();
                    d.status = $('#status').val();
                    d.month = $('#month').val();
                    d.year = $('#year').val();
                },
                dataSrc: function (json) {
                    // Update summary stats
                    $('#stat-total-billed').text('৳ ' + json.totalAmount);
                    $('#stat-total-paid').text('৳ ' + json.totalPaid);
                    $('#stat-total-due').text('৳ ' + json.totalDue);
                    return json.data;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'client_name', name: 'client_name' },
                { data: 'customer_id', name: 'customer_id' },
                { data: 'contact', name: 'contact' },
                { data: 'address', name: 'address' },
                { data: 'month_year', name: 'month_year', searchable: false },
                { data: 'amount', name: 'amount', render: function(data) { return `৳ ${data}`; } },
                { data: 'paid_amount', name: 'paid_amount', render: function(data) { return `৳ ${data}`; } },
                { data: 'remaining', name: 'remaining', render: function(data) { 
                    return `<span class="fw-bold ${data > 0 ? 'text-danger' : 'text-success'}">৳ ${data}</span>`; 
                }},
                { data: 'status_badge', name: 'status', orderable: false, searchable: false }
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
            var client = $('#client_id option:selected').text();
            var status = $('#status option:selected').text();
            var month = $('#month option:selected').text();
            var year = $('#year option:selected').val();

            var parts = [];
            if ($('#client_id').val()) parts.push("Client: " + client);
            if ($('#status').val()) parts.push("Status: " + status);
            
            var period = "";
            if ($('#month').val() && $('#year').val()) {
                period = "Period: " + month + " " + year;
            } else if ($('#year').val()) {
                period = "Year: " + year;
            } else if ($('#month').val()) {
                period = "Month: " + month;
            }
            
            if (period) parts.push(period);

            var subtitle = parts.length > 0 ? parts.join(" | ") : "Showing all records";
            $('#report-subtitle').text(subtitle);
        }

        function getReportTitle() {
            var subtitle = $('#report-subtitle').text();
            return "Due Bills Report" + (subtitle !== "Showing all records" ? " (" + subtitle + ")" : "");
        }
    });
</script>
@endsection
