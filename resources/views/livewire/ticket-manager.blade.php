<div class="container py-4">
    <style>
        /* Modern touches */
        .card-modern { border: 0; border-radius: 12px; box-shadow: 0 6px 18px rgba(22,24,26,0.08); }
        .search-suggestions li:hover { background: #f8fafc; transform: translateY(-1px); }
        .avatar-circle { width:44px; height:44px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:600; color:#fff; }
        .chip { border-radius:999px; padding:6px 10px; background:#f1f5f9; display:inline-flex; gap:8px; align-items:center; }
        .muted-small { font-size:.85rem; color:#6b7280; }
    </style>

    {{-- Top Controls --}}
    <div class="d-flex gap-3 mb-4 align-items-center bg-white p-3 rounded shadow-sm">
        <h4 class="mb-0 fw-semibold">Ticket Manager</h4>
        <small class="muted-small">Fast, clear and modern UI</small>

        <div class="ms-auto d-flex gap-2 align-items-center">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" wire:model="send_sms" id="smsSwitch">
                <label class="form-check-label muted-small" for="smsSwitch">SMS</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" wire:model="send_telegram" id="tgSwitch">
                <label class="form-check-label muted-small" for="tgSwitch">Telegram</label>
            </div>
        </div>
    </div>

    {{-- Create Ticket Card --}}
    <div class="card card-modern mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-1 fw-bold">নতুন টিকেট</h5>
                    <small class="muted-small">Quickly create a ticket with recommended defaults</small>
                </div>
                <div class="d-flex gap-2">
                    <button wire:click="$set('search','')" class="btn btn-sm btn-outline-secondary" title="Reset form">Reset</button>
                </div>
            </div>

            {{-- Client Search --}}
            <div class="mb-3 position-relative">
                <label class="form-label small text-muted">ক্লায়েন্ট খুঁজুন</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">🔎</span>
                    <input type="text"
                           wire:model.debounce.400ms="search"
                           wire:keydown="$set('selectedClient', null)"
                           class="form-control rounded-start"
                           placeholder="Name, ID or Contact..."
                           aria-autocomplete="list"
                           aria-expanded="{{ (!empty($clients) && $search != '' && !$selectedClient) ? 'true' : 'false' }}">
                    @if($search)
                        <button type="button" wire:click="$set('search','')" class="btn btn-light border">✖</button>
                    @endif
                </div>

                @if (!empty($clients) && $search != '' && !$selectedClient)
                    <ul class="list-group position-absolute w-100 mt-2 shadow-sm rounded search-suggestions" style="z-index:1000; max-height:260px; overflow:auto;">
                        @foreach ($clients as $client)
                            <li class="list-group-item list-group-item-action d-flex gap-3 align-items-center"
                                wire:click="$set('selectedClient', {{ $client->id }})" style="cursor:pointer;">
                                <div>
                                    <div class="avatar-circle" style="background:#0d6efd;">
                                        {{ strtoupper(substr($client->name,0,1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>#{{ $client->username }} • {{ $client->name }}</strong>
                                            <div class="muted-small">{{ $client->contact }} • {{ Str::limit($client->address, 50) }}</div>
                                        </div>
                                        <div class="text-end muted-small">ID: {{ $client->id }}</div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Selected Client Chip --}}
            @if ($selectedClient)
                @php $client = \App\Models\Client::find($selectedClient); @endphp
                @if($client)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="chip">
                            <div class="avatar-circle" style="background:#198754; width:36px; height:36px; font-size:.9rem;">
                                {{ strtoupper(substr($client->name,0,1)) }}
                            </div>
                            <div class="d-flex flex-column">
                                <strong>{{ $client->name }}</strong>
                                <small class="muted-small">📞 {{ $client->contact }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="$set('selectedClient', null)">Change</button>
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="$set('search','')">Clear</button>
                    </div>
                @endif
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">অভিযোগের ধরন</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0">📋</span>
                        <select wire:model="complain_type_id" class="form-select">
                            <option value="">নির্বাচন করুন</option>
                            @foreach ($complain_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('complain_type_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Priority</label>
                    <div class="d-flex gap-2 align-items-center">
                        <select wire:model="priority" class="form-select w-auto">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        <div>
                            @if($priority === 'high')
                                <span class="badge bg-danger">High</span>
                            @elseif($priority === 'medium')
                                <span class="badge bg-warning text-dark">Medium</span>
                            @else
                                <span class="badge bg-secondary">Low</span>
                            @endif
                        </div>
                    </div>
                    @error('priority') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-floating mb-3">
                <textarea wire:model="description" class="form-control" placeholder="Ticket details..." id="ticketDescription" style="height:110px; border-radius:10px"></textarea>
                <label for="ticketDescription">বিস্তারিত</label>
            </div>
            @error('description') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

            <div class="d-flex gap-2">
                <button wire:click="submitTicket" wire:loading.attr="disabled" class="btn btn-primary d-flex align-items-center">
                    <span class="me-2">➕</span>
                    <span wire:loading.remove>টিকেট তৈরি করুন</span>
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-outline-secondary" wire:click="$refresh">বাতিল</button>
                <div class="ms-auto muted-small align-self-center">Tip: select client to auto-fill</div>
            </div>
        </div>
    </div>

    {{-- Active Tickets List (modern cards) --}}
    <div class="card card-modern">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
            <div class="fw-bold">Active Tickets</div>
            <small class="muted-small">{{ $tickets->count() }} active</small>
        </div>

        <div class="card-body">
            @forelse ($tickets as $ticket)
                <div class="d-flex gap-3 mb-3 p-3 rounded" style="background:#f8fafc; align-items:flex-start;">
                    <div>
                        <div class="avatar-circle" style="background:#0d6efd; width:48px; height:48px;">
                            {{ strtoupper(substr($ticket->client->name,0,1)) }}
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex gap-2 align-items-center">
                                    <strong>#{{ $ticket->id }} • {{ $ticket->client->name }}</strong>
                                    <span class="badge px-1 py-0 shadow-sm @if($ticket->priority=='high') bg-danger @elseif($ticket->priority=='medium') bg-warning text-dark @else bg-secondary @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                    <span class="badge px-1 py-0 bg-secondary text-dark">ID: {{ $ticket->client->username }}</span>
                                </div>
                                <div class="muted-small mt-1">{{ Str::limit($ticket->description, 160) }}</div>
                            </div>
                            <div class="text-end muted-small">
                                <div>📞 {{ $ticket->client->contact }}</div>
                                <div class="mt-2">স্ট্যাটাস: <strong>{{ ucfirst($ticket->status) }}</strong></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            {{-- <select wire:model="technician_map.{{ $ticket->id }}" class="form-select form-select-sm w-auto">
                                <option value="">Assign Tech</option>
                                @foreach ($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <button wire:click="assignTechnician({{ $ticket->id }})" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                                Assign
                            </button>

                            <select wire:change="updateStatus({{ $ticket->id }}, $event.target.value)" class="form-select form-select-sm w-auto">
                                <option value="">Change Status</option>
                                <option value="in_progress">In Progress</option>
                                <option value="closed">Closed</option>
                            </select> --}}

                            <div class="d-flex gap-2 mt-3 flex-wrap align-items-start">
                                <select wire:model="technician_map.{{ $ticket->id }}" class="form-select form-select-sm w-auto">
                                    <option value="">Assign Tech</option>
                                    @foreach ($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                                <button wire:click="assignTechnician({{ $ticket->id }})" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                                    Assign
                                </button>

                                <select wire:change="updateStatus({{ $ticket->id }}, $event.target.value)" class="form-select form-select-sm w-auto">
                                    <option value="">Change Status</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="closed">Closed</option>
                                </select>

                                <div class="d-flex align-items-start gap-2 flex-shrink-0">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#timeline-{{ $ticket->id }}" aria-expanded="false" aria-controls="timeline-{{ $ticket->id }}">
                                        Details
                                    </button>

                                    <button wire:click="quickComment({{ $ticket->id }})" class="btn btn-sm btn-outline-secondary">Comment</button>
                                </div>
                            </div>

                            {{-- Collapsible timeline moved outside the inline controls so controls don't expand --}}
                            <div class="w-100 mt-2">
                                <div class="collapse" id="timeline-{{ $ticket->id }}">
                                    @php
                                        $timeline = \App\Models\TicketTimeline::where('ticket_id', $ticket->id)->latest()->get();
                                    @endphp

                                    <div class="card mt-1 p-2 shadow-sm" style="max-width:100%; max-height:260px; overflow:auto; border-radius:10px;">
                                        @if($timeline->isEmpty())
                                            <div class="muted-small small px-2 py-3">No timeline entries.</div>
                                        @else
                                            <ul class="list-group list-group-flush">
                                                @foreach($timeline as $item)
                                                    <li class="list-group-item small py-2">
                                                        <div class="d-flex">
                                                            <div class="me-2" style="min-width:44px;">
                                                                <div class="avatar-circle" style="width:36px; height:36px; font-size:.85rem; background:#6c757d;">
                                                                    {{ strtoupper(substr(($item->performed_by ?? 'S'), 0, 1)) }}
                                                                </div>
                                                            </div>

                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between">
                                                                    <div>
                                                                        <div class="fw-semibold">{{ ucfirst($item->action) }}</div>
                                                                        <div class="muted-small">by {{ $item->performed_by ?? 'System' }}</div>
                                                                    </div>
                                                                    <div class="text-end muted-small" style="min-width:90px;">
                                                                        <div title="{{ $item->created_at }}">{{ $item->created_at->format('d M Y, H:i') }}</div>
                                                                        <div class="text-muted small">{{ $item->created_at->diffForHumans() }}</div>
                                                                    </div>
                                                                </div>

                                                                @if($item->note)
                                                                    <div class="mt-1">{{ \Illuminate\Support\Str::limit($item->note, 220) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center muted-small">কোনো টিকেট নেই।</p>
            @endforelse
        </div>
    </div>
</div>
