<div class="container py-4">
    <h2 class="mb-4 text-primary fw-bold">🎫 Ticket Manager</h2>

    {{-- Success/Error Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Create Ticket Card --}}
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">Create New Ticket</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Search Client by Username / Contact..." wire:model="searchClient">
                </div>
                <div class="col-md-6">
                    <select class="form-select" wire:model="client_id">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->username }} | {{ $client->contact }}</option>
                        @endforeach
                    </select>
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
                    <textarea class="form-control" wire:model="description" placeholder="Describe the issue..." rows="3"></textarea>
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-success" wire:click="submitTicket">Submit Ticket</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tickets Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">Active Tickets</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Technician</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assign Technician</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->client->username ?? '' }} | {{ $ticket->client->contact ?? '' }}</td>
                        <td>{{ $ticket->technician->name ?? 'Unassigned' }}</td>
                        <td>{{ $ticket->complainType->name ?? '' }}</td>
                        <td>{{ $ticket->description }}</td>
                        <td>
                            <span class="badge
                                @if($ticket->priority == 'low') bg-success
                                @elseif($ticket->priority == 'medium') bg-warning
                                @else bg-danger @endif">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge
                                @if($ticket->status == 'pending') bg-secondary
                                @elseif($ticket->status == 'in_progress') bg-primary
                                @elseif($ticket->status == 'resolved') bg-success
                                @else bg-dark @endif">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" wire:model="technician_id">
                                    <option value="">Select Tech</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-success" wire:click="assignTechnician({{ $ticket->id }})">Assign</button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <select class="form-select form-select-sm" wire:change="updateStatus({{ $ticket->id }}, $event.target.value)">
                                    <option value="">Change Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <button class="btn btn-info btn-sm mt-1" type="button" data-bs-toggle="collapse" data-bs-target="#timeline-{{ $ticket->id }}">
                                    Timeline
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="collapse bg-light" id="timeline-{{ $ticket->id }}">
                        <td colspan="9">
                            <ul class="list-group list-group-flush">
                                @foreach($ticket->timeline ?? [] as $t)
                                    <li class="list-group-item">
                                        <small class="text-muted">{{ $t->created_at }} | {{ $t->performed_by }} | {{ $t->action }} | {{ $t->note }}</small>
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
