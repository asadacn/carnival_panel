@extends('layouts.app')
@section('title')
    Cable Return Management
@endsection
@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                <div>
                    <h1 class="page-title-heading">
                        <span class="page-title-icon"><i class="fas fa-network-wired"></i></span>
                        Cable Return Management
                    </h1>
                    <p class="page-subtitle">Track, manage and reconcile equipment returns for closed client accounts.</p>
                </div>
                <div class="section-header-breadcrumb d-flex align-items-center gap-2">
                    <a href="{{ route('clients.index') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i><span>Back to Clients</span>
                    </a>
                    <button type="button" class="btn btn-refresh" id="btn-refresh-page" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        @include('stisla-templates::common.errors')

        <div class="section-body">

            <div class="kpi-band">
                <div class="kpi-card kpi-primary">
                    <div class="kpi-card-inner">
                        <div class="kpi-icon-wrap"><i class="fas fa-user-slash"></i></div>
                        <div class="kpi-meta">
                            <span class="kpi-label">Total Closed</span>
                            <span class="kpi-value">{{ $totalClosed }}</span>
                            <span class="kpi-foot">Accounts in queue</span>
                        </div>
                    </div>
                    <div class="kpi-glow"></div>
                </div>

                @php
                    $cablePct   = $totalClosed > 0 ? round(($cableReturnedCount / $totalClosed) * 100) : 0;
                    $cableClass = $cablePct >= 80 ? 'kpi-success' : ($cablePct >= 50 ? 'kpi-warning' : 'kpi-danger');
                @endphp
                <div class="kpi-card {{ $cableClass }}">
                    <div class="kpi-card-inner">
                        <div class="kpi-icon-wrap"><i class="fas fa-plug"></i></div>
                        <div class="kpi-meta">
                            <span class="kpi-label">Cable Returned</span>
                            <span class="kpi-value">{{ $cableReturnedCount }}<small>/{{ $totalClosed }}</small></span>
                            <div class="kpi-progress">
                                <div class="kpi-progress-bar" style="width: {{ $cablePct }}%"></div>
                            </div>
                            <span class="kpi-foot">{{ $cablePct }}% reconciliation complete</span>
                        </div>
                    </div>
                    <div class="kpi-glow"></div>
                </div>

                <div class="kpi-card kpi-danger">
                    <div class="kpi-card-inner">
                        <div class="kpi-icon-wrap"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="kpi-meta">
                            <span class="kpi-label">Cable Pending</span>
                            <span class="kpi-value">{{ $cablePendingCount }}</span>
                            <span class="kpi-foot">Awaiting return</span>
                        </div>
                    </div>
                    <div class="kpi-glow"></div>
                </div>

                @php
                    $onuPct   = $totalClosed > 0 ? round(($onuReturnedCount / $totalClosed) * 100) : 0;
                    $onuClass = $onuPct >= 80 ? 'kpi-success' : ($onuPct >= 50 ? 'kpi-warning' : 'kpi-danger');
                @endphp
                <div class="kpi-card {{ $onuClass }}">
                    <div class="kpi-card-inner">
                        <div class="kpi-icon-wrap"><i class="fas fa-router"></i></div>
                        <div class="kpi-meta">
                            <span class="kpi-label">ONU Returned</span>
                            <span class="kpi-value">{{ $onuReturnedCount }}<small>/{{ $totalClosed }}</small></span>
                            <div class="kpi-progress">
                                <div class="kpi-progress-bar" style="width: {{ $onuPct }}%"></div>
                            </div>
                            <span class="kpi-foot">{{ $onuPct }}% reconciliation complete</span>
                        </div>
                    </div>
                    <div class="kpi-glow"></div>
                </div>
            </div>

            <div class="pro-card mb-3">
                <div class="pro-card-header">
                    <div class="pro-card-title">
                        <i class="fas fa-sliders-h"></i><span>Filter &amp; Search</span>
                    </div>
                    <div class="pro-card-meta">
                        <span class="filter-meta-pill"><i class="fas fa-database"></i> <span id="record-count">{{ $totalClosed }}</span> records</span>
                    </div>
                </div>
                <div class="pro-card-body">
                    <div class="pro-filter-row">
                        <div class="pro-filter-group">
                            <label class="pro-filter-label">Status</label>
                            <div class="pro-chip-set" id="quick-filter-chips">
                                <button type="button" class="pro-chip active" data-filter-type="all"><i class="fas fa-layer-group"></i> All</button>
                                <button type="button" class="pro-chip chip-success" data-filter-type="cable" data-filter-value="returned"><i class="fas fa-plug"></i> Cable Returned</button>
                                <button type="button" class="pro-chip chip-danger" data-filter-type="cable" data-filter-value="not_returned"><i class="fas fa-exclamation-circle"></i> Cable Pending</button>
                                <button type="button" class="pro-chip chip-success" data-filter-type="onu" data-filter-value="returned"><i class="fas fa-router"></i> ONU Returned</button>
                                <button type="button" class="pro-chip chip-warning" data-filter-type="onu" data-filter-value="not_returned"><i class="fas fa-exclamation-circle"></i> ONU Pending</button>
                                <button type="button" class="pro-chip chip-dark" data-filter-type="both_pending"><i class="fas fa-bolt"></i> Both Pending</button>
                            </div>
                        </div>

                        <div class="pro-filter-side">
                            <div class="pro-filter-field">
                                <label class="pro-filter-label">ISP</label>
                                <select id="isp-filter" class="pro-control">
                                    <option value="">All ISPs</option>
                                    <option value="carnival">Carnival</option>
                                    <option value="bijoy">Bijoy</option>
                                </select>
                            </div>
                            <div class="pro-filter-field pro-filter-search">
                                <label class="pro-filter-label">Search</label>
                                <div class="pro-search">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="custom-closed-search" placeholder="Search name, ID, address…">
                                </div>
                            </div>
                            <div class="pro-filter-field pro-filter-actions">
                                <label class="pro-filter-label">&nbsp;</label>
                                <button type="button" id="reset-filters-btn" class="btn btn-pro-reset">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pro-card">
                <div class="pro-card-header">
                    <div class="pro-card-title">
                        <i class="fas fa-table"></i><span>Closed Client Records</span>
                    </div>
                    <div class="pro-card-meta">
                        <span class="pro-legend"><span class="dot dot-success"></span> Returned</span>
                        <span class="pro-legend"><span class="dot dot-warning"></span> Pending</span>
                        <span class="pro-legend"><span class="dot dot-danger"></span> Overdue</span>
                    </div>
                </div>
                <div class="pro-card-body p-0">
                    <div class="pro-table-wrap">
                        <table class="table pro-table align-middle w-100" id="closed-clients">
                            <thead>
                                <tr>
                                    <th class="th-index">#</th>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th colspan="2" class="th-group th-group-cable"><i class="fas fa-plug"></i> Cable</th>
                                    <th colspan="2" class="th-group th-group-onu"><i class="fas fa-router"></i> ONU</th>
                                    <th>Closed On</th>
                                    <th>ISP</th>
                                    <th class="th-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="cableReturnModal" tabindex="-1" aria-labelledby="cableReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pro-modal">
                <div class="pro-modal-header pro-modal-header-info">
                    <div class="pro-modal-icon"><i class="fas fa-plug"></i></div>
                    <div>
                        <h5 class="pro-modal-title">Manage Cable Return</h5>
                        <p class="pro-modal-sub">Update return status, date and reason for this client.</p>
                    </div>
                    <button type="button" class="pro-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <form id="cable_return_form">
                    <div class="pro-modal-body">
                        <input type="hidden" id="cable_client_id">
                        <div class="pro-form-group">
                            <label class="pro-form-label">Client Name</label>
                            <input type="text" id="cable_client_name" class="pro-form-control" readonly>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="pro-form-group">
                                    <label class="pro-form-label">Cable Returned</label>
                                    <select id="cable_returned" class="pro-form-control">
                                        <option value="1">Yes — Returned</option>
                                        <option value="0">No — Not Returned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="pro-form-group">
                                    <label class="pro-form-label">Return Date</label>
                                    <input type="date" id="cable_returned_at" class="pro-form-control">
                                </div>
                            </div>
                        </div>
                        <div class="pro-form-group mb-0">
                            <label class="pro-form-label">Reason / Notes</label>
                            <textarea id="cable_return_reason" class="pro-form-control" rows="3" placeholder="e.g. Returned during account closure, Cable damaged, Still pending..."></textarea>
                        </div>
                    </div>
                    <div class="pro-modal-footer">
                        <button type="button" class="btn btn-pro-secondary" data-bs-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancel</button>
                        <button type="button" class="btn btn-pro-info" onclick="saveCableReturn()"><i class="fas fa-check mr-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="onuReturnModal" tabindex="-1" aria-labelledby="onuReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pro-modal">
                <div class="pro-modal-header pro-modal-header-warning">
                    <div class="pro-modal-icon"><i class="fas fa-router"></i></div>
                    <div>
                        <h5 class="pro-modal-title">Manage ONU Return</h5>
                        <p class="pro-modal-sub">Update ONU equipment return for this client.</p>
                    </div>
                    <button type="button" class="pro-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <form id="onu_return_form">
                    <div class="pro-modal-body">
                        <input type="hidden" id="onu_client_id">
                        <div class="pro-form-group">
                            <label class="pro-form-label">Client Name</label>
                            <input type="text" id="onu_client_name" class="pro-form-control" readonly>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="pro-form-group">
                                    <label class="pro-form-label">ONU Returned</label>
                                    <select id="onu_returned" class="pro-form-control">
                                        <option value="1">Yes — Returned</option>
                                        <option value="0">No — Not Returned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="pro-form-group">
                                    <label class="pro-form-label">Return Date</label>
                                    <input type="date" id="onu_returned_at" class="pro-form-control">
                                </div>
                            </div>
                        </div>
                        <div class="pro-form-group mb-0">
                            <label class="pro-form-label">Reason / Notes</label>
                            <textarea id="onu_return_reason" class="pro-form-control" rows="3" placeholder="e.g. Returned during account closure, ONU damaged, Still pending..."></textarea>
                        </div>
                    </div>
                    <div class="pro-modal-footer">
                        <button type="button" class="btn btn-pro-secondary" data-bs-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancel</button>
                        <button type="button" class="btn btn-pro-warning" onclick="saveOnuReturn()"><i class="fas fa-check mr-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('clients.partials.closed_styles')

@endsection

@section('page_js')
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
@endsection

@section('scripts')
    @include('clients.partials.closed_scripts')
@endsection