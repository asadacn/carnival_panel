@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading font-weight-bold">📊 ISP Dashboard</h3>
    </div>

    <div class="section-body">

        {{-- Last Updated --}}
        <div class="mb-3">
            @if($lastUpdated)
                <p class="text-muted">
                    <i data-lucide="clock" class="me-1"></i>
                    Last updated: {{ \Carbon\Carbon::parse($lastUpdated)->diffForHumans() }}
                </p>
            @else
                <p class="text-muted"><i data-lucide="clock"></i> No updates yet.</p>
            @endif
        </div>

        {{-- Top Metrics --}}
        <div class="row g-3 mb-4">
            @php
                $metrics = [
                    ['title'=>'Total Clients','count'=>$clients->count(),'bg'=>'linear-gradient(135deg,#4facfe,#00f2fe)','icon'=>'users'],
                    ['title'=>'Expiring Soon','count'=>$expiring_soon->count(),'bg'=>'linear-gradient(135deg,#f7971e,#ffd200)','icon'=>'alarm-clock'],
                    ['title'=>'Expired Today','count'=>$expired_today->count(),'bg'=>'linear-gradient(135deg,#ff416c,#ff4b2b)','icon'=>'calendar-x'],
                    ['title'=>'Monthly Expired','count'=>$expired_this_month->count(),'bg'=>'linear-gradient(135deg,#434343,#000000)','icon'=>'calendar-days'],
                    ['title'=>'SMS Available (BDT)','count'=>sms_balance(),'bg'=>'linear-gradient(135deg,#56ab2f,#a8e063)','icon'=>'message-circle']
                ];
            @endphp
            @foreach($metrics as $metric)
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card text-white shadow-sm border-0 rounded-3" style="background: {{ $metric['bg'] }}">
                    <div class="card-body text-center">
                        <i data-lucide="{{ $metric['icon'] }}" class="mb-2" style="width:28px;height:28px"></i>
                        <h6 class="text-uppercase">{{ $metric['title'] }}</h6>
                        <h3 class="fw-bold">{{ $metric['count'] }}</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Today's Expired Clients --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-header text-white" style="background: linear-gradient(135deg,#ff4b2b,#ff416c)">
                <h5 class="mb-0"><i data-lucide="calendar-x" class="me-1"></i> Today's Expired Clients</h5>
            </div>
            <div class="card-body">
                @include('layouts.expireddashboardtable', ['clients' => $expired_today])
            </div>
        </div>

        {{-- Hotspot Expired Clients --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-header text-white" style="background: linear-gradient(135deg,#ff416c,#ff4b2b)">
                <h5 class="mb-0"><i class="fas fa-wifi text-white me-1"></i> Expired Hotspot Clients</h5>
            </div>
            <div class="card-body table-responsive">
                @if($expiredHotspotClients->count() > 0)
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Contact</th>
                            <th>Package</th>
                            <th>Activated At</th>
                            <th>Expired At</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiredHotspotClients as $key => $client)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->contact }}</td>
                            <td>{{ $client->package ?? '-' }}</td>
                            <td>{{ $client->activated_at ? \Carbon\Carbon::parse($client->activated_at)->format('d M, Y') : '-' }}</td>
                            <td>{{ $client->expires_at ? \Carbon\Carbon::parse($client->expires_at)->format('d M, Y') : '-' }}</td>
                            <td>
                                <span class="badge bg-danger">{{ ucfirst($client->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('hotspotClients.edit', $client->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="tel:{{ $client->contact }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-phone"></i> Call
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="text-success mb-0">✅ কোনো expired Hotspot client নেই।</p>
                @endif
            </div>
        </div>

        {{-- Postpaid Expired Clients --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-header text-white" style="background: linear-gradient(135deg,#f7971e,#ffd200)">
                <h5 class="mb-0"><i data-lucide="credit-card" class="me-1"></i> Postpaid Clients Being Expired</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Carnival ID</th>
                            <th>Package</th>
                            <th>Expired Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiredPostpaidClients as $key => $client)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $client->name }}</td>
                                <td>
                                    <a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank">
                                        {{ $client->username }}
                                    </a>
                                </td>
                                <td>{{ $client->package ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($client->expiration)->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No expired postpaid clients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Free ONU Expired Clients --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-header text-white" style="background: linear-gradient(135deg,#ff416c,#ff4b2b)">
                <h5 class="mb-0"><i data-lucide="wifi-off" class="me-1"></i> Expired Clients with Free ONU</h5>
            </div>
            <div class="card-body table-responsive">
                @if($freeOnuExpiredClients->count() > 0)
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Carnival ID</th>
                                <th>Client Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Expiration</th>
                                <th>ONU Serial</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($freeOnuExpiredClients as $key => $client)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        <a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank">
                                            {{ $client->username }}
                                        </a>
                                    </td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->contact }}</td>
                                    <td>{{ $client->address }}</td>
                                    <td>{{ $client->expiration_formatted }}</td>

                                    <td>{{ $client->onu_serial ?? 'N/A' }}</td>
                                    <td>
                                        <a href="tel:{{ $client->contact }}" class="btn btn-sm btn-outline-primary">
                                            <i data-lucide="phone"></i> Call
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-success">✅ কোনো Expired Free ONU client ফেরতবিহীন নেই।</p>
                @endif
            </div>
        </div>

        {{-- Registered Clients Table --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm border-0 rounded-3">
                    <div class="card-header text-white" style="background: linear-gradient(135deg,#56ab2f,#a8e063)">
                        <h5 class="mb-0"><i data-lucide="bar-chart-2" class="me-1"></i> Registered Clients - {{ $registered_clients }}</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Total Clients</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($clients_by_package as $client)
                                    <tr>
                                        <td>{{ $client->package }}</td>
                                        <td>{{ $client->total_clients }}</td>
                                        <td>{{ takaFormat($client->total_amount) }} Tk.</td>
                                    </tr>
                                    @php $total += $client->total_amount; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Clients Chart --}}
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm border-0 rounded-3">
                    <div class="card-header text-white" style="background: linear-gradient(135deg,#36d1dc,#5b86e5)">
                        <h5 class="mb-0"><i data-lucide="activity" class="me-1"></i> Clients Chart</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="clientsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Expired Clients Chart --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Monthly Expired Clients (Yearly Comparison)</h5>
            </div>
            <div class="card-body">
                <canvas id="expiredClientsChart" height="100"></canvas>
            </div>
        </div>


                {{-- Commission Calculator --}}
        <div class="card mb-4 shadow-sm border-0 rounded-3">
            <div class="card-header text-white" style="background: linear-gradient(135deg,#4facfe,#00f2fe)">
                <h5 class="mb-0"><i data-lucide="percent" class="me-1"></i> Commission Calculator</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-outline-dark mb-3" onclick="toggleCommission()">💰 Calculate Commission</button>
                <div id="collapseCommission" style="display: none;">
                    <div class="alert alert-info rounded-3 shadow-sm">
                        <h6>Estimated Total Value: {{ $total }} Tk.</h6>
                        <h6>Commission (40%): {{ takaFormat($total * 0.4) }} Tk.</h6>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
function toggleCommission(){
    $('#collapseCommission').slideToggle('fast');
}
lucide.createIcons();
</script>

<script>
const ctx = document.getElementById('clientsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($clients_by_package->pluck('package')),
        datasets: [
            {
                label: 'Clients',
                data: @json($clients_by_package->pluck('total_clients')),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Total Amount',
                data: @json($clients_by_package->pluck('total_amount')),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                type: 'line',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Clients' }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { drawOnChartArea: false },
                title: { display: true, text: 'Total Amount' }
            }
        }
    }
});

const ctxExpired = document.getElementById('expiredClientsChart').getContext('2d');
new Chart(ctxExpired, {
    type: 'bar',
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
            {
                label: 'Current Year',
                data: @json($monthlyExpiresChartData['current']),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Previous Year',
                data: @json($monthlyExpiresChartData['previous']),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Monthly Expired Clients Comparison' }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'Number of Expired Clients' } },
            x: { title: { display: true, text: 'Months' } }
        }
    }
});
</script>
@endsection
