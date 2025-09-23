@extends('layouts.app')
@section('title', $hotspotClient->name . ' Details')

@section('content')
<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Hotspot Client Details</h1>
        <a href="{{ route('hotspotClients.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="section-body mt-3">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $hotspotClient->name }}</h4>
                <span class="badge @if($hotspotClient->status == 'active' && $hotspotClient->isExpired()) bg-danger
                                      @elseif($hotspotClient->status == 'active') bg-success
                                      @else bg-secondary @endif">
                    @if($hotspotClient->status == 'active' && $hotspotClient->isExpired())
                        Expired
                    @elseif($hotspotClient->status == 'active')
                        Active
                    @else
                        Inactive
                    @endif
                </span>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong>Contact:</strong> {{ $hotspotClient->contact }}</div>
                    <div class="col-md-6"><strong>Cable:</strong> {{ $hotspotClient->cable ?? '-' }}</div>
                    <div class="col-md-6"><strong>Cable Owner:</strong> {{ $hotspotClient->cable_owner ?? '-' }}</div>
                    <div class="col-md-6"><strong>ONU MAC:</strong> {{ $hotspotClient->onu_mac ?? '-' }}</div>
                    <div class="col-md-6"><strong>ONU Owner:</strong> {{ $hotspotClient->onu_owner ?? '-' }}</div>
                    <div class="col-md-6"><strong>Address:</strong> {{ $hotspotClient->adrress ?? '-' }}</div>
                    <div class="col-md-6"><strong>Package Days:</strong> {{ $hotspotClient->package_days ?? '-' }}</div>
                    <div class="col-md-6"><strong>Activated At:</strong> {{ $hotspotClient->activated_at?->format('d M, Y') ?? '-' }}</div>
                    <div class="col-md-6"><strong>Expires At:</strong> {{ $hotspotClient->expires_at?->format('d M, Y') ?? '-' }}
                        @if($hotspotClient->expires_at)
                            @php
                                $totalDays = $hotspotClient->package_days ?? 0;
                                $remainingDays = $hotspotClient->expires_at->diffInDays(now());
                                $progress = $totalDays ? (100 - ($remainingDays/$totalDays*100)) : 0;
                            @endphp
                            <div class="progress mt-1">
                                <div class="progress-bar @if($hotspotClient->isExpired()) bg-danger @else bg-success @endif"
                                     style="width: {{ $progress }}%">
                                     {{ $remainingDays }} days remaining
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6"><strong>Created At:</strong> {{ $hotspotClient->created_at->format('d M, Y H:i') }}</div>
                    <div class="col-md-6"><strong>Last Updated:</strong> {{ $hotspotClient->updated_at->diffForHumans() }}</div>
                </div>
            </div>

            <div class="card-footer d-flex flex-wrap gap-2">
                {{-- Edit --}}
                <a href="{{ route('hotspotClients.edit', $hotspotClient->id) }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </a>

                {{-- Delete --}}
                {!! Form::open(['route' => ['hotspotClients.destroy', $hotspotClient->id], 'method' => 'delete', 'class'=>'d-inline']) !!}
                    {!! Form::button('<i class="fa fa-trash"></i> Delete', [
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-sm',
                        'onclick' => 'return confirm("Are you sure?")'
                    ]) !!}
                {!! Form::close() !!}

                {{-- Manual SMS --}}
                @if($hotspotClient->status == 'active')
                    <a href="{{ route('hotspotClients.sendSms', $hotspotClient->id) }}"
                       class="btn btn-info btn-sm"
                       onclick="return confirm('Send SMS Reminder now?')">
                       <i class="fa fa-sms"></i> SMS Reminder
                    </a>
                @endif

                {{-- Package Renewal --}}
                @php
                    $showRenew = false;
                    if(!$hotspotClient->activated_at || $hotspotClient->isExpired() || ($hotspotClient->expires_at && $hotspotClient->expires_at->subDay()->isPast())) {
                        $showRenew = true;
                    }
                @endphp
                @if($showRenew)
                    <form method="POST" action="{{ route('hotspotClients.activate', $hotspotClient->id) }}" class="d-inline">
                        @csrf
                        <div class="input-group input-group-sm">
                            <select name="days" class="form-select">
                                @for($i=1; $i<=30; $i++)
                                    <option value="{{ $i }}">{{ $i }} Day{{ $i>1?'s':'' }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-success btn-sm">Renew</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.progress {
    height: 20px;
    border-radius: 10px;
}
.progress-bar {
    font-size: 0.85rem;
    line-height: 20px;
}
@media (max-width: 576px) {
    .card-body .row > div {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>
@endsection
