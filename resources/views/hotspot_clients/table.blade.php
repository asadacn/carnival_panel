@extends('layouts.app')

@section('content')

<h3>Hotspot Clients</h3>
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

    <div class="table-responsive mt-3">
        <table class="table table-bordered ">
            <thead >
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    {{-- <th>Cable</th>
                    <th>Cable Owner</th>

                    <th>ONU Owner</th> --}}
                    <th>ONU MAC</th>
                    <th>Address</th>
                    <th>Package</th>
                    {{-- <th>Activated</th> --}}
                    <th>Expired</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotspotClients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->contact }}</td>
                    {{-- <td>{{ $client->cable }}</td>
                    <td>{{ $client->cable_owner }}</td>
                    <td>{{ $client->onu_owner }}</td> --}}
                    <td>{{ $client->onu_mac }}</td>
                    <td>{{ $client->adrress }}</td>
                    <td>{{ $client->package_days ? $client->package_days . ' days': '-' }}</td>
                    {{-- <td>{{ $client->activated_at ? $client->activated_at->format('d M, y') : '-' }}</td> --}}
                    <td>{{ $client->expires_at ? $client->expires_at->format('d/m/y') : '-' }}</td>
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
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('hotspotClients.show', $client->id) }}" class="btn btn-light btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('hotspotClients.edit', $client->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>

                            {{-- Delete --}}
                            {!! Form::open(['route' => ['hotspotClients.destroy', $client->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'onclick' => 'return confirm("Are you sure?")'
                                ]) !!}
                            {!! Form::close() !!}


                    </td>
                    <td>
                         {{-- Renew Dropdown --}}
                            @php
                                $showRenew = false;
                                if(!$client->activated_at || $client->isExpired() || ($client->expires_at && $client->expires_at->subDay()->isPast())) {
                                    $showRenew = true;
                                }
                            @endphp
                            @if($showRenew)
                                <form method="POST" action="{{ route('hotspotClients.activate', $client->id) }}" class="d-inline ms-1">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <select name="days" class="form-select">
                                            <option value="3">3 Days</option>
                                            <option value="7">7 Days</option>
                                            <option value="15">15 Days</option>
                                            <option value="30">30 Days</option>
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
                @endforeach
            </tbody>
        </table>

            <div class="d-flex justify-content-between">
                <span>Total Clients: {{ $hotspotClients->total() }}</span>
                <div>
                   {!! $hotspotClients->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}

                </div>
            </div>

    </div>
@endsection
