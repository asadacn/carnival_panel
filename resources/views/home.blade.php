@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading font-weight-bold d-flex align-items-center gap-2">
            <i data-lucide="layout-dashboard" style="width:22px;height:22px"></i>
            @lang('messages.dashboard_title')
        </h3>
    </div>

    <div class="section-body">

        {{-- ── TOP BAR: Last Updated + Refresh ─────────────────────────── --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-muted mb-0 small d-flex align-items-center gap-1">
                <i data-lucide="clock-3" style="width:14px;height:14px"></i>
                @if($lastUpdated)
                    Data refreshed {{ \Carbon\Carbon::parse($lastUpdated)->diffForHumans() }}
                @else
                    No data recorded yet
                @endif
            </p>
            <a href="?refresh=1" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                <i data-lucide="refresh-cw" style="width:13px;height:13px"></i> Refresh
            </a>
        </div>

        {{-- ── KPI METRIC CARDS ──────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">

            {{-- Total Clients --}}
            <div class="col-6 col-sm-4 col-lg">
                <a href="{{ route('clients.index') }}" class="text-decoration-none">
                    <div class="db-kpi-card" style="--kpi-accent:#6366f1;">
                        <div class="db-kpi-icon" style="background:rgba(99,102,241,.12);">
                            <i data-lucide="users" style="color:#6366f1;width:22px;height:22px;"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">Total Clients</span>
                            <span class="db-kpi-value">{{ $clients->count() }}</span>
                        </div>
                        <div class="db-kpi-arrow"><i data-lucide="chevron-right" style="width:16px;height:16px;color:#6366f1;"></i></div>
                    </div>
                </a>
            </div>

            {{-- Active Clients --}}
            <div class="col-6 col-sm-4 col-lg">
                <a href="{{ route('clients.index') }}" class="text-decoration-none">
                    <div class="db-kpi-card" style="--kpi-accent:#10b981;">
                        <div class="db-kpi-icon" style="background:rgba(16,185,129,.12);">
                            <i data-lucide="user-check" style="color:#10b981;width:22px;height:22px;"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">Active Clients</span>
                            <span class="db-kpi-value">{{ $Active_clients }}</span>
                        </div>
                        <div class="db-kpi-arrow"><i data-lucide="chevron-right" style="width:16px;height:16px;color:#10b981;"></i></div>
                    </div>
                </a>
            </div>

            {{-- Expiring Tomorrow --}}
            <div class="col-6 col-sm-4 col-lg">
                <a href="{{ route('clients.index') }}" class="text-decoration-none">
                    <div class="db-kpi-card" style="--kpi-accent:#f97316;">
                        <div class="db-kpi-icon" style="background:rgba(249,115,22,.12);">
                            <i data-lucide="alert-triangle" style="color:#f97316;width:22px;height:22px;"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">Expiring Tomorrow</span>
                            <span class="db-kpi-value">{{ $expiring_soon->count() }}</span>
                        </div>
                        <div class="db-kpi-arrow"><i data-lucide="chevron-right" style="width:16px;height:16px;color:#f97316;"></i></div>
                    </div>
                </a>
            </div>

            {{-- Expired Today --}}
            <div class="col-6 col-sm-4 col-lg">
                <a href="#todaysExpiredClients" class="text-decoration-none">
                    <div class="db-kpi-card" style="--kpi-accent:#ef4444;">
                        <div class="db-kpi-icon" style="background:rgba(239,68,68,.12);">
                            <i data-lucide="calendar-x" style="color:#ef4444;width:22px;height:22px;"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">Expired Today</span>
                            <span class="db-kpi-value">{{ $expired_today->count() }}</span>
                        </div>
                        <div class="db-kpi-arrow"><i data-lucide="chevron-down" style="width:16px;height:16px;color:#ef4444;"></i></div>
                    </div>
                </a>
            </div>

            {{-- Monthly Expired --}}
            <div class="col-6 col-sm-4 col-lg">
                <a href="{{ route('clients.index') }}" class="text-decoration-none">
                    <div class="db-kpi-card" style="--kpi-accent:#8b5cf6;">
                        <div class="db-kpi-icon" style="background:rgba(139,92,246,.12);">
                            <i data-lucide="calendar-days" style="color:#8b5cf6;width:22px;height:22px;"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">Monthly Expired</span>
                            <span class="db-kpi-value">{{ $expired_this_month->count() }}</span>
                        </div>
                        <div class="db-kpi-arrow"><i data-lucide="chevron-right" style="width:16px;height:16px;color:#8b5cf6;"></i></div>
                    </div>
                </a>
            </div>

            {{-- SMS Balance --}}
            <div class="col-6 col-sm-4 col-lg">
                <a href="{{ route('sms_log') }}" class="text-decoration-none">
                    <div class="db-kpi-card" style="--kpi-accent:#0ea5e9;">
                        <div class="db-kpi-icon" style="background:rgba(14,165,233,.12);">
                            <i data-lucide="message-circle" style="color:#0ea5e9;width:22px;height:22px;"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">SMS Balance</span>
                            <span class="db-kpi-value" style="font-size:1rem;">{{ sms_balance() }} ৳</span>
                        </div>
                        <div class="db-kpi-arrow"><i data-lucide="chevron-right" style="width:16px;height:16px;color:#0ea5e9;"></i></div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ── ROW 1: Client Analytics Chart + Commission ──────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-12">
                <div class="db-card">
                    <div class="db-card-header">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="bar-chart-3" style="width:18px;height:18px;color:#6366f1;"></i>
                                Client Analytics
                            </div>
                            <p class="text-muted small mb-0">Active client distribution by package with monthly revenue overlay.</p>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="db-stat-pill">
                                <i data-lucide="users" style="width:14px;height:14px;color:#6366f1;"></i>
                                <span class="text-muted">Active:</span>
                                <strong>{{ $Active_clients }}</strong>
                            </div>
                            <div class="db-stat-pill">
                                <i data-lucide="banknote" style="width:14px;height:14px;color:#f59e0b;"></i>
                                <span class="text-muted">Revenue:</span>
                                <strong>{{ takaFormat($clients_by_package->sum('total_amount')) }} ৳</strong>
                            </div>
                        </div>
                    </div>
                    <div class="db-card-tabs">
                        <ul class="nav nav-pills" id="analyticsTab" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#at-chart" id="chart-tab"><i data-lucide="bar-chart-2" style="width:14px;height:14px" class="me-1"></i>Chart</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#at-table" id="table-tab"><i data-lucide="table" style="width:14px;height:14px" class="me-1"></i>Table ({{ $Active_clients }})</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#at-commission" id="commission-tab"><i data-lucide="lock" style="width:14px;height:14px" class="me-1"></i>Commission</button></li>
                        </ul>
                    </div>
                    <div class="db-card-body">
                        <div class="tab-content" id="analyticsTabContent">

                            {{-- Chart Tab --}}
                            <div class="tab-pane fade show active" id="at-chart" role="tabpanel">
                                <div style="height:320px;"><canvas id="clientsChart"></canvas></div>
                            </div>

                            {{-- Table Tab --}}
                            <div class="tab-pane fade" id="at-table" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table db-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Package</th>
                                                <th class="text-center">Clients</th>
                                                <th class="text-end">Monthly Value (৳)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $pkgTotal = 0; @endphp
                                            @foreach($clients_by_package as $pkg)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold text-dark">{{ $pkg->package }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge" style="background:rgba(99,102,241,.1);color:#6366f1;font-weight:600;">{{ $pkg->total_clients }}</span>
                                                </td>
                                                <td class="text-end fw-semibold text-success">{{ takaFormat($pkg->total_amount) }}</td>
                                            </tr>
                                            @php $pkgTotal += $pkg->total_amount; @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:#f8fafc;">
                                                <td class="fw-bold text-dark">Total</td>
                                                <td class="text-center fw-bold text-dark">{{ $Active_clients }}</td>
                                                <td class="text-end fw-bold text-success">{{ takaFormat($pkgTotal) }} ৳</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            {{-- Commission Tab --}}
                            <div class="tab-pane fade" id="at-commission" role="tabpanel">
                                <div id="unlock-area" class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height:280px;">
                                    <div class="mb-3" style="width:56px;height:56px;background:rgba(99,102,241,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                        <i data-lucide="lock" style="width:26px;height:26px;color:#6366f1;"></i>
                                    </div>
                                    <p class="text-muted mb-3 small">This section is password-protected.<br>Enter your account password to view commission data.</p>
                                    <button class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                        <i data-lucide="key" style="width:14px;height:14px" class="me-1"></i> Unlock Commission Data
                                    </button>
                                </div>
                                <div id="protected-commission-chart" style="display:none;">
                                    <div class="row align-items-center g-4">
                                        <div class="col-md-5">
                                            <div style="height:280px;"><canvas id="commissionPieChart"></canvas></div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="db-commission-box">
                                                <p class="small text-muted mb-1">Total Monthly Revenue (Gross)</p>
                                                <h3 class="fw-bolder text-dark mb-4">{{ takaFormat($total) }} ৳</h3>
                                                <div id="commission-details"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ROW 2: Monthly Expiration Trend (Bar) + SMS Analytics ──────── --}}
        <div class="row g-3 mb-4">

            {{-- Monthly Expiration Bar Chart --}}
            <div class="col-lg-7">
                <div class="db-card h-100">
                    <div class="db-card-header">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="trending-down" style="width:18px;height:18px;color:#f59e0b;"></i>
                                Monthly Expiration Trend
                            </div>
                            <p class="text-muted small mb-0">{{ now()->year }} vs {{ now()->year - 1 }} — month-by-month comparison.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="db-stat-pill">
                                <i data-lucide="calendar" style="width:14px;height:14px;color:#f59e0b;"></i>
                                <span class="text-muted">{{ now()->year }}:</span>
                                <strong>{{ array_sum($monthlyExpiresChartData['current'] ?? []) }}</strong>
                            </div>
                            <div class="db-stat-pill">
                                <i data-lucide="calendar-clock" style="width:14px;height:14px;color:#94a3b8;"></i>
                                <span class="text-muted">{{ now()->year - 1 }}:</span>
                                <strong>{{ array_sum($monthlyExpiresChartData['previous'] ?? []) }}</strong>
                            </div>
                            <div class="db-stat-pill">
                                <i data-lucide="calendar-check" style="width:14px;height:14px;color:#f97316;"></i>
                                <span class="text-muted">This Month:</span>
                                <strong style="color:#f97316;">{{ $monthlyExpiresChartData['current'][now()->month - 1] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="db-card-body">
                        <div style="height:280px;"><canvas id="expiredClientsChart"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- SMS Transmission Analytics --}}
            <div class="col-lg-5">
                <div class="db-card h-100">
                    <div class="db-card-header">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="message-square-text" style="width:18px;height:18px;color:#10b981;"></i>
                                SMS Analytics
                                <span class="db-badge-pill" style="background:rgba(16,185,129,.1);color:#10b981;">{{ now()->format('F Y') }}</span>
                            </div>
                            <p class="text-muted small mb-0">Daily delivery status for the current month.</p>
                        </div>
                        <a href="{{ route('sms_log') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                            <i data-lucide="external-link" style="width:13px;height:13px"></i> Logs
                        </a>
                    </div>
                    {{-- SMS Mini Stats --}}
                    <div class="px-4 pb-2 pt-0">
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="db-mini-stat" style="border-color:rgba(99,102,241,.2);background:rgba(99,102,241,.04);">
                                    <span class="db-mini-label">Today Sent</span>
                                    <span class="db-mini-val" style="color:#6366f1;">{{ number_format($smsTodaySent ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="db-mini-stat" style="border-color:rgba(16,185,129,.2);background:rgba(16,185,129,.04);">
                                    <span class="db-mini-label">Month Total</span>
                                    <span class="db-mini-val" style="color:#10b981;">{{ number_format($smsMonthSent ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="db-mini-stat" style="border-color:rgba(245,158,11,.2);background:rgba(245,158,11,.04);">
                                    <span class="db-mini-label">Success Rate</span>
                                    <span class="db-mini-val" style="color:#f59e0b;">{{ $smsDeliveryRate ?? 100 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="db-card-body pt-2">
                        <div style="height:210px;"><canvas id="smsTrendsChart"></canvas></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── SECTION HEADER: Client Expiration Management ────────────────── --}}
        <div class="db-section-divider mb-3">
            <div class="db-section-label">
                <i data-lucide="alert-octagon" style="width:16px;height:16px;color:#ef4444;"></i>
                Client Expiration Management
            </div>
            <div class="db-section-line"></div>
        </div>

        {{-- ── ROW 3: Today's Expired + Hotspot Expired ────────────────────── --}}
        <div class="row g-3 mb-4" id="todaysExpiredClients">

            {{-- Today's Expired Wired Clients --}}
            <div class="col-lg-6">
                <div class="db-card h-100">
                    <div class="db-card-header" style="border-left:3px solid #ef4444;">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="calendar-x" style="width:18px;height:18px;color:#ef4444;"></i>
                                Today's Expired Clients
                                <span class="db-badge-pill" style="background:rgba(239,68,68,.1);color:#ef4444;">Wired</span>
                            </div>
                            <p class="text-muted small mb-0">{{ now('Asia/Dhaka')->format('d F, Y') }} — Wired connections expired today.</p>
                        </div>
                        <span class="db-count-badge" style="background:#ef4444;">{{ $expired_today->count() }}</span>
                    </div>
                    <div class="db-card-body p-0">
                        @if($expired_today->count() > 0)
                        <div class="table-responsive">
                            <table class="table db-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>ID</th>
                                        <th>Contact</th>
                                        <th>Package</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expired_today as $client)
                                    <tr>
                                        <td class="text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('clients.show', $client->id) }}" class="fw-semibold text-dark text-decoration-none hover-primary">{{ $client->name }}</a>
                                        </td>
                                        <td>
                                            @if($client->isp_code === 'bijoy')
                                                <a href="https://selfcare.bijoy.net/pay/{{ $client->username }}" target="_blank" class="db-link-id">{{ $client->username }}</a>
                                            @else
                                                <a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" class="db-link-id">{{ $client->username }}</a>
                                            @endif
                                        </td>
                                        <td><a href="tel:{{ $client->contact }}" class="text-muted small">{{ $client->contact }}</a></td>
                                        <td><span class="db-pkg-badge">{{ $client->package }}</span></td>
                                        <td class="small text-muted">{{ $client->comment ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="db-empty-state">
                                <i data-lucide="check-circle-2" style="width:36px;height:36px;color:#10b981;" class="mb-2"></i>
                                <p class="mb-0 fw-semibold text-success">No wired clients expired today!</p>
                                <small class="text-muted">All connections are active.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Expired Hotspot Clients --}}
            <div class="col-lg-6">
                <div class="db-card h-100">
                    <div class="db-card-header" style="border-left:3px solid #f97316;">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="wifi-off" style="width:18px;height:18px;color:#f97316;"></i>
                                Expired Hotspot Clients
                            </div>
                            <p class="text-muted small mb-0">Hotspot accounts past their expiry date.</p>
                        </div>
                        <span class="db-count-badge" style="background:#f97316;">{{ $expiredHotspotClients->count() }}</span>
                    </div>
                    <div class="db-card-body p-0">
                        @if($expiredHotspotClients->count() > 0)
                        <div class="table-responsive" style="max-height:320px;overflow-y:auto;">
                            <table class="table db-table mb-0">
                                <thead class="sticky-top" style="top:0;">
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Expired</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiredHotspotClients as $client)
                                    <tr>
                                        <td class="fw-semibold text-dark small">{{ $client->name }}</td>
                                        <td class="small text-muted">{{ $client->contact ?? 'N/A' }}</td>
                                        <td>
                                            <span class="db-danger-text small fw-semibold">
                                                {{ $client->expires_at ? \Carbon\Carbon::parse($client->expires_at)->format('d M, Y') : '—' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="tel:{{ $client->contact }}" class="btn btn-xs db-btn-call">
                                                <i data-lucide="phone" style="width:12px;height:12px"></i> Call
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="db-empty-state">
                                <i data-lucide="wifi" style="width:36px;height:36px;color:#10b981;" class="mb-2"></i>
                                <p class="mb-0 fw-semibold text-success">No expired Hotspot clients!</p>
                                <small class="text-muted">All hotspot accounts are active.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ── ROW 4: Postpaid Expiring + Free ONU to Collect ──────────────── --}}
        <div class="row g-3 mb-4">

            {{-- Postpaid Clients Expiring --}}
            <div class="col-lg-6">
                <div class="db-card h-100">
                    <div class="db-card-header" style="border-left:3px solid #f59e0b;">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="credit-card" style="width:18px;height:18px;color:#f59e0b;"></i>
                                Postpaid Clients Expiring
                                <span class="db-badge-pill" style="background:rgba(245,158,11,.1);color:#f59e0b;">Today / Tomorrow</span>
                            </div>
                            <p class="text-muted small mb-0">Postpaid accounts approaching expiry.</p>
                        </div>
                        <span class="db-count-badge" style="background:#f59e0b;">{{ $expiredPostpaidClients->count() }}</span>
                    </div>
                    <div class="db-card-body p-0">
                        @if($expiredPostpaidClients->count() > 0)
                        <div class="table-responsive">
                            <table class="table db-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>ID</th>
                                        <th>Address</th>
                                        <th>Expiry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiredPostpaidClients as $client)
                                    <tr>
                                        <td class="fw-semibold text-dark small">{{ $client->name }}</td>
                                        <td>
                                            @if($client->isp_code === 'bijoy')
                                                <a href="https://selfcare.bijoy.net/pay/" target="_blank" class="db-link-id">{{ $client->username }}</a>
                                            @else
                                                <a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" class="db-link-id">{{ $client->username }}</a>
                                            @endif
                                        </td>
                                        <td><span class="small text-muted">{{ $client->address ?? '—' }}</span></td>
                                        <td><span class="db-danger-text small fw-semibold">{{ \Carbon\Carbon::parse($client->expiration)->format('d M, Y') }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="db-empty-state">
                                <i data-lucide="check-circle-2" style="width:36px;height:36px;color:#10b981;" class="mb-2"></i>
                                <p class="mb-0 fw-semibold text-success">No postpaid clients expiring soon.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Free ONU Expired Clients --}}
            <div class="col-lg-6">
                <div class="db-card h-100">
                    <div class="db-card-header" style="border-left:3px solid #64748b;">
                        <div>
                            <div class="db-card-title">
                                <i data-lucide="package-minus" style="width:18px;height:18px;color:#64748b;"></i>
                                Free ONU to Collect
                            </div>
                            <p class="text-muted small mb-0">Expired clients with unreturned free ONU devices.</p>
                        </div>
                        <span class="db-count-badge" style="background:#64748b;">{{ $freeOnuExpiredClients->count() }}</span>
                    </div>
                    <div class="db-card-body p-0">
                        @if($freeOnuExpiredClients->count() > 0)
                        <div class="table-responsive" style="max-height:320px;overflow-y:auto;">
                            <table class="table db-table mb-0">
                                <thead class="sticky-top" style="top:0;">
                                    <tr>
                                        <th>Name</th>
                                        <th>ID</th>
                                        <th>Mobile</th>
                                        <th>Expiry</th>
                                        <th>ONU Serial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($freeOnuExpiredClients as $client)
                                    <tr>
                                        <td class="fw-semibold text-dark small">{{ $client->name }}</td>
                                        <td>
                                            <a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" class="db-link-id">{{ $client->username }}</a>
                                        </td>
                                        <td><a href="tel:{{ $client->contact }}" class="small text-muted">{{ $client->contact }}</a></td>
                                        <td><span class="db-danger-text small fw-semibold">{{ $client->expiration_formatted }}</span></td>
                                        <td><code class="small">{{ $client->onu_serial ?? 'N/A' }}</code></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="db-empty-state">
                                <i data-lucide="package-check" style="width:36px;height:36px;color:#10b981;" class="mb-2"></i>
                                <p class="mb-0 fw-semibold text-success">No free ONU devices pending collection.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>{{-- /section-body --}}
</section>

{{-- ── FLOATING ACTION BUTTON ──────────────────────────────────────────── --}}
<a href="{{ route('tickets.live') }}"
   class="db-fab"
   data-bs-toggle="tooltip" data-bs-placement="left" title="New Ticket">
    <i data-lucide="plus" style="width:26px;height:26px;"></i>
</a>

{{-- ── PASSWORD MODAL ───────────────────────────────────────────────────── --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;">
                <h5 class="modal-title text-white fw-bold" id="passwordModalLabel">
                    <i data-lucide="key" style="width:18px;height:18px" class="me-2"></i> Unlock Commission
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Enter your account password to view protected commission data.</p>
                <input type="password" class="form-control" id="accessPassword" placeholder="Your session password">
                <div id="passwordError" class="text-danger small mt-2" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary fw-semibold" id="checkPasswordBtn">
                    <i data-lucide="unlock" style="width:14px;height:14px" class="me-1"></i> Unlock
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── STYLES ───────────────────────────────────────────────────────────── --}}
<style>
/* ─── KPI Cards ─────────────────────────────────────────────────── */
.db-kpi-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 14px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: box-shadow .22s, transform .22s;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    cursor: pointer;
}
.db-kpi-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,.10);
    transform: translateY(-2px);
    border-color: var(--kpi-accent, #6366f1);
}
.db-kpi-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.db-kpi-body { flex: 1; min-width: 0; }
.db-kpi-label { display: block; font-size: .73rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
.db-kpi-value { display: block; font-size: 1.45rem; font-weight: 800; color: #1e293b; line-height: 1.2; margin-top: 2px; }
.db-kpi-arrow { flex-shrink: 0; opacity: .5; }

/* ─── Cards ─────────────────────────────────────────────────────── */
.db-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.db-card-header {
    padding: 18px 22px 14px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.db-card-tabs {
    padding: 0 22px;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfc;
}
.db-card-tabs .nav-link {
    font-size: .82rem; font-weight: 600; color: #64748b;
    padding: 8px 14px; border-radius: 8px;
    border: none; margin: 6px 2px;
}
.db-card-tabs .nav-link.active {
    background: rgba(99,102,241,.12); color: #6366f1;
}
.db-card-body { padding: 18px 22px; flex: 1; }
.db-card-body.p-0 { padding: 0; }
.db-card-body.pt-2 { padding-top: 8px; }
.db-card-title {
    font-size: .93rem; font-weight: 700; color: #1e293b;
    display: flex; align-items: center; gap: 7px; margin-bottom: 2px;
}

/* ─── Stat Pills ──────────────────────────────────────────────── */
.db-stat-pill {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 20px; padding: 4px 12px;
    font-size: .78rem;
}
.db-badge-pill {
    display: inline-flex; align-items: center;
    font-size: .7rem; font-weight: 700; padding: 2px 8px;
    border-radius: 20px; letter-spacing: .02em;
}
.db-count-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 30px; height: 30px; border-radius: 50%;
    font-size: .8rem; font-weight: 700; color: #fff;
    padding: 0 8px;
}

/* ─── Mini Stats ─────────────────────────────────────────────── */
.db-mini-stat {
    border: 1px solid; border-radius: 10px;
    padding: 8px 10px; text-align: center;
}
.db-mini-label { display: block; font-size: .65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .04em; }
.db-mini-val { display: block; font-size: 1.1rem; font-weight: 800; margin-top: 2px; }

/* ─── Tables ─────────────────────────────────────────────────── */
.db-table { font-size: .84rem; margin: 0; }
.db-table thead th {
    background: #f8fafc; border-bottom: 1px solid #e9ecef;
    color: #64748b; font-weight: 700; font-size: .75rem;
    text-transform: uppercase; letter-spacing: .04em;
    padding: 10px 14px; border-top: none;
}
.db-table tbody td {
    padding: 10px 14px; vertical-align: middle;
    border-bottom: 1px solid #f1f5f9; color: #334155;
}
.db-table tbody tr:last-child td { border-bottom: none; }
.db-table tbody tr:hover td { background: #f8faff; }
.db-table tfoot td {
    padding: 10px 14px; border-top: 2px solid #e9ecef;
}

/* ─── Misc ───────────────────────────────────────────────────── */
.db-link-id {
    font-family: 'SFMono-Regular', Consolas, monospace;
    font-size: .8rem; font-weight: 700; color: #6366f1;
    text-decoration: none;
}
.db-link-id:hover { text-decoration: underline; }
.db-pkg-badge {
    background: rgba(99,102,241,.08); color: #6366f1;
    font-size: .72rem; font-weight: 700; padding: 2px 8px;
    border-radius: 6px;
}
.db-danger-text { color: #ef4444; }
.db-btn-call {
    background: rgba(16,185,129,.1); color: #10b981;
    border: 1px solid rgba(16,185,129,.2); border-radius: 6px;
    font-size: .75rem; font-weight: 600; padding: 3px 10px;
    display: inline-flex; align-items: center; gap: 4px;
}
.db-btn-call:hover { background: #10b981; color: #fff; }
.db-empty-state {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 40px 20px; text-align: center;
}
.db-commission-box {
    background: #f8fafc; border-radius: 14px; padding: 24px;
}
.db-fab {
    position: fixed; bottom: 28px; right: 28px;
    width: 54px; height: 54px; border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 22px rgba(99,102,241,.45);
    text-decoration: none; z-index: 9999;
    transition: transform .22s, box-shadow .22s;
}
.db-fab:hover { transform: scale(1.1); box-shadow: 0 10px 30px rgba(99,102,241,.55); color: #fff; }
.db-section-divider {
    display: flex; align-items: center; gap: 12px;
}
.db-section-label {
    display: flex; align-items: center; gap: 7px;
    font-size: .8rem; font-weight: 700; color: #64748b;
    text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
}
.db-section-line {
    flex: 1; height: 1px; background: linear-gradient(90deg,#e2e8f0,transparent);
}
.btn-xs { padding: 3px 10px; font-size: .75rem; }
.hover-primary:hover { color: #6366f1 !important; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tooltips
        var tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipEls.forEach(function(el) { new bootstrap.Tooltip(el); });
    });
</script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
lucide.createIcons();

const totalRevenue = @json($total ?? 0);
const userEmail = "{{ Auth::check() ? Auth::user()->email : '' }}";

// ── Commission Chart ──────────────────────────────────────────────────────
function initCommissionChart(commAmount, remAmount) {
    const el = document.getElementById('commissionPieChart');
    if (!el) return;
    if (el._chart) el._chart.destroy();
    el._chart = new Chart(el.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['40% Commission', '60% Remaining'],
            datasets: [{
                data: [commAmount, remAmount],
                backgroundColor: ['#10b981','#6366f1'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '72%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 16, font: { size: 12, weight: '600' } } },
                tooltip: {
                    backgroundColor: 'rgba(15,23,42,.92)', padding: 12, cornerRadius: 10,
                    titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 }, boxPadding: 4,
                    callbacks: { label: function(ctx) { return ' ' + ctx.label + ': ৳ ' + Number(ctx.parsed).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); } }
                }
            }
        }
    });
    document.getElementById('commission-details').innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3 bg-white border">
            <span class="small fw-semibold text-success"><i data-lucide="percentage" style="width:14px;height:14px" class="me-1"></i> 40% Commission:</span>
            <span class="fw-bold text-success">৳ ${Number(commAmount).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-white border">
            <span class="small fw-semibold text-primary"><i data-lucide="wallet" style="width:14px;height:14px" class="me-1"></i> 60% Remaining:</span>
            <span class="fw-bold text-primary">৳ ${Number(remAmount).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</span>
        </div>
    `;
    lucide.createIcons();
}

document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Clients by Package Chart (Bar + Line) ────────────────────────
    const clientsChartEl = document.getElementById('clientsChart');
    if (clientsChartEl) {
        const ctx = clientsChartEl.getContext('2d');

        let barGrad = ctx.createLinearGradient(0, 0, 0, 320);
        barGrad.addColorStop(0,   'rgba(99,102,241,.92)');
        barGrad.addColorStop(0.5, 'rgba(79,70,229,.72)');
        barGrad.addColorStop(1,   'rgba(67,56,202,.38)');

        let lineAreaGrad = ctx.createLinearGradient(0, 0, 0, 320);
        lineAreaGrad.addColorStop(0, 'rgba(245,158,11,.22)');
        lineAreaGrad.addColorStop(1, 'rgba(245,158,11,.00)');

        const clientsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($clients_by_package->pluck('package')),
                datasets: [
                    {
                        label: 'Active Clients',
                        data: @json($clients_by_package->pluck('total_clients')),
                        backgroundColor: barGrad,
                        borderColor: 'transparent',
                        borderWidth: 0,
                        borderRadius: { topLeft: 8, topRight: 8 },
                        borderSkipped: false,
                        maxBarThickness: 44,
                        order: 2
                    },
                    {
                        label: 'Monthly Revenue (৳)',
                        data: @json($clients_by_package->pluck('total_amount')),
                        borderColor: '#f59e0b',
                        backgroundColor: lineAreaGrad,
                        borderWidth: 2.5,
                        type: 'line', fill: true, tension: 0.42,
                        pointBackgroundColor: '#f59e0b', pointBorderColor: '#fff',
                        pointBorderWidth: 2.5, pointRadius: 5, pointHoverRadius: 8,
                        yAxisID: 'y1', order: 1
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeInOutQuart' },
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        beginAtZero: true, border: { display: false },
                        ticks: { precision: 0, font: { size: 11 }, color: '#94a3b8' },
                        grid: { color: 'rgba(226,232,240,.5)' },
                        title: { display: true, text: 'Active Clients', font: { size: 11, weight: '500' }, color: '#94a3b8' }
                    },
                    y1: {
                        beginAtZero: true, position: 'right', border: { display: false },
                        grid: { drawOnChartArea: false },
                        ticks: { callback: v => '৳ ' + Number(v).toLocaleString(), font: { size: 11 }, color: '#f59e0b' },
                        title: { display: true, text: 'Revenue (৳)', font: { size: 11, weight: '500' }, color: '#f59e0b' }
                    },
                    x: {
                        grid: { display: false }, border: { display: false },
                        ticks: { font: { size: 11, weight: '600' }, color: '#64748b', maxRotation: 30 }
                    }
                },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 20, font: { size: 12, weight: '600' } } },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,.93)', padding: 13, cornerRadius: 10,
                        titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 }, boxPadding: 5,
                        callbacks: {
                            label: function(ctx) {
                                return ctx.datasetIndex === 1
                                    ? ' ' + ctx.dataset.label + ': ৳ ' + Number(ctx.raw).toLocaleString()
                                    : ' ' + ctx.dataset.label + ': ' + Number(ctx.raw).toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('chart-tab')?.addEventListener('shown.bs.tab', () => clientsChart.resize());
    }

    // ── 2. Monthly Expiration Bar Chart ────────────────────────────────
    const expiredEl = document.getElementById('expiredClientsChart');
    if (expiredEl) {
        const ctx = expiredEl.getContext('2d');

        let curGrad = ctx.createLinearGradient(0, 0, 0, 280);
        curGrad.addColorStop(0, 'rgba(245,158,11,.92)');
        curGrad.addColorStop(1, 'rgba(245,158,11,.40)');

        let prevGrad = ctx.createLinearGradient(0, 0, 0, 280);
        prevGrad.addColorStop(0, 'rgba(148,163,184,.80)');
        prevGrad.addColorStop(1, 'rgba(100,116,139,.30)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        label: 'Current Year (' + new Date().getFullYear() + ')',
                        data: @json($monthlyExpiresChartData['current']),
                        backgroundColor: curGrad,
                        borderColor: '#d97706',
                        borderWidth: 1, borderRadius: 7, borderSkipped: false, maxBarThickness: 26
                    },
                    {
                        label: 'Previous Year (' + (new Date().getFullYear() - 1) + ')',
                        data: @json($monthlyExpiresChartData['previous']),
                        backgroundColor: prevGrad,
                        borderColor: '#94a3b8',
                        borderWidth: 1, borderRadius: 7, borderSkipped: false, maxBarThickness: 26
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeInOutQuart' },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 18, font: { size: 12, weight: '600' } } },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,.93)', padding: 13, cornerRadius: 10,
                        titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 }, boxPadding: 5,
                        callbacks: {
                            title: items => items[0].label + ' — Expired Clients',
                            label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.raw + ' clients'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true, border: { display: false },
                        ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#94a3b8' },
                        grid: { color: 'rgba(226,232,240,.5)' },
                        title: { display: true, text: 'Expired Clients', font: { size: 11, weight: '500' }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false }, border: { display: false },
                        ticks: { font: { size: 11, weight: '600' }, color: '#64748b' }
                    }
                }
            }
        });
    }

    // ── 3. SMS Trends Chart (Current Month Area Line) ────────────────────
    const smsEl = document.getElementById('smsTrendsChart');
    if (smsEl) {
        const ctx = smsEl.getContext('2d');

        let sentGrad = ctx.createLinearGradient(0, 0, 0, 200);
        sentGrad.addColorStop(0, 'rgba(16,185,129,.30)');
        sentGrad.addColorStop(1, 'rgba(16,185,129,.01)');

        let failGrad = ctx.createLinearGradient(0, 0, 0, 200);
        failGrad.addColorStop(0, 'rgba(239,68,68,.22)');
        failGrad.addColorStop(1, 'rgba(239,68,68,.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($smsChartData['labels'] ?? []),
                datasets: [
                    {
                        label: 'Delivered / Sent',
                        data: @json($smsChartData['sent'] ?? []),
                        borderColor: '#10b981', backgroundColor: sentGrad,
                        fill: true, tension: 0.42,
                        pointBackgroundColor: '#10b981', pointBorderColor: '#fff',
                        pointBorderWidth: 2, pointRadius: 3.5, pointHoverRadius: 7,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Failed',
                        data: @json($smsChartData['failed'] ?? []),
                        borderColor: '#ef4444', backgroundColor: failGrad,
                        fill: true, tension: 0.42,
                        pointBackgroundColor: '#ef4444', pointBorderColor: '#fff',
                        pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 6,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeInOutQuart' },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 7, padding: 16, font: { size: 11, weight: '600' } } },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,.93)', padding: 12, cornerRadius: 10,
                        titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 }, boxPadding: 4
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true, border: { display: false },
                        ticks: { precision: 0, font: { size: 10 }, color: '#94a3b8' },
                        grid: { color: 'rgba(226,232,240,.5)' }
                    },
                    x: {
                        grid: { display: false }, border: { display: false },
                        ticks: { font: { size: 9.5, weight: '600' }, color: '#64748b', maxRotation: 45, autoSkip: true, maxTicksLimit: 15 }
                    }
                }
            }
        });
    }

    // ── Password Unlock ─────────────────────────────────────────────────
    document.getElementById('checkPasswordBtn')?.addEventListener('click', function() {
        const password = document.getElementById('accessPassword').value;
        const errorDiv = document.getElementById('passwordError');
        errorDiv.style.display = 'none';

        fetch('/api/check-master-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ password: password, totalRevenue: totalRevenue })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('passwordModal'))?.hide();
                document.getElementById('unlock-area').style.display = 'none';
                document.getElementById('protected-commission-chart').style.display = 'block';
                initCommissionChart(data.commissionAmount, data.remainingAmount);
            } else {
                errorDiv.innerText = data.message || 'Authentication failed.';
                errorDiv.style.display = 'block';
            }
        })
        .catch(() => {
            errorDiv.innerText = 'An unexpected error occurred.';
            errorDiv.style.display = 'block';
        });
    });
});
</script>
@endsection
