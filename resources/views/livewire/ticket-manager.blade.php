<div class="container py-5">

    {{-- 🔥 Glass + Fade Animations CSS --}}
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            color: #111;
        }
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .blur-bg {
            background: linear-gradient(135deg, rgba(248,248,248,0.3), rgba(240,248,255,0.2));
            backdrop-filter: blur(15px);
            min-height: 100vh;
            padding-bottom: 50px;
        }
        .list-group-item:hover {
            background-color: rgba(203, 251, 108, 0.15);
            cursor: pointer;
        }
        .badge-modern {
            padding: 0.35em 0.75em;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
            display: inline-block;
        }
    </style>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="blur-bg rounded-4 p-4 shadow-lg">

        <h2 class="text-center mb-4 fw-bold">🎫 Ticket Manager (Glass UI)</h2>

        {{-- Alerts --}}
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show glass-card">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show glass-card">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Create Ticket --}}
        <div class="card glass-card mb-5 p-3 fade-in">
            <div class="card-header bg-transparent border-0 fw-bold fs-5">🧾 Create New Ticket</div>
            <div class="card-body">
                <div class="row g-3">

                    {{-- Client Search --}}
                    <div class="col-md-6 position-relative">
                        <input type="text" class="form-control" placeholder="Search client by username/contact..." wire:model.debounce.300ms="searchClient">

                        @if(!empty($clients))
                            <ul class="list-group position-absolute w-100 mt-1 shadow glass-card z-index-20">
                                @foreach($clients as $client)
                                    <li class="list-group-item list-group-item-action" wire:click="selectClient({{ $client->id }})">
                                        <strong>{{ $client->username }}</strong> — {{ $client->contact }}<br>
                                        <small class="text-muted">{{ $client->name ?? 'N/A' }}</small> | <small class="text-muted">{{ $client->package ?? 'N/A' }}</small>
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
                        <div class="col-12 fade-in">
                            <div class="card glass-card border-info">
                                <div class="card-header bg-transparent border-info fw-bold">👤 Selected Client</div>
                                <div class="card-body">
                                    <h5>{{ $selectedClient->username }} | {{ $selectedClient->name }}</h5>
                                    <p class="mb-1"><strong>📞 Contact:</strong> {{ $selectedClient->contact }}</p>
                                    <p class="mb-1"><strong>📦 Package:</strong> {{ $selectedClient->package ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong>📍 Address:</strong> {{ $selectedClient->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-12 text-end">
                        <button class="btn btn-success px-4 py-2 fw-bold shadow" wire:click="submitTicket">
                            🚀 Submit Ticket
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="card glass-card fade-in">
            <div class="card-header bg-transparent fw-bold fs-5">📋 Active Tickets</div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle text-dark">
                    <thead>
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
                            <tr class="fade-in">
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->client->username ?? '' }}<br><small>{{ $ticket->client->contact ?? '' }}</small></td>
                                <td>{{ $ticket->technician->name ?? 'Unassigned' }}</td>
                                <td>{{ $ticket->complainType->name ?? '' }}</td>
                                <td>{{ $ticket->description }}</td>

                                {{-- Priority Badge --}}
                                @php $priorityColors = ['low'=>'#4caf50','medium'=>'#ffc107','high'=>'#f44336']; @endphp
                                <td><span class="badge-modern" style="background: {{ $priorityColors[$ticket->priority] ?? '#999' }}">{{ ucfirst($ticket->priority) }}</span></td>

                                {{-- Status Badge + Dropdown --}}
                                @php $statusColors = ['pending'=>'#bdbdbd','in_progress'=>'#2196f3','resolved'=>'#4caf50','closed'=>'#9e9e9e']; @endphp
                                <td>
                                    <span class="badge-modern mb-1" style="background: {{ $statusColors[$ticket->status] ?? '#999' }}">{{ ucfirst($ticket->status) }}</span>
                                    <select class="form-select form-select-sm" onchange="if(this.value){ Swal.fire({
                                        title:'Update Status?',
                                        text:'Are you sure you want to update status?',
                                        icon:'question',
                                        showCancelButton:true,
                                        confirmButtonText:'Yes',
                                        cancelButtonText:'No'
                                    }).then((result)=>{ if(result.isConfirmed){ @this.call('updateStatus', {{ $ticket->id }}, this.value); this.value=''; } else { this.value=''; } }); }">
                                        <option value="">Change</option>
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </td>

                                {{-- Assign Technician --}}
                                <td>
                                    <select class="form-select form-select-sm" wire:model="technician_id">
                                        <option value="">Select</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-success mt-1"
                                        onclick="Swal.fire({
                                            title:'Assign Technician?',
                                            text:'Are you sure you want to assign this technician?',
                                            icon:'question',
                                            showCancelButton:true,
                                            confirmButtonText:'Yes',
                                            cancelButtonText:'No'
                                        }).then((result)=>{ if(result.isConfirmed){ @this.call('assignTechnician', {{ $ticket->id }}); } });">
                                        ✔
                                    </button>
                                </td>

                                {{-- Timeline --}}
                                <td>
                                    <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#timeline-{{ $ticket->id }}">
                                        🕓 View
                                    </button>
                                </td>
                            </tr>

                            <tr class="collapse" id="timeline-{{ $ticket->id }}">
                                <td colspan="9">
                                    <ul class="list-group list-group-flush glass-card">
                                        @foreach($ticket->timeline ?? [] as $t)
                                            <li class="list-group-item bg-transparent">
                                                <small>{{ $t->created_at }} | {{ $t->performed_by }} | {{ $t->action }} | {{ $t->note }}</small>
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
