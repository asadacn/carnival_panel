@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading font-weight-bold d-flex align-items-center">
            <i data-lucide="layout-dashboard" class="me-2" style="width:24px;height:24px"></i>
            ISP Operations Dashboard
        </h3>
    </div>

    <div class="section-body">

        {{-- Last Updated / Refresh Button --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            @if($lastUpdated)
                <p class="text-muted mb-0 small">
                    <i data-lucide="clock" class="me-1" style="width:16px;height:16px"></i>
                    Data last refreshed: **{{ \Carbon\Carbon::parse($lastUpdated)->diffForHumans() }}**
                </p>
            @else
                <p class="text-muted mb-0 small"><i data-lucide="clock" class="me-1" style="width:16px;height:16px"></i> No data recorded yet.</p>
            @endif
            <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload();">
                <i data-lucide="refresh-cw" style="width:14px;height:14px"></i> Refresh
            </button>
        </div>

        {{-- Top Metrics - Actionable Cards (FIXED ROUTES) --}}
        <div class="row g-4 mb-5">
            @php
                // FIX: Unknown routes are temporarily linked to clients.index to prevent RouteNotFoundException.
                $metrics = [
                    ['title'=>'Total Clients','count'=>$clients->count(),'bg'=>'linear-gradient(135deg,#1F2937,#374151)','icon'=>'users','link'=>route('clients.index')],
                    ['title'=>'Expiring Soon','count'=>$expiring_soon->count(),'bg'=>'linear-gradient(135deg,#f97316,#fb923c)','icon'=>'alert-triangle','link'=>route('clients.index')],
                    ['title'=>'Expired Today','count'=>$expired_today->count(),'bg'=>'linear-gradient(135deg,#dc2626,#f87171)','icon'=>'calendar-x','link'=>'#todaysExpiredClients'],
                    ['title'=>'Monthly Expired','count'=>$expired_this_month->count(),'bg'=>'linear-gradient(135deg,#4b5563,#6b7280)','icon'=>'calendar-days','link'=>route('clients.index')],
                    ['title'=>'SMS Balance (BDT)','count'=>sms_balance() . ' ৳','bg'=>'linear-gradient(135deg,#10b981,#34d399)','icon'=>'message-circle','link'=>route('clients.index')]
                ];
            @endphp
            @foreach($metrics as $metric)
            <div class="col-sm-6 col-md-4 col-lg-2-4">
                @if(str_starts_with($metric['link'], '#'))
                    <div class="card text-white shadow-lg border-0 rounded-4 transition-scale" style="background: {{ $metric['bg'] }}; overflow: hidden;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <i data-lucide="{{ $metric['icon'] }}" class="mb-2 opacity-75" style="width:32px;height:32px"></i>
                                <small class="text-uppercase fw-semibold opacity-90">{{ $metric['title'] }}</small>
                            </div>
                            <h3 class="fw-bolder mt-2 mb-0 display-6">{{ $metric['count'] }}</h3>
                            <div class="d-flex justify-content-end mt-2 opacity-75 small">
                                <i data-lucide="arrow-down" style="width:16px;height:16px"></i>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $metric['link'] }}" class="text-decoration-none d-block">
                        <div class="card text-white shadow-lg border-0 rounded-4 transition-scale" style="background: {{ $metric['bg'] }}; overflow: hidden;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <i data-lucide="{{ $metric['icon'] }}" class="mb-2 opacity-75" style="width:32px;height:32px"></i>
                                    <small class="text-uppercase fw-semibold opacity-90">{{ $metric['title'] }}</small>
                                </div>
                                <h3 class="fw-bolder mt-2 mb-0 display-6">{{ $metric['count'] }}</h3>
                                <div class="d-flex justify-content-end mt-2 opacity-75 small">
                                    <i data-lucide="arrow-right" style="width:16px;height:16px"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Client Management Overviews --}}
        <div class="row g-4 mb-5">

            {{-- Client Analytics & Value (FIXED: Tabbed UI to control length) --}}
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header border-0 bg-white pt-4 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center text-primary">
                            <i data-lucide="activity" class="me-2"></i> Client Analytics & Value
                        </h5>

                        {{-- Tab Navigation for Chart and Table --}}
                        <ul class="nav nav-pills mt-3" id="clientAnalyticsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="chart-tab" data-bs-toggle="pill" data-bs-target="#chart-content" type="button" role="tab" aria-controls="chart-content" aria-selected="true">
                                    <i data-lucide="bar-chart-2" style="width:16px;height:16px" class="me-1"></i> Chart View
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="table-tab" data-bs-toggle="pill" data-bs-target="#table-content" type="button" role="tab" aria-controls="table-content" aria-selected="false">
                                    <i data-lucide="list-ordered" style="width:16px;height:16px" class="me-1"></i> Data Table ({{ $registered_clients }})
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body pt-3">
                        <div class="tab-content" id="clientAnalyticsTabContent">

                            {{-- Tab 1: Chart View --}}
                            <div class="tab-pane fade show active" id="chart-content" role="tabpanel" aria-labelledby="chart-tab">
                                <h6 class="text-muted mb-3 mt-2">Clients by Package & Value Trend</h6>
                                <div style="height: 350px;">
                                    <canvas id="clientsChart"></canvas>
                                </div>
                            </div>

                            {{-- Tab 2: Registered Clients Table --}}
                            <div class="tab-pane fade" id="table-content" role="tabpanel" aria-labelledby="table-tab">
                                <h6 class="text-muted mb-3 mt-2">Registered Clients Summary</h6>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-striped table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Package</th>
                                                <th>Clients</th>
                                                <th>Value (৳)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @foreach ($clients_by_package as $client)
                                                <tr>
                                                    <td class="small">{{ $client->package }}</td>
                                                    <td class="small fw-bold">{{ $client->total_clients }}</td>
                                                    <td class="small">{{ takaFormat($client->total_amount) }}</td>
                                                </tr>
                                                @php $total += $client->total_amount; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-2 border-top mt-2 text-end">
                                    <h6 class="mb-0 small fw-bold text-success">Total Monthly Value: {{ takaFormat($total) }} ৳</h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Commission Pie Chart - Right Column (NEW CHART) --}}
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header border-0 text-white bg-info rounded-top-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i data-lucide="pie-chart" class="me-2"></i> Monthly Value & Commission
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <div class="w-100" style="max-height: 250px;">
                            <canvas id="commissionPieChart"></canvas>
                        </div>
                        <div class="p-3 border rounded-3 mt-4 w-100 bg-light text-center">
                            <p class="mb-1 small text-muted">Total Monthly Revenue:</p>
                            <h4 class="fw-bolder text-dark mb-0">{{ takaFormat($total) }} ৳</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Client Expiration Management Section --}}

        <div class="row g-4">

            <div class="col-lg-12">

                <h4 class="mb-4 fw-bold text-dark border-bottom pb-2">Client Expiration Management ⚠️</h4>

            </div>



            <div class="row">

            <div class="col-lg-6">  {{-- 1. Today's Expired Clients (Wired) --}}

                <div class="card mb-4 shadow-sm border-0 rounded-4" id="todaysExpiredClients">

                    <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(135deg,#dc2626,#f87171)">

                        <i data-lucide="calendar-x" class="me-2"></i> Today's Expired Clients (Wired)

                    </div>

                    <div class="card-body">

                        @include('layouts.expireddashboardtable', ['clients' => $expired_today])

                    </div>

                </div>

            </div>







            {{-- 2. Expired Hotspot Clients (Original Position) --}}

            <div class="col-lg-6">

                <div class="card mb-4 shadow-sm border-0 rounded-4">

                    <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(135deg,#ff4b2b,#ff416c)">

                        <i data-lucide="wifi-off" class="me-2"></i> Expired Hotspot Clients

                    </div>

                    <div class="card-body table-responsive">

                        @if($expiredHotspotClients->count() > 0)

                            <table class="table table-striped table-hover table-sm align-middle">

                                <thead class="table-dark">

                                    <tr>

                                        <th>Name</th>

                                        <th>Package</th>

                                        <th>Expires At</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($expiredHotspotClients as $key => $client)

                                    <tr>

                                        <td><i data-lucide="user" style="width:14px;height:14px" class="me-1"></i> {{ $client->name }}</td>

                                        <td><span class="badge bg-secondary">{{ $client->package ?? '-' }}</span></td>

                                        <td>{{ $client->expires_at ? \Carbon\Carbon::parse($client->expires_at)->format('d M, Y') : '-' }}</td>

                                        <td>

                                            <a href="tel:{{ $client->contact }}" class="btn btn-sm btn-success"><i class="fas fa-phone"></i> Call</a>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        @else

                            <p class="text-success mb-0 fw-bold">✅ কোনো expired Hotspot client নেই।</p>

                        @endif

                    </div>

                </div>





             {{-- 3. Postpaid Clients Being Expired (MOVED HERE) --}}



                <div class="card mb-4 shadow-sm border-0 rounded-4">

                    <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(135deg,#f7971e,#ffd200)">

                        <i data-lucide="credit-card" class="me-2"></i> Postpaid Clients Being Expired

                    </div>

                    <div class="card-body table-responsive">

                        <table class="table table-hover table-bordered table-sm align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Client Name</th>

                                    <th>Carnival ID</th>

                                    <th>Package</th>

                                    <th>Expired Date</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($expiredPostpaidClients as $client)

                                    <tr>

                                        <td>{{ $client->name }}</td>

                                        <td><a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" class="text-primary fw-bold">{{ $client->username }}</a></td>

                                        <td><span class="badge bg-info text-dark">{{ $client->package ?? '-' }}</span></td>

                                        <td class="text-danger fw-bold">{{ \Carbon\Carbon::parse($client->expiration)->format('d M, Y') }}</td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center text-muted">No expired postpaid clients found.</td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>





             {{-- 4. Free ONU Expired Clients (MOVED HERE) --}}



                <div class="card mb-4 shadow-sm border-0 rounded-4">

                    <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(135deg,#4b5563,#6b7280)">

                        <i data-lucide="package-minus" class="me-2"></i> Expired Clients (Free ONU to Collect)

                    </div>

                    <div class="card-body table-responsive">

                        @if($freeOnuExpiredClients->count() > 0)

                            <table class="table table-striped table-hover table-sm align-middle">

                                <thead class="table-dark">

                                    <tr>

                                        <th>Client Name</th>

                                        <th>Mobile</th>

                                        <th>Expiration</th>

                                        <th>ONU Serial</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($freeOnuExpiredClients as $client)

                                        <tr>

                                            <td>{{ $client->name }}</td>

                                            <td><a href="tel:{{ $client->contact }}" class="btn btn-sm btn-outline-primary"><i data-lucide="phone" style="width:14px;height:14px"></i></a></td>

                                            <td class="text-danger fw-bold">{{ $client->expiration_formatted }}</td>

                                            <td>{{ $client->onu_serial ?? 'N/A' }}</td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        @else

                            <p class="text-success mb-0 fw-bold">✅ কোনো Expired Free ONU client ফেরতবিহীন নেই।</p>

                        @endif

                    </div>

                </div>









            </div>



        {{-- Monthly Expired Clients Chart (Full Width) --}}
        <div class="card mb-4 shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary text-white fw-bold">
                <h5 class="mb-0 d-flex align-items-center">
                    <i data-lucide="trending-down" class="me-2"></i> Monthly Expiration Trend (Yearly Comparison)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="expiredClientsChart" height="80"></canvas>
            </div>
        </div>

    </div>
</section>

<a href="{{ route('tickets.live') }}"
   class="btn btn-primary rounded-circle position-fixed d-flex justify-content-center align-items-center"
   style="bottom: 30px; right: 30px; width: 60px; height: 60px; font-size: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.4); z-index: 9999; transition: transform 0.2s, box-shadow 0.2s;"
   data-bs-toggle="tooltip" data-bs-placement="left" title="Create New Ticket">
    +
</a>

<style>
    /* Custom utility for 5-column layout on large screens */
    @media (min-width: 1200px) {
        .col-lg-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
    /* Hover effect for metrics cards (UX) */
    .transition-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
    }
    /* Remove default Bootstrap card header rounding when using a rounded card */
    .card-header:first-child {
        border-radius: calc(0.5rem - 1px) calc(0.5rem - 1px) 0 0 !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
lucide.createIcons();

document.addEventListener('DOMContentLoaded', function () {
    const clientsChartElement = document.getElementById('clientsChart');
    const expiredClientsChartElement = document.getElementById('expiredClientsChart');
    const commissionPieChartElement = document.getElementById('commissionPieChart');

    // --- Data Calculation for Commission Chart ---
    // NOTE: $total is already calculated in the PHP blade above.
    const totalRevenue = @json($total ?? 0);
    const commissionRate = 0.40; // 40%
    const commissionAmount = totalRevenue * commissionRate;
    const remainingAmount = totalRevenue - commissionAmount;

    // --- Chart Initializations ---

    if (clientsChartElement) {
        // Client Chart Definition (Bar/Line)
        const clientsChart = new Chart(clientsChartElement.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($clients_by_package->pluck('package')),
                datasets: [
                    {
                        label: 'Clients',
                        data: @json($clients_by_package->pluck('total_clients')),
                        backgroundColor: 'rgba(52, 152, 219, 0.8)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Amount (৳)',
                        data: @json($clients_by_package->pluck('total_amount')),
                        backgroundColor: 'rgba(231, 76, 60, 0.8)',
                        borderColor: 'rgba(231, 76, 60, 1)',
                        borderWidth: 1,
                        type: 'line',
                        fill: false,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Clients' }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Total Amount (৳)' }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // FIX: Add event listener to redraw the chart when its tab is shown
        var chartTabTrigger = document.getElementById('chart-tab');
        if (chartTabTrigger) {
            chartTabTrigger.addEventListener('shown.bs.tab', function (event) {
                clientsChart.resize(); // Re-render the chart
            });
        }
    }


    if (expiredClientsChartElement) {
        // Expired Clients Chart Definition (Bar)
        const expiredClientsChart = new Chart(expiredClientsChartElement.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                datasets: [
                    {
                        label: 'Current Year',
                        data: @json($monthlyExpiresChartData['current']),
                        backgroundColor: 'rgba(243, 156, 18, 0.8)',
                        borderColor: 'rgba(243, 156, 18, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Previous Year',
                        data: @json($monthlyExpiresChartData['previous']),
                        backgroundColor: 'rgba(52, 73, 94, 0.8)',
                        borderColor: 'rgba(52, 73, 94, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'Number of Expired Clients' } },
                    x: { title: { display: false } }
                }
            }
        });
    }

    if (commissionPieChartElement) {
        // Commission Pie Chart Definition (NEW)
        const commissionPieChart = new Chart(commissionPieChartElement.getContext('2d'), {
            type: 'pie',
            data: {
                labels: [
                    `40% Commission (${commissionAmount.toFixed(2)} ৳)`,
                    `60% Remaining (${remainingAmount.toFixed(2)} ৳)`
                ],
                datasets: [{
                    data: [commissionAmount, remainingAmount],
                    backgroundColor: [
                        '#10b981', // Green for commission
                        '#34d399', // Lighter green for remaining
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 13 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    // Use the takaFormat function (assuming it's available in your JS scope or defined in your blade/app.js)
                                    // If not available, use a standard formatting function
                                    let value = context.parsed.toFixed(2);
                                    label += `${value} ৳`;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

});

// NOTE: Since the `takaFormat()` function is likely a PHP helper,
// you may need to ensure a similar JavaScript formatting function
// is available if you want to use it in the Chart.js tooltip
// (which is currently using toFixed(2) as a fallback).
</script>
@endsection
