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
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(22, 24, 26, 0.08);
        }

        /* Stats Card Styles */
        .stats-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }
        .stats-card:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
            transform: translateY(-2px);
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
        .icon-box.bg-soft-primary { background: #e9f0ff; color: var(--primary-color); }
        .icon-box.bg-soft-warning { background: #fff8e1; color: var(--warning-color); }
        .icon-box.bg-soft-success { background: #e8f5e9; color: var(--success-color); }

        /* Avatar and Status Colors */
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
            background: var(--primary-color);
        }
        .avatar-circle.status-open, .avatar-circle.status-pending { background: var(--danger-color) !important; }
        .avatar-circle.status-assigned { background: var(--warning-color) !important; }
        .avatar-circle.status-closed { background: var(--success-color) !important; }

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
        .muted-small { font-size: 0.875rem; color: var(--muted-color); }

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
        .timeline-item { border-left: 2px solid #e0e0e0; padding-left: 1rem; margin-left: 0.5rem; }
        .timeline-item:last-child { border-left: none; }

        /* Form Floating Fix */
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-control-plaintext:not(:placeholder-shown) ~ label,
        .form-floating > .form-select ~ label {
            transform: scale(.85) translateY(-1.05rem) translateX(.15rem);
        }
        .form-floating .form-control { min-height: calc(3.5rem + 2px); height: auto; }
        .form-floating .form-control#ticketDescription { padding-top: 2rem; min-height: 110px; }

        /* --- New: Create Ticket Modal + FAB polish --- */
        .fab-new-ticket {
            border-radius: 999px;
            padding: 0.65rem 1.4rem;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(13,110,253,0.35);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .fab-new-ticket:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(13,110,253,0.45); }

        #createTicketModal .modal-content {
            border: none;
            border-radius: 1.1rem;
            overflow: hidden;
        }
        #createTicketModal .modal-header {
            background: linear-gradient(135deg,#0d6efd,#3b82f6);
            color: #fff;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        #createTicketModal .modal-header .btn-close { filter: invert(1) brightness(2); }
        #createTicketModal .modal-body { padding: 1.5rem; max-height: 70vh; overflow-y: auto; }
        #createTicketModal .modal-footer { border: none; padding: 1rem 1.5rem 1.5rem; }
        #createTicketModal .steps-dot {
            width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,.45);
        }
        #createTicketModal .steps-dot.active { background: #fff; }

        .search-suggestions .list-group-item:hover { background: #f1f5f9; }
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
    <div class="d-flex gap-3 mb-4 align-items-center p-3 rounded flex-wrap">
        <h4 class="mb-0 fw-bold text-dark">Ticket Management Dashboard 🚀</h4>
        <a href="{{ route('tickets.analytics') }}" class="btn btn-outline-primary btn-sm rounded-pill ms-3 shadow-sm"><i class="fas fa-chart-pie me-1"></i> View Analytics 📊</a>

        {{-- New: Prominent "Create Ticket" trigger for the modal --}}
        <button type="button" class="btn btn-primary fab-new-ticket ms-2" data-bs-toggle="modal" data-bs-target="#createTicketModal">
            <i class="bi bi-plus-lg me-1"></i> নতুন টিকেট
        </button>
    </div>

    {{-- TICKET STATUS DASHBOARD COUNTERS --}}
    <div class="row mb-5 g-4">
        <div class="col-lg-4 col-md-6">
            <div class="card stats-card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-warning me-3"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <div class="muted-small fw-semibold text-warning">Pending Tickets</div>
                        <h3 class="mb-0 fw-bold text-dark">{{ $ticketCounts['pending'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card stats-card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-primary me-3"><i class="bi bi-tools"></i></div>
                    <div>
                        <div class="muted-small fw-semibold text-primary">In Progress (Assigned)</div>
                        <h3 class="mb-0 fw-bold text-dark">{{ $ticketCounts['progress'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card stats-card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-success me-3"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="muted-small fw-semibold text-success">Closed Tickets</div>
                        <h3 class="mb-0 fw-bold text-dark">{{ $ticketCounts['closed'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TICKET ANALYTICS CHARTS --}}
    <div class="row g-4 mb-5" id="ticketChartsSection">
        <div class="col-12">
            <div class="card card-modern p-4" style="background: linear-gradient(135deg,#0f172a,#1e293b); color:#fff;">
                <div class="d-flex align-items-center mb-3">
                    <span style="font-size:1.4rem; margin-right:.5rem;">📊</span>
                    <h6 class="mb-0 fw-bold" style="color:#e2e8f0; letter-spacing:.03em;">Tickets by Category</h6>
                    <span class="ms-auto badge rounded-pill" style="background:#3b82f6; font-size:.7rem;">Complain Types</span>
                </div>
                <div style="position:relative; height:260px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        window._ticketCategoryData = @json($complainTypeStats);
    </script>

    {{-- ============================================================ --}}
    {{--  CREATE TICKET MODAL (moved out of the page flow)             --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="createTicketModalLabel">➕ নতুন টিকেট তৈরি করুন</h5>
                        <small style="opacity:.85;">Please ensure client details are correct before submission.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Client Search & Selection --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-semibold small text-muted">ক্লায়েন্ট খুঁজুন (ID/Name/Contact)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-pill">🔍</span>
                                <input type="text"
                                       id="ticketClientSearch"
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
                                <ul class="list-group position-absolute w-100 mt-2 shadow-lg rounded-3 search-suggestions" style="z-index:2000; max-height:220px; overflow:auto; border: 1px solid #ddd;">
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
                                <div class="alert alert-light text-muted small py-2 my-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle"></i> No client selected. Please search above.
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

                    {{-- Description Field --}}
                    <div class="form-floating mb-3">
                        <textarea
                            wire:model="description"
                            class="form-control"
                            placeholder="Ticket details..."
                            id="ticketDescription"
                            style="height:110px; border-radius:1rem"
                            aria-label="Ticket details"></textarea>
                        <label for="ticketDescription">বিস্তারিত (Description - Optional)</label>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Form Notifications Settings --}}
                    <div class="card bg-light border-0 p-3 mb-2" style="border-radius: 1rem;">
                        <div class="d-flex gap-4 align-items-center flex-wrap">
                            <span class="fw-semibold small text-muted"><i class="bi bi-bell-fill me-1"></i> Send Notifications:</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.live="send_sms" id="smsSwitch" style="font-size:1.1rem; cursor:pointer;">
                                <label class="form-check-label small text-dark fw-medium" for="smsSwitch" style="cursor:pointer;">SMS Notification</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.live="send_telegram" id="tgSwitch" style="font-size:1.1rem; cursor:pointer;">
                                <label class="form-check-label small text-dark fw-medium" for="tgSwitch" style="cursor:pointer;">Telegram Notification</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            wire:click="reset(['search', 'selectedClient', 'complain_type_id', 'description', 'priority'])"
                            class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </button>
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button wire:click="submitTicket" wire:loading.attr="disabled" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <span wire:loading.remove wire:target="submitTicket">➕ Create Ticket</span>
                        <span wire:loading wire:target="submitTicket">
                            <span class="spinner-border spinner-border-sm me-1"></span> Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- END CREATE TICKET MODAL --}}

    {{-- IMPROVED ACTIVE TICKETS HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5 flex-wrap gap-2">
        <h4 class="mb-0 fw-bold text-dark">
            <i class="bi bi-list-check me-2 text-primary"></i> Active Ticket List
        </h4>
        <div class="chip bg-white border border-secondary-subtle py-2 px-3 shadow-sm">
            <span class="fw-bold text-dark">{{ $tickets->total() }}</span>
            <span class="muted-small">Total Tickets (Filtered)</span>
            <span class="mx-2 text-muted">|</span>
            <span class="muted-small">Page {{ $tickets->currentPage() }}</span>
        </div>
    </div>

    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-4">
            <label class="form-label fw-semibold small text-muted mb-1">Filter by Status</label>
            <select wire:model.live="filterStatus" class="form-select rounded-pill form-select-sm">
                <option value="all">-- All Tickets --</option>
                <option value="pending">Pending</option>
                <option value="progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold small text-muted mb-1">Search by Ticket ID</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 rounded-start-pill">#</span>
                <input type="number"
                       wire:model.live.debounce.300ms="filterTicketId"
                       class="form-control rounded-end-pill"
                       placeholder="Enter Ticket ID (e.g. 102)"
                       min="1">
            </div>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button wire:click="resetFilters" class="btn btn-sm btn-outline-secondary rounded-pill w-auto">
                <i class="bi bi-x-circle me-1"></i> Clear Filters
            </button>
        </div>
    </div>

    {{-- Active Tickets List (modern cards) --}}
    <div class="card card-modern">
        <div class="card-body p-4 pt-2">
            @forelse ($tickets as $ticket)
                <div class="ticket-card d-flex gap-3 mb-4 p-4 align-items-start">
                    <div class="flex-shrink-0 text-center">
                        <div class="avatar-circle status-{{ $ticket->status }}" style="width:50px; height:50px; font-size:1.1rem; margin-bottom: 0.5rem;">
                            #{{ $ticket->id }}
                        </div>
                        <div class="muted-small" title="Complain Type">{{ $ticket->complainType->name ?? 'N/A' }}</div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold">{{ $ticket->client->name }} ({{ $ticket->client->username }})</h6>
                            <span class="badge badge-priority @if($ticket->priority=='high') bg-danger @elseif($ticket->priority=='medium') bg-warning text-dark @else bg-secondary @endif">
                                {{ ucfirst($ticket->priority) }} Priority
                            </span>
                        </div>

                        <div class="muted-small mb-2">{{ Str::limit($ticket->description, 160) }}</div>

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
                                        <i class="bi bi-clock-history me-2"></i>Timeline
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

                <div class="row g-3 px-3 mb-4">
                    <div class="col-lg-3">
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

                    <div class="col-lg-6">
                         <div class="collapse w-100" id="comment-{{ $ticket->id }}" data-bs-parent=".ticket-card">
                            <div class="input-group input-group-sm">
                                <input type="text" wire:model.defer="technician_map.comment-{{ $ticket->id }}" class="form-control rounded-start-pill" placeholder="Add a quick note to the timeline...">
                                <button wire:click="quickComment({{ $ticket->id }})" class="btn btn-success rounded-end-pill" wire:loading.attr="disabled">Save Note</button>
                            </div>
                         </div>
                    </div>

                    <div class="col-12">
                         <div class="collapse" id="timeline-{{ $ticket->id }}" data-bs-parent=".ticket-card">
                            <div class="card bg-light p-3 mt-3 shadow-sm border-0" style="max-height:300px; overflow-y:auto; border-radius:0.75rem;">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Ticket Timeline 📜</h6>
                                @php
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
                <div class="text-center p-5">
                    <div style="font-size:2.5rem;">🎉</div>
                    <p class="muted-small mb-3">Great job! No tickets found matching the current filters.</p>
                    <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                        <i class="bi bi-plus-lg me-1"></i> Create a Ticket
                    </button>
                </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>

    {{-- Chart.js + Initialization --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const CAT_COLORS = [
            '#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444',
            '#06b6d4','#ec4899','#a3e635','#f97316','#14b8a6',
        ];

        let catChart = null;

        function renderCharts() {
            const catData   = window._ticketCategoryData || [];
            const catLabels = catData.map(d => d.label);
            const catCounts = catData.map(d => d.count);
            const catColors = catLabels.map((_, i) => CAT_COLORS[i % CAT_COLORS.length]);

            const catCanvas = document.getElementById('categoryChart');
            if (catCanvas) {
                if (catChart) catChart.destroy();
                catChart = new Chart(catCanvas, {
                    type: 'bar',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            label: 'Tickets',
                            data: catCounts,
                            backgroundColor: catColors,
                            borderColor: catColors.map(c => c + 'cc'),
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.parsed.x} ticket${ctx.parsed.x !== 1 ? 's' : ''}`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(255,255,255,0.06)' },
                                ticks: { color: '#94a3b8', font: { size: 11 } },
                                beginAtZero: true,
                                precision: 0,
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: '#cbd5e1', font: { size: 11 } },
                            }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', renderCharts);

        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', () => {
                setTimeout(renderCharts, 50);
            });
        });

        // --- New: Modal UX niceties ---
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('createTicketModal');
            if (!modalEl) return;

            // Auto-focus the client search field when the modal opens
            modalEl.addEventListener('shown.bs.modal', function () {
                const searchInput = document.getElementById('ticketClientSearch');
                if (searchInput) searchInput.focus();
            });

            // Auto-open modal if arriving from "Open Ticket" shortcut on clients page
            @if($autoOpenModal)
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(function () {
                        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();
                    }, 300);
                });
            @endif

            // After ticket created: close modal then show share popup (Livewire v2)
            document.addEventListener('livewire:load', function () {
                Livewire.on('ticket-created', function (id, clientName, clientId, contact, address, complainType, priority, description) {
                    const d = { id, clientName, clientId, contact, address, complainType, priority, description };

                    // 1. Close the create-ticket modal
                    const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
                    instance.hide();

                    // 2. Build share texts
                    window._ticketShareText = '🔔 *নতুন টিকেট তৈরি হয়েছে* 🔔\n' +
                        '-----------------------------\n' +
                        '🎫 *Ticket ID:* #' + d.id + '\n' +
                        '👤 *Client:* ' + d.clientName + ' (' + d.clientId + ')\n' +
                        '📞 *Contact:* ' + (d.contact || 'N/A') + '\n' +
                        '🏠 *Address:* ' + (d.address || 'N/A') + '\n' +
                        '⚙️ *Type:* ' + d.complainType + '\n' +
                        '🔥 *Priority:* ' + d.priority + '\n' +
                        '📝 *Description:* ' + (d.description || 'N/A') + '\n' +
                        '-----------------------------';

                    window._ticketShareTelegramText = '🔔 <b>নতুন টিকেট তৈরি হয়েছে</b> 🔔\n' +
                        '-----------------------------\n' +
                        '🎫 <b>Ticket ID:</b> #' + d.id + '\n' +
                        '👤 <b>Client:</b> ' + d.clientName + ' (' + d.clientId + ')\n' +
                        '📞 <b>Contact:</b> ' + (d.contact || 'N/A') + '\n' +
                        '🏠 <b>Address:</b> ' + (d.address || 'N/A') + '\n' +
                        '⚙️ <b>Type:</b> ' + d.complainType + '\n' +
                        '🔥 <b>Priority:</b> ' + d.priority + '\n' +
                        '📝 <b>Description:</b> ' + (d.description || 'N/A') + '\n' +
                        '-----------------------------';

                    // 3. Show Swal share modal
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Ticket Created!',
                        html: `
                            <p style="margin-bottom:12px;color:#6b7280;font-size:.9rem;">টিকেট সফলভাবে তৈরি হয়েছে। নিচের অপশন থেকে শেয়ার করুন।</p>
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 16px;text-align:left;margin-bottom:16px;">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;font-weight:600;color:#166534;font-size:.85rem;">
                                    <i class="bi bi-ticket-perforated-fill"></i> #${d.id} — ${d.clientName}
                                </div>
                                <div style="font-size:.82rem;color:#374151;line-height:1.8;">
                                    <div><i class="fas fa-phone-alt" style="width:16px;color:#6b7280;"></i> ${d.contact || 'N/A'}</div>
                                    <div><i class="fas fa-map-marker-alt" style="width:16px;color:#6b7280;"></i> ${d.address || 'N/A'}</div>
                                    <div><i class="fas fa-tag" style="width:16px;color:#6b7280;"></i> ${d.complainType} — ${d.priority} Priority</div>
                                </div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:8px;">
                                <button onclick="ticketCopyDetails()" class="btn btn-primary w-100" style="display:flex;align-items:center;justify-content:center;gap:8px;font-weight:600;padding:10px;border-radius:8px;background-color:#3b82f6;border-color:#3b82f6;color:white;">
                                    <i class="fas fa-copy"></i> Copy Ticket Details
                                </button>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                    <button onclick="ticketSendWhatsApp()" class="btn btn-success" style="display:flex;align-items:center;justify-content:center;gap:6px;font-weight:600;padding:10px;border-radius:8px;background-color:#25d366;border-color:#25d366;color:white;">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </button>
                                    <button id="ticket-tg-share-btn" onclick="ticketSendTelegram()" class="btn btn-info" style="display:flex;align-items:center;justify-content:center;gap:6px;font-weight:600;padding:10px;border-radius:8px;background-color:#0088cc;border-color:#0088cc;color:white;">
                                        <i class="fab fa-telegram-plane"></i> Telegram
                                    </button>
                                </div>
                            </div>
                        `,
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        cancelButtonColor: '#6b7280',
                    });
                });
            });
        });
    })();

    // --- Ticket Share Helpers ---
    window.ticketCopyDetails = function () {
        var text = window._ticketShareText || '';
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed'; ta.style.left = '-9999px'; ta.style.top = '-9999px';
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try {
            document.execCommand('copy');
            Swal.fire({ icon:'success', title:'Copied!', text:'Ticket details copied to clipboard.', timer:1500, showConfirmButton:false, toast:true, position:'top-end' });
        } catch(e) { console.error('Copy failed', e); }
        document.body.removeChild(ta);
    };

    window.ticketSendWhatsApp = function () {
        var waUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(window._ticketShareText || '');
        window.open(waUrl, '_blank');
    };

    window.ticketSendTelegram = function () {
        var shareText = window._ticketShareTelegramText || '';
        var $btn = document.getElementById('ticket-tg-share-btn');
        var origHtml = $btn ? $btn.innerHTML : '';
        if ($btn) { $btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...'; $btn.disabled = true; }

        fetch('{{ route("tickets.send-telegram") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ message: shareText })
        })
        .then(r => r.json())
        .then(resp => {
            if ($btn) { $btn.innerHTML = origHtml; $btn.disabled = false; }
            if (resp.success) {
                Swal.fire({ icon:'success', title:'Sent!', text:'Ticket details posted to Telegram Channel.', timer:2000, showConfirmButton:false, toast:true, position:'top-end' });
            } else {
                Swal.fire('Error', 'Failed to send to Telegram.', 'error');
            }
        })
        .catch(() => {
            if ($btn) { $btn.innerHTML = origHtml; $btn.disabled = false; }
            Swal.fire('Error', 'An error occurred while sending to Telegram.', 'error');
        });
    };
    </script>
</div>
