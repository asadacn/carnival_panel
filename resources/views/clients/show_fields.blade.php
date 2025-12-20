<div class="container mt-4">

    <!-- Client Overview -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-user-circle me-2"></i>Client Overview</h5>
        </div>
        <div class="card-body">


            <div class="row g-3">
                <div class="col-md-4"><strong>Name:</strong> <span class="text-muted">{{ $client->name }}</span></div>
                <div class="col-md-4"><strong>Contact:</strong> <span class="text-muted">{{ $client->contact ?? '-' }}</span></div>
                <div class="col-md-4"><strong>Secondary Contact:</strong> <span class="text-muted">{{ $client->secondary_contact ?? '-' }}</span></div>
                <div class="col-md-4"><strong>Address:</strong> <span class="text-muted">{{ $client->address ?? '-' }}</span></div>
                <div class="col-md-4"><strong>Package:</strong> <span class="text-muted">{{ $client->package ?? '-' }}</span></div>
                <div class="col-md-4"><strong>Carnival ID:</strong> <span class="text-muted">{{ $client->username ?? '-' }}</span></div>
            </div>


        </div>

                            <!-- Client Summary Badges -->
        <div class="m-4 p-3 bg-white rounded shadow-sm d-flex flex-wrap gap-3 align-items-center">
            <!-- Status Badge -->
            <span class="badge {{ $client->status == 'registered' ? 'bg-success' : 'bg-danger' }}">
                Status: {{ ucfirst($client->status) }}
            </span>

            <!-- ONU Returned Badge -->
            <span class="badge {{ $client->onu_returned ? 'bg-success' : 'bg-danger' }}">
                ONU Returned: {{ $client->onu_returned ? 'Yes' : 'No' }}
            </span>

            <!-- Cable Returned Badge -->
            <span class="badge {{ $client->cable_returned ? 'bg-success' : 'bg-danger' }}">
                Cable Returned: {{ $client->cable_returned ? 'Yes' : 'No' }}
            </span>
        </div>
    </div>

    <!-- Equipment Info -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5><i class="fas fa-network-wired me-2"></i>Equipment Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><strong>ONU MAC:</strong> <span class="text-muted">{{ $client->Onu_mac ?? '-' }}</span></div>
                <div class="col-md-4"><strong>ONU Serial:</strong> <span class="text-muted">{{ $client->onu_serial ?? '-' }}</span></div>
                <div class="col-md-4"><strong>ONU Brand:</strong> <span class="text-muted">{{ $client->onu_brand ?? '-' }}</span></div>

                <div class="col-md-4">
                    <strong>ONU Free:</strong>
                    <span class="badge bg-{{ $client->onu_free ? 'success' : 'danger' }}"
                          data-bs-toggle="tooltip"
                          title="{{ $client->onu_free ? 'Free ONU provided' : 'ONU not free' }}">
                        <i class="fas fa-circle me-1"></i>{{ $client->onu_free ? 'Yes' : 'No' }}
                    </span>
                </div>

                <div class="col-md-4">
                    <strong>ONU Returned:</strong>
                    <span class="badge bg-{{ $client->onu_returned ? 'success' : 'danger' }}"
                          data-bs-toggle="tooltip"
                          title="{{ $client->onu_returned ? 'ONU returned' : 'ONU not returned' }}">
                        <i class="fas fa-undo me-1"></i>{{ $client->onu_returned ? 'Yes' : 'No' }}
                    </span>
                </div>

                <div class="col-md-4"><strong>ONU Owner:</strong> <span class="text-muted">{{ ucfirst($client->onu_owner ?? 'client') }}</span></div>

                <div class="col-md-4"><strong>Cable:</strong> <span class="text-muted">{{ $client->cable ?? '-' }}</span></div>

                <div class="col-md-4">
                    <strong>Cable Returned:</strong>
                    <span class="badge bg-{{ $client->cable_returned ? 'success' : 'danger' }}"
                          data-bs-toggle="tooltip"
                          title="{{ $client->cable_returned ? 'Cable returned' : 'Cable not returned' }}">
                        <i class="fas fa-undo me-1"></i>{{ $client->cable_returned ? 'Yes' : 'No' }}
                    </span>
                </div>

                <div class="col-md-4"><strong>Cable Owner:</strong> <span class="text-muted">{{ ucfirst($client->cable_owner ?? 'company') }}</span></div>
                <div class="col-md-4"><strong>Billing Type:</strong> <span class="text-muted">{{ ucfirst($client->billing_type ?? 'prepaid') }}</span></div>
                <div class="col-md-4"><strong>GPS Location:</strong> <span class="text-muted">{{ $client->gps_location ?? '-' }}</span></div>
            </div>
        </div>
    </div>

    <!-- Status & Comments -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-info-circle me-2"></i>Status & Comments</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $client->status == 'registered' ? 'success' : 'danger' }}"
                          data-bs-toggle="tooltip"
                          title="{{ ucfirst($client->status) }}">
                        <i class="fas fa-info-circle me-1"></i>{{ ucfirst($client->status) }}
                    </span>
                </div>
                <div class="col-md-6"><strong>Comment:</strong> <span class="text-muted">{{ $client->comment ?? '-' }}</span></div>
                 <div class="col-md-6"><strong>ISP:</strong> <span class="text-muted">{{ $client->isp_code ?? '-' }}</span></div>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5><i class="fas fa-clock me-2"></i>Timestamps</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><strong>Created At:</strong> <span class="text-muted">{{ $client->created_at }}</span></div>
                <div class="col-md-6"><strong>Updated At:</strong> <span class="text-muted">{{ $client->updated_at }}</span></div>
            </div>
        </div>
    </div>

</div>

<!-- Initialize Bootstrap Tooltips -->
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
