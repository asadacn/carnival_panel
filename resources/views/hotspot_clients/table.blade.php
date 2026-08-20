{{-- Metrics Cards --}}
<div class="row mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="card premium-card card-active mb-3 border-0 shadow-sm rounded-4 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2 tracking-wide">Active Clients</h6>
                        <h2 class="mb-0 fw-bolder text-dark">{{ $hotspotClients->where('status', 'active')->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-success-soft text-success rounded-circle p-3">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-progress bg-success" style="height: 4px; width: 100%; position: absolute; bottom: 0;"></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card premium-card card-expired mb-3 border-0 shadow-sm rounded-4 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2 tracking-wide">Expired Clients</h6>
                        <h2 class="mb-0 fw-bolder text-dark">{{ $hotspotClients->filter(fn($c) => $c->status == 'active' && $c->isExpired())->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-danger-soft text-danger rounded-circle p-3">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-progress bg-danger" style="height: 4px; width: 100%; position: absolute; bottom: 0;"></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card premium-card card-inactive mb-3 border-0 shadow-sm rounded-4 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2 tracking-wide">Inactive Clients</h6>
                        <h2 class="mb-0 fw-bolder text-dark">{{ $hotspotClients->where('status', 'inactive')->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-secondary-soft text-secondary rounded-circle p-3">
                        <i class="fas fa-user-slash fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-progress bg-secondary" style="height: 4px; width: 100%; position: absolute; bottom: 0;"></div>
        </div>
    </div>
</div>

{{-- Data Table Card --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-list text-primary me-2"></i> Client List</h5>
        <form action="{{ route('hotspotClients.index') }}" method="GET" class="d-flex mt-2 mt-md-0">
            <div class="input-group input-group-sm search-group shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" class="form-control border-0 shadow-none bg-white" placeholder="Search clients...">
                <button class="btn btn-primary px-3" type="submit">Search</button>
            </div>
        </form>
    </div>

    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle premium-table mb-0">
                <thead class="bg-light text-uppercase text-secondary text-sm">
                    <tr>
                        <th class="ps-4">Client Info</th>
                        <th>Hardware</th>
                        <th>Package Info</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($hotspotClients as $key => $client)
                    <tr class="table-row-hover">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 45px; height: 45px;">
                                    {{ substr($client->name, 0, 2) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $client->name }}</h6>
                                    <small class="text-muted"><i class="fas fa-phone-alt me-1 text-primary"></i>{{ $client->contact }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-dark fw-medium"><i class="fas fa-network-wired me-1 text-info"></i> {{ $client->onu_mac ?: 'N/A' }}</span>
                                <small class="text-muted text-truncate" style="max-width: 150px;" title="{{ $client->adrress }}"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $client->adrress ?: 'N/A' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $client->package_days ? $client->package_days . ' Days' : 'N/A' }}</span>
                                @if($client->expires_at)
                                    <small class="text-muted">Exp: {{ $client->expires_at->format('d M, Y') }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($client->status == 'active' && $client->isExpired())
                                <span class="badge rounded-pill bg-danger-soft text-danger px-3 py-2 fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Expired</span>
                            @elseif($client->status == 'active')
                                <span class="badge rounded-pill bg-success-soft text-success px-3 py-2 fw-bold"><i class="fas fa-check-circle me-1"></i> Active</span>
                            @else
                                <span class="badge rounded-pill bg-secondary-soft text-secondary px-3 py-2 fw-bold"><i class="fas fa-minus-circle me-1"></i> Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                {{-- Quick Renew --}}
                                @php
                                    $showRenew = !$client->activated_at || $client->isExpired() || ($client->expires_at && $client->expires_at->subDay()->isPast());
                                @endphp
                                @if($showRenew)
                                    <form method="POST" action="{{ route('hotspotClients.activate', $client->id) }}" class="m-0 d-inline-block">
                                        @csrf
                                        <div class="input-group input-group-sm flex-nowrap" style="width: 120px;">
                                            <select name="days" class="form-select form-select-sm border-success text-success shadow-none rounded-start-pill">
                                                <option value="30">30d</option>
                                                <option value="15">15d</option>
                                                <option value="7">7d</option>
                                                <option value="1">1d</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm rounded-end-pill px-2" title="Quick Renew"><i class="fas fa-bolt"></i></button>
                                        </div>
                                    </form>
                                @endif

                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle btn-icon shadow-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li><a class="dropdown-item" href="{{ route('hotspotClients.show', $client->id) }}"><i class="fas fa-eye text-primary me-2"></i> View Details</a></li>
                                        <li><a class="dropdown-item" href="{{ route('hotspotClients.edit', $client->id) }}"><i class="fas fa-edit text-warning me-2"></i> Edit Client</a></li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="openExpiryModal({{ $client->id }}, '{{ addslashes($client->name) }}', '{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : '' }}', '{{ $client->status }}')">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i> Edit Expiry Date
                                            </a>
                                        </li>
                                        @if($client->status == 'active')
                                        <li>
                                            {!! Form::open(['route' => ['hotspotClients.sendSms', $client->id], 'method' => 'post', 'class' => 'm-0']) !!}
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Send SMS Reminder now?')"><i class="fas fa-sms text-info me-2"></i> Send SMS</button>
                                            {!! Form::close() !!}
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            {!! Form::open(['route' => ['hotspotClients.destroy', $client->id], 'method' => 'delete', 'class' => 'm-0']) !!}
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this client?')"><i class="fas fa-trash text-danger me-2"></i> Delete</button>
                                            {!! Form::close() !!}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users fa-3x text-muted mb-3 opacity-50"></i>
                                <h6 class="text-muted">No hotspot clients found.</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top-0 pt-3 pb-4 px-4 d-flex justify-content-between align-items-center">
        <span class="text-muted small fw-bold">Total Clients: {{ $hotspotClients->total() }}</span>
        <div class="pagination-wrapper">
            {!! $hotspotClients->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
        </div>
    </div>
</div>

<style>
    /* Metrics Cards Styling */
    .premium-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
    }
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .icon-shape {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
    .bg-primary-soft { background-color: rgba(0, 123, 255, 0.1); }
    .bg-info-soft { background-color: rgba(23, 162, 184, 0.1); }
    
    .tracking-wide { letter-spacing: 1px; }

    /* Table Styling */
    .premium-table th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f3f5;
    }
    .premium-table td {
        vertical-align: middle;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #f8f9fa;
    }
    .table-row-hover {
        transition: background-color 0.2s;
    }
    .table-row-hover:hover {
        background-color: #f8f9fa;
    }
    
    /* Search Group */
    .search-group {
        border: 1px solid #e9ecef;
    }
    .search-group:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* Dropdown */
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    
    /* Pagination Wrapper */
    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }
    .pagination-wrapper .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
    }
</style>
