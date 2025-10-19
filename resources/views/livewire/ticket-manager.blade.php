<div class="container py-5">
    <style>
        /* Base Modern Styles */
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --muted-color: #6c757d;
        }

        .card-modern {
            border: none;
            border-radius: 1rem; /* Softer rounded corners */
            box-shadow: 0 8px 24px rgba(22, 24, 26, 0.08); /* Stronger, softer shadow */
        }

        /* NEW: Stats Card Styles */
        .stats-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }
        .stats-card:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .icon-box.bg-soft-primary {
            background: #e9f0ff;
            color: var(--primary-color);
        }
        .icon-box.bg-soft-warning {
            background: #fff8e1;
            color: var(--warning-color);
        }
        .icon-box.bg-soft-success {
            background: #e8f5e9;
            color: var(--success-color);
        }
        /* END NEW: Stats Card Styles */


        /* Search/Selection Enhancements */
        .search-suggestions li:hover {
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* subtle lift */
        }
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            flex-shrink: 0;
            /* Default background is set for new tickets if no status class is applied */
            background: var(--primary-color);
        }

        /* --- TICKET STATUS COLORS (FIXED) --- */
        .avatar-circle.status-open {
            background: var(--danger-color) !important; /* লাল: নতুন বা হাই প্রায়োরিটি */
        }
        .avatar-circle.status-assigned {
            background: var(--warning-color) !important; /* হলুদ/কমলা: টেকনিশিয়ানের কাছে আছে */
        }
        .avatar-circle.status-pending {
            background: var(--muted-color) !important; /* ধূসর: অপেক্ষমাণ */
        }
        .avatar-circle.status-closed {
            background: var(--success-color) !important; /* সবুজ: সম্পন্ন */
        }
        /* --- END STATUS COLORS --- */

        .chip {
            border-radius: 999px;
            padding: 4px 12px;
            background: #eef2f6;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            font-size: 0.9rem;
            color: var(--muted-color);
            font-weight: 500;
        }
        .muted-small {
            font-size: 0.875rem; /* Slightly larger for better readability */
            color: var(--muted-color);
        }

        /* Status & Priority Badges */
        .badge-priority {
            padding: 0.4em 0.7em;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 0.5rem;
            line-height: 1;
        }
        .ticket-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .ticket-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .timeline-item {
            border-left: 2px solid #e0e0e0;
            padding-left: 1rem;
            margin-left: 0.5rem;
        }
        .timeline-item:last-child {
            border-left: none;
        }

        /* Form Floating Fix - IMPROVED FOR TEXTAREA */
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-control-plaintext:not(:placeholder-shown) ~ label,
        .form-floating > .form-select ~ label {
            /* Adjusted Y position to ensure label is fully above text content */
            transform: scale(.85) translateY(-1.05rem) translateX(.15rem);
        }
        /* Adjusted overall padding for general form inputs in floating state */
        .form-floating .form-control {
            min-height: calc(3.5rem + 2px);
            height: auto;
            /* Default: padding: 1rem 0.75rem 0.5rem 0.75rem; */
        }
        /* KEY FIX: Specific padding adjustment for the large textarea */
        .form-floating .form-control#ticketDescription {
             /* Increased top padding to ensure the first line of text is not obscured by the floating label */
             padding-top: 2rem;
             min-height: 110px;
        }

    </style>

    {{-- Alerts for success/error messages --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show card-modern shadow-none" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show card-modern shadow-none" role="alert">
            <i class="bi bi-x-octagon-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Top Controls / Dashboard Header --}}
    <div class="d-flex gap-3 mb-4 align-items-center p-3 rounded">
        <h4 class="mb-0 fw-bold text-dark">Ticket Management Dashboard 🚀</h4>

        <div class="ms-auto d-flex gap-3 align-items-center">
             <span class="muted-small">Notifications:</span>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" wire:model.live="send_sms" id="smsSwitch" style="font-size:1.2rem;">
                <label class="form-check-label muted-small" for="smsSwitch" title="Toggle SMS Notifications">SMS</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" wire:model.live="send_telegram" id="tgSwitch" style="font-size:1.2rem;">
                <label class="form-check-label muted-small" for="tgSwitch" title="Toggle Telegram Notifications">Telegram</label>
            </div>
        </div>
    </div>

    {{-- NEW: TICKET STATUS DASHBOARD COUNTERS --}}
    <div class="row mb-5 g-4">

        {{-- Pending Count Card --}}
        <div class="col-lg-4 col-md-6">
            <div class="card stats-card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-warning me-3">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <div class="muted-small fw-semibold text-warning">Pending Tickets</div>
                        {{-- Assuming $ticketCounts['pending'] exists in your Livewire component --}}
                        <h3 class="mb-0 fw-bold text-dark">{{ $ticketCounts['pending'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- In Progress/Assigned Count Card --}}
        <div class="col-lg-4 col-md-6">
            <div class="card stats-card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-primary me-3">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <div class="muted-small fw-semibold text-primary">In Progress (Assigned)</div>
                        {{-- Assuming $ticketCounts['progress'] or ['assigned'] exists --}}
                        <h3 class="mb-0 fw-bold text-dark">{{ $ticketCounts['progress'] ?? $ticketCounts['assigned'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Closed Count Card --}}
        <div class="col-lg-4 col-md-12">
            <div class="card stats-card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-success me-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="muted-small fw-semibold text-success">Closed Tickets</div>
                        {{-- Assuming $ticketCounts['closed'] exists --}}
                        <h3 class="mb-0 fw-bold text-dark">{{ $ticketCounts['closed'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END TICKET STATUS DASHBOARD COUNTERS --}}

    {{-- Create Ticket Card --}}
    <div class="card card-modern mb-5">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-3">
                <div>
                    <h5 class="mb-0 fw-bold text-primary">➕ নতুন টিকেট তৈরি করুন</h5>
                    <small class="muted-small">Please ensure client details are correct before submission.</small>
                </div>
                <button wire:click="reset(['search', 'selectedClient', 'complain_type_id', 'description', 'priority'])" class="btn btn-sm btn-outline-secondary" title="Reset form">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </button>
            </div>

            {{-- Client Search & Selection --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6 position-relative">
                    <label class="form-label fw-semibold small text-muted">ক্লায়েন্ট খুঁজুন (ID/Name/Contact)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill">🔍</span>
                        <input type="text"
                               wire:model.debounce.400ms="search"
                               wire:keydown="$set('selectedClient', null)"
                               class="form-control rounded-end-pill"
                               placeholder="Search client..."
                               aria-autocomplete="list">
                        @if($search)
                            <button type="button" wire:click="$set('search','')" class="btn btn-light border-0 px-3">✖</button>
                        @endif
                    </div>

                    @if (!empty($clients) && $search != '' && !$selectedClient)
                        <ul class="list-group position-absolute w-100 mt-2 shadow-lg rounded-3 search-suggestions" style="z-index:1000; max-height:260px; overflow:auto; border: 1px solid #ddd;">
                            @foreach ($clients as $client)
                                <li class="list-group-item list-group-item-action d-flex gap-3 align-items-center py-2"
                                    wire:click="$set('selectedClient', {{ $client->id }}); $set('search', '{{ $client->name }}')" style="cursor:pointer;">
                                    <div class="avatar-circle" style="background:#5cb85c; width:36px; height:36px; font-size:.9rem;">
                                        {{ strtoupper(substr($client->name,0,1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong class="text-dark">{{ $client->name }}</strong>
                                        <div class="muted-small">{{ $client->contact }} • ID: {{ $client->username }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Selected Client Display (Right side or below search) --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">নির্বাচিত ক্লায়েন্ট</label>
                    @if ($selectedClient)
                        @php $client = \App\Models\Client::find($selectedClient); @endphp
                        @if($client)
                            <div class="chip bg-white border d-flex justify-content-between w-100 p-2 shadow-sm">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="background:var(--success-color); width:32px; height:32px; font-size:.8rem;">
                                        {{ strtoupper(substr($client->name,0,1)) }}
                                    </div>
                                    <div class="d-flex flex-column text-start">
                                        <strong class="text-dark">{{ $client->name }} (#{{ $client->username }})</strong>
                                        <small class="muted-small">🏠 {{ Str::limit($client->address, 35) }}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger border-0" wire:click="$set('selectedClient', null)" title="Remove Selection">✕</button>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light text-muted small py-2 my-0">
                            No client selected. Please search above.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Form Fields --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">অভিযোগের ধরন (Complain Type)</label>
                    <select wire:model="complain_type_id" class="form-select rounded-pill">
                        <option value="">-- নির্বাচন করুন --</option>
                        @foreach ($complain_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('complain_type_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Priority Level</label>
                    <div class="d-flex gap-3 align-items-center">
                        <select wire:model="priority" class="form-select rounded-pill w-auto">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        <div>
                            @if($priority === 'high')
                                <span class="badge badge-priority bg-danger">🔥 High</span>
                            @elseif($priority === 'medium')
                                <span class="badge badge-priority bg-warning text-dark">⚠️ Medium</span>
                            @else
                                <span class="badge badge-priority bg-secondary">Low</span>
                            @endif
                        </div>
                    </div>
                    @error('priority') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Description Field (FIXED) --}}
            <div class="form-floating mb-4">
                <textarea
                    wire:model="description"
                    class="form-control"
                    placeholder="Ticket details..."
                    id="ticketDescription"
                    style="height:110px; border-radius:1rem"
                    aria-label="Ticket details"></textarea>
                <label for="ticketDescription">বিস্তারিত (Description)</label>
                @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <button wire:click="submitTicket" wire:loading.attr="disabled" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                <span wire:loading.remove>➕ Create Ticket</span>
                <span wire:loading>Creating...</span>
            </button>
        </div>
    </div>



    {{-- IMPROVED ACTIVE TICKETS HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h4 class="mb-0 fw-bold text-dark">
            <i class="bi bi-list-check me-2 text-primary"></i> Active Ticket List
        </h4>
        <div class="chip bg-white border border-secondary-subtle py-2 px-3 shadow-sm">
            <span class="fw-bold text-dark">{{ $tickets->total() }}</span>
            <span class="muted-small">Total Tickets</span>
            <span class="mx-2 text-muted">|</span>
            <span class="muted-small">Page {{ $tickets->currentPage() }}</span>
        </div>
    </div>
    {{-- END IMPROVED HEADER --}}

    {{-- Active Tickets List (modern cards) --}}
    <div class="card card-modern">

        <div class="card-body p-4 pt-2">
            @forelse ($tickets as $ticket)
                <div class="ticket-card d-flex gap-3 mb-4 p-4 align-items-start">

                    {{-- Left Side: Ticket ID & Client Info (FIXED: Dynamically assigned status class) --}}
                    <div class="flex-shrink-0 text-center">
                        <div
                            class="avatar-circle status-{{ $ticket->status }}"
                            style="width:50px; height:50px; font-size:1.1rem; margin-bottom: 0.5rem;">
                            #{{ $ticket->id }}
                        </div>
                        <div class="muted-small" title="Complain Type">{{ $ticket->complainType->name ?? 'N/A' }}</div>
                    </div>

                    {{-- Middle: Description, Status, Technician --}}
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold">{{ $ticket->client->name }} ({{ $ticket->client->username }})</h6>
                            <span class="badge badge-priority @if($ticket->priority=='high') bg-danger @elseif($ticket->priority=='medium') bg-warning text-dark @else bg-secondary @endif">
                                {{ ucfirst($ticket->priority) }} Priority
                            </span>
                        </div>

                        <div class="muted-small mb-2">
                            {{ Str::limit($ticket->description, 160) }}
                        </div>

                        <div class="d-flex flex-wrap gap-3 align-items-center">
                             <span class="chip bg-light-info text-primary border border-info-subtle">
                                 <i class="bi bi-person-fill"></i> Status: <strong class="text-dark">{{ ucfirst($ticket->status) }}</strong>
                            </span>
                            @if($ticket->technician)
                                 <span class="chip bg-light-warning text-warning border border-warning-subtle">
                                     👨‍🔧 Assigned: <strong>{{ $ticket->technician->name }}</strong>
                                 </span>
                            @endif
                            <span class="muted-small ms-auto">Created: {{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Right Side: Contact & Primary Actions --}}
                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-2">
                        <small class="fw-bold text-dark">📞 {{ $ticket->client->contact }}</small>
                        <small class="muted-small" title="Client Address">🏠 {{ Str::limit($ticket->client->address, 30) }}</small>

                        <div class="btn-group mt-2">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#timeline-{{ $ticket->id }}">
                                        <i class="bi bi-clock-history me-2"></i> View Details/Timeline
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#comment-{{ $ticket->id }}">
                                        <i class="bi bi-chat-dots me-2"></i> Add Comment
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button
                                        onclick="confirm('Are you sure you want to CLOSE this ticket?') || event.stopImmediatePropagation()"
                                        wire:click="updateStatus({{ $ticket->id }}, 'closed')"
                                        class="dropdown-item text-success"
                                        @if($ticket->status == 'closed') disabled @endif>
                                        <i class="bi bi-check-circle-fill me-2"></i> Close Ticket
                                    </button>
                                </li>
                                <li>
                                     <button
                                         onclick="confirm('Are you absolutely sure you want to PERMANENTLY DELETE ticket #{{ $ticket->id }}?') || event.stopImmediatePropagation()"
                                         wire:click="deleteTicket({{ $ticket->id }})"
                                         class="dropdown-item text-danger">
                                         <i class="bi bi-trash-fill me-2"></i> Delete Ticket
                                     </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Collapsible Footer (Actions, Comment, Timeline) --}}
                <div class="row g-3 px-3 mb-4">
                    {{-- Technician Assignment --}}
                    <div class="col-lg-6">
                        <div class="input-group input-group-sm">
                            <select wire:model="technician_map.{{ $ticket->id }}" class="form-select rounded-start-pill">
                                <option value="">-- Assign Technician --</option>
                                @foreach ($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <button
                                onclick="confirm('Confirm assignment to the selected technician?') || event.stopImmediatePropagation()"
                                wire:click="assignTechnician({{ $ticket->id }})"
                                class="btn btn-primary rounded-end-pill flex-shrink-0"
                                wire:loading.attr="disabled">
                                Assign
                            </button>
                        </div>
                    </div>

                    {{-- Quick Comment --}}
                    <div class="col-lg-6">
                         <div class="collapse w-100" id="comment-{{ $ticket->id }}" data-bs-parent=".ticket-card">
                            <div class="input-group input-group-sm">
                                <input type="text" wire:model.defer="technician_map.comment-{{ $ticket->id }}" class="form-control rounded-start-pill" placeholder="Add a quick note to the timeline...">
                                <button wire:click="quickComment({{ $ticket->id }})" class="btn btn-success rounded-end-pill" wire:loading.attr="disabled">Save Note</button>
                            </div>
                         </div>
                    </div>

                    {{-- Collapsible Timeline --}}
                    <div class="col-12">
                         <div class="collapse" id="timeline-{{ $ticket->id }}" data-bs-parent=".ticket-card">
                            <div class="card bg-light p-3 mt-3 shadow-sm border-0" style="max-height:300px; overflow-y:auto; border-radius:0.75rem;">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Ticket Timeline 📜</h6>
                                @php
                                     // NOTE: You must ensure TicketTimeline model exists and has the correct relationships/data
                                    $timeline = \App\Models\TicketTimeline::where('ticket_id', $ticket->id)->latest()->get();
                                @endphp

                                @if($timeline->isEmpty())
                                    <div class="muted-small text-center py-2">No timeline entries yet.</div>
                                @else
                                    <ul class="list-unstyled mb-0">
                                        @foreach($timeline as $item)
                                            <li class="d-flex mb-3 timeline-item">
                                                <div class="me-3 flex-shrink-0">
                                                    <div class="avatar-circle" style="width:30px; height:30px; font-size:.7rem; background:#6c757d;">
                                                        {{ strtoupper(substr(($item->performed_by ?? 'S'), 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="fw-semibold text-dark">{{ ucfirst($item->action) }}</div>
                                                        <small class="text-muted text-nowrap" title="{{ $item->created_at }}">{{ $item->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <div class="small">{{ \Illuminate\Support\Str::limit($item->note, 220) }}</div>
                                                    <small class="muted-small">by {{ $item->performed_by ?? 'System' }}</small>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                         </div>
                    </div>
                </div>
            @empty
                <p class="text-center p-5 muted-small">🎉 Great job! No open tickets found matching your criteria.</p>
            @endforelse

            {{-- Pagination Links --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
            {{-- END Pagination Links --}}
        </div>
    </div>
</div>
