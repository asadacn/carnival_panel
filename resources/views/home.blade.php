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
    <div class="card-header">
        <h5>Postpaid Clients Being Expired </h5>
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
                        <td>{{ $client->username }}</td>
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

        {{-- Registered Clients by Package --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Registered Clients - {{ $registered_clients }}</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
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
                                <td>{{ $client->total }}</td>
                                <td>{{ takaFormat($client->total * $package_price) }} Tk.</td>
                            </tr>
                            @php $total += $client->total * $package_price; @endphp
                        @endforeach
                    </tbody>
                </table>
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
@endsection
