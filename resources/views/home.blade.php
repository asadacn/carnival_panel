@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Dashboard</h3>
    </div>

    <div class="section-body">
        {{-- Last Updated --}}
        <div class="mb-3">
            @if($lastUpdated)
                <p class="text-muted">Last updated: {{ \Carbon\Carbon::parse($lastUpdated)->diffForHumans() }}</p>
            @else
                <p class="text-muted">No updates yet.</p>
            @endif
        </div>

        {{-- Top Metrics --}}
        <div class="row mb-4">
            @php
                $metrics = [
                    ['title'=>'Total Clients','count'=>$clients->count(),'bg'=>'primary'],
                    ['title'=>'Expiring Soon','count'=>$expiring_soon->count(),'bg'=>'warning'],
                    ['title'=>'Expired Today','count'=>$expired_today->count(),'bg'=>'danger'],
                    ['title'=>'Monthly Expired','count'=>$expired_this_month->count(),'bg'=>'dark'],
                    ['title'=>'SMS Available','count'=>sms_balance(),'bg'=>'success h5']
                ];
            @endphp
            @foreach($metrics as $metric)
            <div class="col-sm-2 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted">{{ $metric['title'] }}</h6>
                        <h3 class="text-{{ $metric['bg'] }}">{{ $metric['count'] }}</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Today's Expired Clients --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Today's Expired Clients</h5>
            </div>
            <div class="card-body">
                @include('layouts.expireddashboardtable', $clients = $expired_today->get())
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Postpaid Clients Being Expired </h5>
            </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
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
                        <td><a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" rel="noopener noreferrer">{{ $client->username }}</a></td>
                        <td>{{ $client->package ?? '-' }}</td>
                        <td>{{ $client->expiration }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No expired postpaid clients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4 shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Expired Clients with Free ONU (Not Returned)</h5>
            </div>
    <div class="card-body">
        @if($freeOnuExpiredClients->count() > 0)
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
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
                            <td><a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" rel="noopener noreferrer">{{ $client->username }}</a></td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->contact }}</td>
                            <td>{{ $client->address }}</td>
                            <td>
                               {{ $client->expiration }}
                            </td>


                            <td>{{ $client->onu_serial ?? 'N/A' }}</td>
                            <td>
                                <a href="tel:{{ $client->contact }}" class="btn btn-sm btn-primary">
                                    📞 Call
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


<div class="row">
    {{-- Table --}}
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Registered Clients - {{ $registered_clients }}</h5>
            </div>
            <div class="card-body p-4">
                <table class="table table-striped table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Package</th>
                            <th>Total Clients</th>
                            <th>Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($clients_by_package as $client)
                            @php $package_price = $package[$client->package] ?? 0; @endphp
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

    {{-- Chart --}}
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Clients Chart</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="clientsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>





        {{-- Commission Calculator --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Commission Calculator</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-outline-primary mb-3" onclick="toggleCommission()">Calculate Commission</button>
                <div id="collapseCommission" style="display: none;">
                    <div class="alert alert-info">
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
<script>
    function toggleCommission(){
        $('#collapseCommission').slideToggle('fast');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('clientsChart').getContext('2d');
    const clientsChart = new Chart(ctx, {
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
                    type: 'line', // amount কে line chart বানিয়ে দিলাম
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Clients'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Total Amount'
                    }
                }
            }
        }
    });
</script>
@endsection
