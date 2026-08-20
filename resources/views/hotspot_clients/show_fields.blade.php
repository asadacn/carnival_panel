<div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center">
            <div class="avatar bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 45px; height: 45px; font-size: 1.2rem;">
                {{ substr($hotspotClient->name, 0, 2) }}
            </div>
            <h4 class="mb-0 fw-bold">{{ $hotspotClient->name }}</h4>
        </div>
        <span class="badge rounded-pill px-3 py-2 fs-6 shadow-sm @if($hotspotClient->status == 'active' && $hotspotClient->isExpired()) bg-danger text-white
                              @elseif($hotspotClient->status == 'active') bg-success text-white
                              @else bg-secondary text-white @endif">
            @if($hotspotClient->status == 'active' && $hotspotClient->isExpired())
                <i class="fas fa-exclamation-circle me-1"></i> Expired
            @elseif($hotspotClient->status == 'active')
                <i class="fas fa-check-circle me-1"></i> Active
            @else
                <i class="fas fa-minus-circle me-1"></i> Inactive
            @endif
        </span>
    </div>

    <div class="card-body p-4 bg-light">
        <div class="row g-4">
            <!-- Client Info -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fas fa-id-card me-2"></i> Client Information</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-phone-alt me-2 w-20px"></i> Contact</span>
                                <span class="fw-bold">{{ $hotspotClient->contact }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-map-marker-alt me-2 w-20px"></i> Address</span>
                                <span class="fw-bold text-end">{{ $hotspotClient->adrress ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-calendar-plus me-2 w-20px"></i> Created At</span>
                                <span class="fw-bold">{{ $hotspotClient->created_at->format('d M, Y H:i') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Connection Info -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="text-info fw-bold mb-3 border-bottom pb-2"><i class="fas fa-network-wired me-2"></i> Connection Details</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-ethernet me-2 w-20px"></i> ONU MAC</span>
                                <span class="fw-bold text-dark font-monospace">{{ $hotspotClient->onu_mac ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-hdd me-2 w-20px"></i> ONU Owner</span>
                                <span class="badge bg-light text-dark border">{{ $hotspotClient->onu_owner ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-plug me-2 w-20px"></i> Cable Length</span>
                                <span class="fw-bold">{{ $hotspotClient->cable ? $hotspotClient->cable . ' m' : '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-user-tag me-2 w-20px"></i> Cable Owner</span>
                                <span class="badge bg-light text-dark border">{{ $hotspotClient->cable_owner ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Package Info -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="text-success fw-bold mb-3 border-bottom pb-2"><i class="fas fa-box-open me-2"></i> Package & Billing Details</h6>
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Package Days</h6>
                                    <h3 class="mb-0 text-success fw-bold">{{ $hotspotClient->package_days ?? '-' }}</h3>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><strong>Activated:</strong> {{ $hotspotClient->activated_at?->format('d M, Y') ?? '-' }}</span>
                                    <span><strong>Expires:</strong> {{ $hotspotClient->expires_at?->format('d M, Y') ?? '-' }}</span>
                                </div>
                                @if($hotspotClient->expires_at)
                                    @php
                                        $totalDays = $hotspotClient->package_days ?? 0;
                                        $remainingDays = $hotspotClient->expires_at->isPast() ? 0 : $hotspotClient->expires_at->diffInDays(now());
                                        $progress = $totalDays && $totalDays > 0 ? (100 - ($remainingDays/$totalDays*100)) : 0;
                                        $progress = min(100, max(0, $progress));
                                    @endphp
                                    <div class="progress rounded-pill shadow-sm" style="height: 25px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated @if($hotspotClient->isExpired()) bg-danger @else bg-success @endif"
                                             role="progressbar"
                                             style="width: {{ $progress }}%"
                                             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                             <span class="fw-bold px-2">{{ $remainingDays }} days remaining</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-secondary mb-0 py-2 text-center rounded-pill">
                                        <i class="fas fa-info-circle me-2"></i> No active package schedule found.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer bg-white border-top-0 p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <small class="text-muted"><i class="fas fa-clock me-1"></i> Last updated: {{ $hotspotClient->updated_at->diffForHumans() }}</small>
        </div>
        <div class="d-flex flex-wrap gap-2">
            {{-- Manual SMS --}}
            @if($hotspotClient->status == 'active')
                {!! Form::open(['route' => ['hotspotClients.sendSms', $hotspotClient->id], 'method' => 'post', 'class' => 'm-0']) !!}
                    {!! Form::button('<i class="fas fa-sms me-1"></i> Send SMS', [
                        'type' => 'submit',
                        'class' => 'btn btn-outline-info shadow-sm rounded-pill fw-bold',
                        'onclick' => 'return confirm("Send SMS Reminder now?")'
                    ]) !!}
                {!! Form::close() !!}
            @endif

            {{-- Edit Expiry Date --}}
            <button type="button" class="btn btn-outline-primary shadow-sm rounded-pill fw-bold"
                    onclick="openShowExpiryModal('{{ $hotspotClient->expires_at ? $hotspotClient->expires_at->format('Y-m-d') : '' }}', '{{ $hotspotClient->status }}')">
                <i class="fas fa-calendar-alt me-1"></i> Edit Expiry
            </button>

            {{-- Edit --}}
            <a href="{{ route('hotspotClients.edit', $hotspotClient->id) }}" class="btn btn-warning shadow-sm rounded-pill fw-bold text-dark">
                <i class="fas fa-edit me-1"></i> Edit
            </a>

            {{-- Delete --}}
            {!! Form::open(['route' => ['hotspotClients.destroy', $hotspotClient->id], 'method' => 'delete', 'class'=>'m-0']) !!}
                {!! Form::button('<i class="fas fa-trash me-1"></i> Delete', [
                    'type' => 'submit',
                    'class' => 'btn btn-danger shadow-sm rounded-pill fw-bold',
                    'onclick' => 'return confirm("Are you sure you want to permanently delete this client?")'
                ]) !!}
            {!! Form::close() !!}

            {{-- Package Renewal --}}
            @php
                $showRenew = false;
                if(!$hotspotClient->activated_at || $hotspotClient->isExpired() || ($hotspotClient->expires_at && $hotspotClient->expires_at->subDay()->isPast())) {
                    $showRenew = true;
                }
            @endphp
            @if($showRenew)
                <form method="POST" action="{{ route('hotspotClients.activate', $hotspotClient->id) }}" class="m-0 ms-2">
                    @csrf
                    <div class="input-group">
                        <select name="days" class="form-select border-success text-success shadow-sm rounded-start-pill fw-bold" style="width: auto; max-width: 120px;">
                            <option value="30">30 Days</option>
                            <option value="15">15 Days</option>
                            <option value="7">7 Days</option>
                            <option value="1">1 Day</option>
                        </select>
                        <button type="submit" class="btn btn-success shadow-sm rounded-end-pill fw-bold px-4"><i class="fas fa-bolt me-1"></i> Renew</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<style>
    .w-20px {
        width: 20px;
        text-align: center;
    }
    .list-group-item {
        border-color: #f1f3f5;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .card {
        transition: all 0.3s ease;
    }
    .progress {
        background-color: #e9ecef;
        overflow: visible;
    }
    .progress-bar {
        transition: width 1s ease;
        border-radius: 50px;
        position: relative;
        overflow: visible;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .progress-bar span {
        white-space: nowrap;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
</style>

<style>
    .w-20px {
        width: 20px;
        text-align: center;
    }
    .list-group-item {
        border-color: #f1f3f5;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .card {
        transition: all 0.3s ease;
    }
    .progress {
        background-color: #e9ecef;
        overflow: visible;
    }
    .progress-bar {
        transition: width 1s ease;
        border-radius: 50px;
        position: relative;
        overflow: visible;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .progress-bar span {
        white-space: nowrap;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
</style>
