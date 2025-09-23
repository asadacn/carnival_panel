@extends('layouts.app')

@section('content')

<h3 class="mb-3">Hotspot Clients</h3>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="{{ route('hotspotClients.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search...">
            <button class="btn btn-primary btn-sm ms-2" type="submit">Search</button>
        </form>
    </div>

    <div class="col-md-6 text-end">
        <a href="{{ route('hotspotClients.create') }}" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Add New Client
        </a>
    </div>
</div>

{{-- Metrics Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Active</h5>
                <p class="card-text">{{ $hotspotClients->where('status', 'active')->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Expired</h5>
                <p class="card-text">{{ $hotspotClients->filter(fn($c) => $c->status == 'active' && $c->isExpired())->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title">Inactive</h5>
                <p class="card-text">{{ $hotspotClients->where('status', 'inactive')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact</th>
                <th>ONU MAC</th>
                <th>Address</th>
                <th>Package</th>
                <th>Expired</th>
                <th>Remaining</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hotspotClients as $key => $client)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->contact }}</td>
                <td>{{ $client->onu_mac }}</td>
                <td>{{ $client->adrress }}</td>
                <td>{{ $client->package_days ? $client->package_days . ' days' : '-' }}</td>
                <td>{{ $client->expires_at ? $client->expires_at->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($client->expires_at && !$client->isExpired())
                        {{ $client->expires_at->diffInDays(now()) }} days
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($client->status == 'active' && $client->isExpired())
                        <span class="badge bg-danger">Expired</span>
                    @elseif($client->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        <a href="{{ route('hotspotClients.show', $client->id) }}" class="btn btn-light btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('hotspotClients.edit', $client->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>

                        {!! Form::open(['route' => ['hotspotClients.destroy', $client->id], 'method' => 'delete', 'class' => 'd-inline']) !!}
                            {!! Form::button('<i class="fa fa-trash"></i>', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger btn-sm',
                                'onclick' => 'return confirm("Are you sure?")'
                            ]) !!}
                        {!! Form::close() !!}

                        {{-- Renew Dropdown 1-30 Days --}}
                        @php
                            $showRenew = !$client->activated_at || $client->isExpired() || ($client->expires_at && $client->expires_at->subDay()->isPast());
                        @endphp
                        @if($showRenew)
                            <form method="POST" action="{{ route('hotspotClients.activate', $client->id) }}" class="d-inline ms-1">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <select name="days" class="form-select form-select-sm">
                                        @for($i=1; $i<=30; $i++)
                                            <option value="{{ $i }}">{{ $i }} Day{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm">Renew</button>
                                </div>
                            </form>
                        @endif

                        {{-- Manual SMS --}}
                        @if($client->status == 'active')
                            <a href="{{ route('hotspotClients.sendSms', $client->id) }}"
                               class="btn btn-info btn-sm ms-1"
                               onclick="return confirm('Send SMS Reminder now?')">
                               SMS
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted">No hotspot clients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-between mt-2">
        <span>Total Clients: {{ $hotspotClients->total() }}</span>
        <div>
            {!! $hotspotClients->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
        </div>
    </div>
</div>

@endsection
