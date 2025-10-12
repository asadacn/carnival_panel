<div class="container-fluid py-4">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="bg-light rounded-4 shadow-sm p-4">

        <h2 class="text-center fw-bold mb-4">🎫 Ticket Manager</h2>

        {{-- Alerts --}}
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Create Ticket --}}
        <div class="card mb-5 border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                🧾 Create New Ticket
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Client Search --}}
                    <div class="col-md-6 position-relative">
                        <input type="text" class="form-control" placeholder="Search client..." wire:model.debounce.300ms="searchClient">
                        @if(!empty($clients))
                            <ul class="list-group position-absolute w-100 mt-1 shadow-sm">
                                @foreach($clients as $client)
                                    <li class="list-group-item list-group-item-action"
                                        wire:click="selectClient({{ $client->id }})">
                                        <strong>{{ $client->username }}</strong> — {{ $client->contact }}
                                        <br><small class="text-muted">{{ $client->name ?? 'N/A' }} | {{ $client->package ?? 'N/A' }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <select class="form-select" wire:model="complain_type_id">
                            <option value="">Select Complain Type</option>
                            @foreach($complainTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <select class="form-select" wire:model="priority">
                            <option value="low">Low Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="high">High Priority</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <textarea class="form-control" wire:model="description" rows="3" placeholder="Describe the issue..."></textarea>
                    </div>

                    {{-- Selected Client Card --}}
                    @if($selectedClient)
                        <div class="col-12">
                            <div class="card border-info shadow-sm">
                                <div class="card-header bg-info bg-opacity-10 fw-semibold text-info">
                                    👤 Selected Client
                                </div>
                                <div class="card-body small">
                                    <h6 class="mb-1">{{ $selectedClient->username }} — {{ $selectedClient->name }}</h6>
                                    <p class="mb-1"><strong>📞</strong> {{ $selectedClient->contact }}</p>
                                    <p class="mb-1"><strong>📦</strong> {{ $selectedClient->package ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong>📍</strong> {{ $selectedClient->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-6 text-end">
                        <button class="btn btn-success  px-4" wire:click="submitTicket">
                            🚀 Submit Ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white fw-semibold">
                📋 Active Tickets
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Technician</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Assign</th>
                                <th>Timeline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $ticket->client->username ?? '' }}</div>
                                        <div class="fw-semibold">{{ $ticket->client->name ?? '' }}</div>

                                        <small class="text-muted">{{ $ticket->client->contact ?? '' }}</small>
                                    </td>
                                    <td>{{ $ticket->technician->name ?? 'Unassigned' }}</td>
                                    <td>{{ $ticket->complainType->name ?? '' }}</td>
                                    <td>{{ $ticket->description }}</td>

                                    {{-- Priority --}}
                                    @php $priorityColors = ['low'=>'success','medium'=>'warning','high'=>'danger']; @endphp
                                    <td><span class="badge bg-{{ $priorityColors[$ticket->priority] ?? 'secondary' }}">{{ ucfirst($ticket->priority) }}</span></td>

                                    {{-- Status --}}
                                    @php $statusColors = ['pending'=>'secondary','in_progress'=>'info','resolved'=>'success','closed'=>'dark']; @endphp
                                    <td>
                                        <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }}">{{ ucfirst($ticket->status) }}</span>
                                        <select class="form-select form-select-sm mt-1"
                                            onchange="if(this.value){ Swal.fire({
                                                title:'Update Status?',
                                                text:'Confirm status change.',
                                                icon:'question',
                                                showCancelButton:true,
                                                confirmButtonText:'Yes',
                                                cancelButtonText:'No'
                                            }).then((r)=>{ if(r.isConfirmed){ @this.call('updateStatus', {{ $ticket->id }}, this.value); this.value=''; } else { this.value=''; } }); }">
                                            <option value="">Change</option>
                                            <option value="pending">Pending</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="resolved">Resolved</option>
                                            <option value="closed">Closed</option>
                                        </select>
                                    </td>

                                    {{-- Assign Technician --}}
                                    <td>
                                        <div class="d-flex gap-1">
                                            <select class="form-select form-select-sm" wire:model="technician_id">
                                                <option value="">Select</option>
                                                @foreach($technicians as $tech)
                                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="Swal.fire({
                                                    title:'Assign Technician?',
                                                    text:'Confirm assignment.',
                                                    icon:'question',
                                                    showCancelButton:true,
                                                    confirmButtonText:'Yes',
                                                    cancelButtonText:'No'
                                                }).then((r)=>{ if(r.isConfirmed){ @this.call('assignTechnician', {{ $ticket->id }}); } });">
                                                ✔
                                            </button>
                                        </div>
                                    </td>

                                    {{-- Timeline --}}
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#timeline-{{ $ticket->id }}">
                                            🕓 View
                                        </button>
                                    </td>
                                </tr>

                                <tr class="collapse bg-light" id="timeline-{{ $ticket->id }}">
                                    <td colspan="9">
                                        <ul class="list-group list-group-flush small">
                                            @foreach($ticket->timeline ?? [] as $t)
                                                <li class="list-group-item">
                                                    <strong>{{ $t->created_at }}</strong> —
                                                    {{ $t->performed_by }} → {{ $t->action }}
                                                    <span class="text-muted">({{ $t->note }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
