@extends('layouts.app')
@section('title')
    Closed Clients — Cable Return Management
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1><i class="fas fa-user-slash me-2"></i>Closed Clients — Cable Return Management</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary form-btn">
                    <i class="fas fa-arrow-left"></i> Back to Client List
                </a>
            </div>
        </div>

        @include('stisla-templates::common.errors')

        <div class="section-body">

            <!-- ===== Professional Summary Stats Cards ===== -->
            <div class="row mb-4 g-3">

                <!-- Total Closed -->
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card stat-card-navy shadow-sm h-100">
                        <div class="stat-card-body">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-user-slash stat-icon"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Closed Clients</span>
                                <span class="stat-value">{{ $totalClosed }}</span>
                                <span class="stat-subtext">In the closed list</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cable Returned -->
                @php
                    $cablePct = $totalClosed > 0 ? round(($cableReturnedCount / $totalClosed) * 100) : 0;
                    $cableClass = $cablePct < 50 ? 'stat-card-red' : ($cablePct < 80 ? 'stat-card-amber' : 'stat-card-green');
                @endphp
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card {{ $cableClass }} shadow-sm h-100">
                        <div class="stat-card-body">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-plug stat-icon"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Cable Returned</span>
                                <span class="stat-value">{{ $cableReturnedCount }}</span>
                                <div class="progress progress-xs mt-1">
                                    <div class="progress-bar" style="width: {{ $cablePct }}%;"></div>
                                </div>
                                <span class="stat-subtext">{{ $cablePct }}% of total</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cable Pending -->
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card stat-card-red shadow-sm h-100">
                        <div class="stat-card-body">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-exclamation-triangle stat-icon"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Cable Pending</span>
                                <span class="stat-value">{{ $cablePendingCount }}</span>
                                <span class="stat-subtext">Awaiting return</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ONU Returned -->
                @php
                    $onuPct = $totalClosed > 0 ? round(($onuReturnedCount / $totalClosed) * 100) : 0;
                    $onuClass = $onuPct < 50 ? 'stat-card-red' : ($onuPct < 80 ? 'stat-card-amber' : 'stat-card-green');
                @endphp
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card {{ $onuClass }} shadow-sm h-100">
                        <div class="stat-card-body">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-undo stat-icon"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">ONU Returned</span>
                                <span class="stat-value">{{ $onuReturnedCount }}</span>
                                <div class="progress progress-xs mt-1">
                                    <div class="progress-bar" style="width: {{ $onuPct }}%;"></div>
                                </div>
                                <span class="stat-subtext">{{ $onuPct }}% of total</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== Filter & Search Toolbar ===== -->
            <div class="card border-0 shadow-sm mb-3 corporate-card">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-center">

                        <!-- Quick Filter Chips -->
                        <div class="col-lg-7 col-md-12">
                            <div class="d-flex align-items-center gap-2 flex-wrap" id="quick-filter-chips">
                                <span class="text-muted small fw-bold text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">
                                    <i class="fas fa-filter text-primary me-1"></i> Filters:
                                </span>
                                <button type="button" class="btn btn-sm corporate-chip active" data-filter-type="all">
                                    <i class="fas fa-layer-group"></i> All
                                </button>
                                <button type="button" class="btn btn-sm corporate-chip" data-filter-type="cable" data-filter-value="returned">
                                    <i class="fas fa-plug"></i> Cable Returned
                                </button>
                                <button type="button" class="btn btn-sm corporate-chip" data-filter-type="cable" data-filter-value="not_returned">
                                    <i class="fas fa-exclamation-circle"></i> Cable Pending
                                </button>
                                <button type="button" class="btn btn-sm corporate-chip" data-filter-type="onu" data-filter-value="returned">
                                    <i class="fas fa-undo"></i> ONU Returned
                                </button>
                                <button type="button" class="btn btn-sm corporate-chip" data-filter-type="onu" data-filter-value="not_returned">
                                    <i class="fas fa-exclamation-circle"></i> ONU Pending
                                </button>
                                <button type="button" class="btn btn-sm corporate-chip" data-filter-type="both_pending">
                                    <i class="fas fa-bolt"></i> Both Pending
                                </button>
                            </div>
                        </div>

                        <!-- ISP Filter + Search + Reset -->
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <select id="isp-filter" class="form-select form-select-sm corporate-select">
                                <option value="">All ISPs</option>
                                <option value="carnival">Carnival</option>
                                <option value="bijoy">Bijoy</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="input-group">
                                <input type="text" id="custom-closed-search" class="form-control corporate-input" placeholder="Search..." style="font-size: 0.85rem;">
                                <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-6 text-end">
                            <button type="button" id="reset-filters-btn" class="btn btn-sm corporate-reset-btn w-100">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== DataTable Card ===== -->
            <div class="card border-0 shadow-sm corporate-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-corporate align-middle w-100" id="closed-clients" style="font-size: 0.85rem;">
                            <thead>
                                <tr>
                                    <th style="width: 3%;">#</th>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Package</th>
                                    <th style="min-width: 120px;">Cable Status</th>
                                    <th style="min-width: 160px;">Cable Action</th>
                                    <th style="min-width: 120px;">ONU Status</th>
                                    <th style="min-width: 160px;">ONU Action</th>
                                    <th>Closed At</th>
                                    <th>ISP</th>
                                    <th>Due</th>
                                    <th style="width: 12%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables populates via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Cable Return Modal ===== -->
    <div class="modal fade" id="cableReturnModal" tabindex="-1" aria-labelledby="cableReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content corporate-modal">
                <div class="modal-header modal-header-corporate modal-header-info">
                    <h5 class="modal-title" id="cableReturnLabel">
                        <i class="fas fa-plug me-2"></i>Manage Cable Return
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cable_return_form">
                    <div class="modal-body p-4">
                        <input type="hidden" id="cable_client_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Client Name</label>
                            <input type="text" id="cable_client_name" class="form-control corporate-input" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Cable Returned</label>
                            <select id="cable_returned" class="form-select corporate-select">
                                <option value="1">Yes — Returned</option>
                                <option value="0">No — Not Returned</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Return Date</label>
                            <input type="date" id="cable_returned_at" class="form-control corporate-input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Reason / Notes</label>
                            <textarea id="cable_return_reason" class="form-control corporate-input" rows="3"
                                placeholder="e.g. Returned during account closure, Cable damaged, Still pending..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-info text-white" onclick="saveCableReturn()">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== ONU Return Modal ===== -->
    <div class="modal fade" id="onuReturnModal" tabindex="-1" aria-labelledby="onuReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content corporate-modal">
                <div class="modal-header modal-header-corporate modal-header-warning">
                    <h5 class="modal-title" id="onuReturnLabel">
                        <i class="fas fa-undo me-2"></i>Manage ONU Return
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="onu_return_form">
                    <div class="modal-body p-4">
                        <input type="hidden" id="onu_client_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Client Name</label>
                            <input type="text" id="onu_client_name" class="form-control corporate-input" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">ONU Returned</label>
                            <select id="onu_returned" class="form-select corporate-select">
                                <option value="1">Yes — Returned</option>
                                <option value="0">No — Not Returned</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Return Date</label>
                            <input type="date" id="onu_returned_at" class="form-control corporate-input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-1">Reason / Notes</label>
                            <textarea id="onu_return_reason" class="form-control corporate-input" rows="3"
                                placeholder="e.g. Returned during account closure, ONU damaged, Still pending..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning" onclick="saveOnuReturn()">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* ===== CORPORATE STAT CARDS ===== */
        .stat-card {
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
            background: #fff;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card-navy::before { background: #2563eb; }
        .stat-card-green::before { background: #22c55e; }
        .stat-card-red::before { background: #ef4444; }
        .stat-card-amber::before { background: #f59e0b; }

        .stat-card-body {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 22px !important;
        }
        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-card-navy .stat-icon-wrapper { background: rgba(37, 99, 235, 0.10); }
        .stat-card-green .stat-icon-wrapper { background: rgba(34, 197, 94, 0.10); }
        .stat-card-red .stat-icon-wrapper { background: rgba(239, 68, 68, 0.10); }
        .stat-card-amber .stat-icon-wrapper { background: rgba(245, 158, 11, 0.10); }
        .stat-icon { font-size: 1.3rem; }
        .stat-card-navy .stat-icon { color: #2563eb; }
        .stat-card-green .stat-icon { color: #22c55e; }
        .stat-card-red .stat-icon { color: #ef4444; }
        .stat-card-amber .stat-icon { color: #f59e0b; }
        .stat-content { flex: 1; min-width: 0; }
        .stat-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #94a3b8;
            margin-bottom: 2px;
        }
        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }
        .stat-subtext {
            display: block;
            font-size: 0.72rem;
            color: #cbd5e1;
            margin-top: 2px;
        }
        .progress-xs { height: 4px !important; }
        .progress-bar {
            border-radius: 2px;
            transition: width 0.6s ease;
        }

        /* ===== CORPORATE CHIPS & FILTERS ===== */
        .corporate-chip {
            border-radius: 18px;
            padding: 4px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .corporate-chip:hover {
            background: #f1f5f9;
            color: #334159;
            transform: translateY(-1px);
        }
        .corporate-chip.active {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }
        .corporate-chip.active i { color: #fff !important; }

        /* ===== CORPORATE INPUTS & SELECTS ===== */
        .corporate-input, .corporate-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            font-size: 0.85rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }
        .corporate-input:focus, .corporate-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0 0 !important;
        }
        .corporate-input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }

        /* ===== CORPORATE TABLE ===== */
        .table-corporate {
            margin-bottom: 0;
        }
        .table-corporate thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
            padding: 12px 14px;
            white-space: nowrap;
        }
        .table-corporate tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            background: #fff;
        }
        .table-corporate tbody tr:hover td {
            background: #f8fafc;
        }
        .table-corporate tbody tr.row-cable-pending td.cable_status {
            background: rgba(239, 68, 68, 0.03);
        }
        .table-corporate tbody tr.row-onu-pending td.onu_status {
            background: rgba(245, 158, 11, 0.03);
        }
        .table-corporate tbody tr.row-both-pending td.cable_status,
        .table-corporate tbody tr.row-both-pending td.onu_status {
            background: rgba(239, 68, 68, 0.05);
        }

        /* ===== CORPORATE CARD ===== */
        .corporate-card {
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }

        /* ===== CORPORATE MODAL ===== */
        .corporate-modal {
            border-radius: 12px;
            overflow: hidden;
        }
        .corporate-reset-btn {
            border-radius: 8px;
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .corporate-reset-btn:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fca5a5;
        }
        .modal-header-corporate {
            padding: 14px 22px;
            border: none;
        }
        .modal-header-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .modal-header-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .modal-header-corporate .modal-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #fff;
        }
        .modal-header-corporate .btn-close-white {
            color: #fff;
            opacity: 0.8;
        }
        .modal-header-corporate .btn-close-white:hover {
            opacity: 1;
        }

        /* ===== ACTION BUTTONS ===== */
        .btn-group-sm .btn {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .btn-group-sm .btn:hover {
            transform: translateY(-1px);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .table-corporate thead { display: none; }
            .stat-card-body { padding: 14px 16px !important; }
            .stat-icon { font-size: 1.1rem; }
            .stat-value { font-size: 1.3rem; }
        }
    </style>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script>
        // ===== CSRF SETUP =====
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ===== DATA TABLES =====
        let table;

        $(function() {
            table = $('#closed-clients').DataTable({
                pageLength: 25,
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                searching: true,
                dom: '<"row"<"col-sm-12"tr>>' +
                     '<"row mt-2"<"col-sm-6"i><"col-sm-6 text-end"p>>',
                ajax: {
                    url: "{{ route('clients.closed') }}",
                    data: function(d) {
                        d.isp_filter    = $('#isp-filter').val();
                        d.cable_filter  = window.currentCableFilter || '';
                        d.onu_filter    = window.currentOnuFilter || '';
                    }
                },
                columns: [
                    { data: 'DT_RowIndex',           name: 'DT_RowIndex',              searchable: false, orderable: false },
                    { data: 'username',              name: 'username' },
                    { data: 'name',                  name: 'name' },
                    { data: 'contact',               name: 'contact' },
                    { data: 'address',               name: 'address' },
                    { data: 'package',               name: 'package' },
                    { data: 'cable_status',          name: 'cable_status',              searchable: false, orderable: false },
                    { data: 'cable_action',          name: 'cable_action',              searchable: false, orderable: false },
                    { data: 'onu_status',            name: 'onu_status',                searchable: false, orderable: false },
                    { data: 'onu_action',            name: 'onu_action',                searchable: false, orderable: false },
                    { data: 'closed_at_formatted',   name: 'closed_at_formatted',     searchable: false },
                    { data: 'isp_code',              name: 'isp_code' },
                    { data: 'total_due',             name: 'total_due',                 searchable: false, orderable: false },
                    { data: 'action',                name: 'action',                    searchable: false, orderable: false, className: 'text-end' },
                ],
                order: [[0, 'asc']],
                drawCallback: function(settings) {
                    $('#closed-clients tbody tr').each(function() {
                        const cableHtml = $(this).find('td.cable_status').html();
                        const onuHtml = $(this).find('td.onu_status').html();
                        if (cableHtml && !cableHtml.includes('Returned')) {
                            $(this).addClass('row-cable-pending');
                        }
                        if (onuHtml && !onuHtml.includes('Returned')) {
                            $(this).addClass('row-onu-pending');
                        }
                    });
                }
            });
        });

        // ===== SEARCH =====
        $('#custom-closed-search').on('keyup input', function() {
            table.search($(this).val()).draw();
        });

        // ===== ISP FILTER =====
        $('#isp-filter').on('change', function() {
            $('.corporate-chip').removeClass('active');
            $('.corporate-chip[data-filter-type="all"]').addClass('active');
            table.draw();
        });

        // ===== QUICK CHIP FILTERS =====
        window.currentCableFilter = '';
        window.currentOnuFilter = '';

        $('.corporate-chip').on('click', function() {
            const isAll = $(this).data('filter-type') === 'all';
            if (isAll) {
                $('#isp-filter').val('');
            }
            $('.corporate-chip').removeClass('active');
            $(this).addClass('active');

            const type = $(this).data('filter-type');
            const val  = $(this).data('filter-value') || '';

            window.currentCableFilter = '';
            window.currentOnuFilter = '';

            if (type === 'cable')         window.currentCableFilter = val;
            else if (type === 'onu')      window.currentOnuFilter = val;
            else if (type === 'both_pending') {
                window.currentCableFilter = 'not_returned';
                window.currentOnuFilter = 'not_returned';
            }

            table.draw();
        });

        // ===== RESET FILTERS =====
        $('#reset-filters-btn').on('click', function() {
            $('#custom-closed-search').val('');
            table.search('');
            $('#isp-filter').val('');
            window.currentCableFilter = '';
            window.currentOnuFilter = '';
            $('.corporate-chip').removeClass('active');
            $('.corporate-chip[data-filter-type="all"]').addClass('active');
            table.ajax.reload();
        });

        // ===== REMOVE FROM CLOSED LIST =====
        window.removeFromClosedList = function(clientId, clientName) {
            Swal.fire({
                title: 'Remove from Closed List?',
                text: '"' + clientName + '" will be removed from the closed clients list.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#6563f1',
            }).then((result) => {
                if (!result.isConfirmed) return;
                Swal.showLoading();
                $.ajax({
                    url: "{{ url('clients') }}/" + clientId + "/unclose",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not remove client' });
                        }
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                    }
                });
            });
        };

        // ===== QUICK TOGGLE RETURN (inline buttons) =====
        window.quickToggleReturn = function(clientId, type, returnStatus, clientName) {
            const isCable = type === 'cable';
            const endpoint = isCable
                ? "{{ url('clients') }}/" + clientId + "/cable-return"
                : "{{ url('clients') }}/" + clientId + "/onu-return";

            const fieldName = isCable ? 'cable_returned' : 'onu_returned';
            const reasonField = isCable ? 'cable_return_reason' : 'onu_return_reason';
            const dateField = isCable ? 'cable_returned_at' : 'onu_returned_at';

            if (returnStatus === 1) {
                // Marking as returned — auto-fill date and default reason
                const reason = (isCable ? 'Cable' : 'ONU') + ' marked as returned via quick toggle';
                const dateVal = new Date().toISOString().split('T')[0];
                submitQuickReturn(clientId, endpoint, fieldName, reasonField, dateField, 1, reason, dateVal);
            } else {
                // Marking as not returned — require reason via SweetAlert prompt
                Swal.fire({
                    title: 'Reason Required',
                    html: '<p class="mb-2">Why is the ' + (isCable ? 'cable' : 'ONU') + ' not being returned for</p>' +
                          '<p class="fw-bold mb-3 text-primary">"' + clientName + '"?</p>' +
                          '<textarea id="swal-reason" class="form-control" rows="3" placeholder="e.g. Client unreachable, Equipment damaged, Still pending..."></textarea>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    preSubmit: () => {
                        const r = $('#swal-reason').val();
                        if (!r || !r.trim()) {
                            Swal.showValidationMessage('Reason is required');
                        }
                        return r;
                    }
                }).then((inputResult) => {
                    if (inputResult.isConfirmed && inputResult.value) {
                        submitQuickReturn(clientId, endpoint, fieldName, reasonField, dateField, 0, inputResult.value.trim(), null);
                    }
                });
            }
        };

        function submitQuickReturn(clientId, endpoint, fieldName, reasonField, dateField, returned, reason, dateVal) {
            const equipment = fieldName.includes('cable') ? 'Cable' : 'ONU';
            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while we update the ' + equipment + ' return status.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: endpoint,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            [fieldName]: returned ? '1' : '0',
                            [reasonField]: reason || null,
                            [dateField]: dateVal || null,
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1200,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not update status' });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                        }
                    });
                }
            });
        }

        // ===== CABLE RETURN MODAL =====
        window.openCableReturnModal = function(clientId, clientName, cableReturned, cableReturnReason) {
            $('#cable_client_id').val(clientId);
            $('#cable_client_name').val(clientName);
            $('#cable_returned').val(cableReturned ? '1' : '0');
            $('#cable_returned_at').val('');
            $('#cable_return_reason').val(cableReturnReason || '');

            const today = new Date().toISOString().split('T')[0];
            $('#cable_returned_at').val(today);

            if (cableReturned) {
                $('#cable_returned').val('1');
            }

            const modal = new bootstrap.Modal(document.getElementById('cableReturnModal'));
            modal.show();
        };

        window.saveCableReturn = function() {
            const clientId = $('#cable_client_id').val();
            const returned = $('#cable_returned').val();
            const reason = $('#cable_return_reason').val();
            submitCableReturn(clientId, returned, reason);
        };

        function submitCableReturn(clientId, returned, reason) {
            const returnDate = $('#cable_returned_at').val();
            const modalEl = document.getElementById('cableReturnModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();
            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while we update the cable return status.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: "{{ url('clients') }}/" + clientId + "/cable-return",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            cable_returned: returned,
                            cable_returned_at: returnDate || null,
                            cable_return_reason: reason || null,
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not update cable status' });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                        }
                    });
                }
            });
        }

        // ===== ONU RETURN MODAL =====
        window.openOnuReturnModal = function(clientId, clientName, onuReturned, onuReturnReason) {
            $('#onu_client_id').val(clientId);
            $('#onu_client_name').val(clientName);
            $('#onu_returned').val(onuReturned ? '1' : '0');
            $('#onu_returned_at').val('');
            $('#onu_return_reason').val(onuReturnReason || '');

            const today = new Date().toISOString().split('T')[0];
            $('#onu_returned_at').val(today);

            if (onuReturned) {
                $('#onu_returned').val('1');
            }

            const modal = new bootstrap.Modal(document.getElementById('onuReturnModal'));
            modal.show();
        };

        window.saveOnuReturn = function() {
            const clientId = $('#onu_client_id').val();
            const returned = $('#onu_returned').val();
            const reason = $('#onu_return_reason').val();
            submitOnuReturn(clientId, returned, reason);
        };

        function submitOnuReturn(clientId, returned, reason) {
            const returnDate = $('#onu_returned_at').val();
            const modalEl = document.getElementById('onuReturnModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();
            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while we update the ONU return status.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: "{{ url('clients') }}/" + clientId + "/onu-return",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            onu_returned: returned,
                            onu_returned_at: returnDate || null,
                            onu_return_reason: reason || null,
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not update ONU status' });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                        }
                    });
                }
            });
        }

        // ===== ADD TO CLOSED FROM CLIENT LIST =====
        window.addToClosedList = function(clientId, clientName) {
            Swal.fire({
                title: 'Close Client?',
                text: '"' + clientName + '" will be added to the closed clients list for cable return management.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Close Client',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
            }).then((result) => {
                if (!result.isConfirmed) return;
                Swal.showLoading();
                $.ajax({
                    url: "{{ url('clients') }}/" + clientId + "/close",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.hideLoading();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Client Closed!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.href = "{{ route('clients.closed') }}";
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not close client' });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                    }
                });
            });
        };
    </script>
@endsection
