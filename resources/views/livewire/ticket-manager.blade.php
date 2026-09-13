<div class="container py-4 ticket-ui-wrap">
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
        /* End Stats Card Styles */


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

        .avatar-circle.status-open, .avatar-circle.status-pending {
            background: var(--danger-color) !important;
        }
        .avatar-circle.status-assigned {
            background: var(--warning-color) !important;
        }
        .avatar-circle.status-closed {
            background: var(--success-color) !important;
        }

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
            font-size: 0.875rem;
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
            background: linear-gradient(180deg, #ffffff 0%, #fffefb 100%);
            border: 1px solid #dceaf7;
            transition: all 0.2s ease;
            border-radius: 1.2rem;
            box-shadow: 0 10px 26px rgba(31, 41, 55, 0.06);
            position: relative;
            overflow: visible;
            z-index: 1;
        }
        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0.65rem;
            bottom: 0.65rem;
            left: 0.45rem;
            width: 4px;
            border-radius: 999px;
            background: linear-gradient(180deg, #7dd3fc, #34d399);
            opacity: 0.95;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.7);
        }
        .ticket-card.status-open::before {
            background: linear-gradient(180deg, #dc2626, #f97316);
        }
        .ticket-card.status-pending::before {
            background: linear-gradient(180deg, #eab308, #fbbf24);
        }
        .ticket-card.status-assigned::before,
        .ticket-card.status-in_progress::before {
            background: linear-gradient(180deg, #8b5cf6, #6366f1);
        }
        .ticket-card.status-closed::before {
            background: linear-gradient(180deg, #10b981, #34d399);
        }
        .ticket-card:hover {
            border-color: #99d4ff;
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.12);
            transform: translateY(-2px);
            z-index: 20;
        }
        .ticket-card .ticket-info-panel {
            border-right: 1px solid #dcebe7;
            padding-right: 1rem;
        }
        .ticket-card .ticket-action-panel {
            background: linear-gradient(180deg, #f9fdff 0%, #eefaf7 100%);
            border: 1px solid #bce4da;
            border-radius: 1rem;
            padding: 1.25rem;
            min-height: 100%;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.9);
            min-width: 230px;
            overflow: visible;
        }
        .ticket-card .ticket-action-panel .dropdown-menu {
            border-radius: 0.8rem;
            box-shadow: 0 12px 30px rgba(31, 41, 55, 0.12);
            min-width: 210px;
            margin-top: 0.5rem;
            transition: opacity 250ms ease, transform 250ms ease;
            transform: translateY(-4px);
            opacity: 0;
            z-index: 2000;
            position: absolute;
        }
        .ticket-card .ticket-action-panel .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
        }
        .ticket-card .ticket-action-panel .dropdown {
            position: relative;
            z-index: 1060;
        }
        .ticket-card .ticket-card-client {
            font-size: 1rem;
            font-weight: 800;
            color: #244064;
            letter-spacing: 0.01em;
        }
        .ticket-card .ticket-card-meta {
            font-size: 0.8rem;
            color: var(--muted-color);
            letter-spacing: 0.02em;
        }
        .ticket-card .ticket-card-description {
            color: #526278;
            font-size: 0.88rem;
            border-left: 2px dashed #9bd8b7;
            padding-left: 0.75rem;
            line-height: 1.6;
            background: #f8fcf9;
            border-radius: 0.55rem;
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
        }
        .ticket-card .ticket-card-actions {
            min-width: 170px;
        }
        .ticket-card .ticket-card-action-btn {
            border-radius: 999px;
            padding: 0.45rem 1rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .ticket-card .assign-control-wrap {
            padding: 0.65rem 0.75rem;
            background: rgba(255,255,255,0.78);
            border: 1px solid #cfe8e1;
            border-radius: 0.85rem;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.95);
        }
        .ticket-card .assign-dropdown-wrap {
            min-width: 160px;
        }
        .ticket-card .assign-select {
            border-radius: 999px 0 0 999px !important;
            border-color: #bdd6cb;
            color: #344054;
            background: #fff;
            font-size: 0.78rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }
        .ticket-card .assign-button {
            border-radius: 0 999px 999px 0 !important;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.45rem 0.85rem;
            background: linear-gradient(135deg, #0d6efd, #1e88e5);
            border: none;
        }
        .ticket-card .ticket-card-id {
            min-width: 88px;
            width: 88px;
            background: #eaf8f6;
            border-radius: 1rem;
            border: 1px solid #bce9dd;
            color: #075e55;
            font-weight: 800;
            padding: 0.8rem 0.7rem;
            text-align: center;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.9);
        }
        .ticket-card .ticket-card-id .ticket-id {
            display: block;
            font-size: 1rem;
            line-height: 1.2;
            color: #0f766e;
        }
        .ticket-card .ticket-card-footer {
            border-top: 1px solid #eef7ee;
            background: #fdfdfc;
            padding-top: 0.8rem;
            margin-top: 0.8rem;
        }
        .ticket-list-panel {
            border: 1px solid #dceaf7;
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 14px 36px rgba(15, 23, 42, 0.06);
        }
        .timeline-item {
            border-left: 2px solid #e0e0e0;
            padding-left: 1rem;
            margin-left: 0.5rem;
        }
        .timeline-item:last-child {
            border-left: none;
        }

        /* Form Floating Fix */
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-control-plaintext:not(:placeholder-shown) ~ label,
        .form-floating > .form-select ~ label {
            transform: scale(.85) translateY(-1.05rem) translateX(.15rem);
        }
        .form-floating .form-control {
            min-height: calc(3.5rem + 2px);
            height: auto;
        }
        .form-floating .form-control#ticketDescription {
             padding-top: 2rem;
             min-height: 110px;
        }

        /* Ticket UI Redesign */
        .ticket-ui-wrap {
            max-width: 1440px;
            margin: 0 auto;
        }

        .ticket-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.2rem;
            margin-bottom: 1rem;
            border-radius: 16px;
            background: linear-gradient(135deg, #eef4ff, #ffffff);
            border: 1px solid rgba(99, 102, 241, 0.08);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
        }

        .ticket-page-title {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .ticket-page-title-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 8px 16px rgba(13, 110, 253, 0.20);
        }

        .ticket-page-title h4 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.2rem;
        }

        .ticket-page-title .muted-small {
            font-size: 0.79rem;
        }

        .ticket-topbar .btn {
            border-radius: 10px;
            font-weight: 700;
            padding: 0.62rem 1rem;
        }

        .ticket-kpi-grid {
            margin-bottom: 1.25rem;
        }

        .ticket-kpi-card {
            min-height: 116px;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s ease;
        }

        .ticket-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .ticket-kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .ticket-kpi-card .ticket-kpi-label {
            font-size: 0.78rem;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .ticket-kpi-card .ticket-kpi-value {
            font-size: 1.9rem;
            font-weight: 800;
            color: #1e293b;
            margin-top: 0.2rem;
            line-height: 1.2;
        }

        .ticket-create-wrap {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .ticket-create-wrap .card-body {
            padding: 1.3rem;
        }

        .ticket-create-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding-bottom: 0.9rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            margin-bottom: 1rem;
        }

        .ticket-create-head h5 {
            font-size: 1.1rem;
            font-weight: 900;
            color: #1d4ed8;
            margin: 0;
        }

        .ticket-create-head small {
            color: #64748b;
            font-size: 0.78rem;
        }

        .ticket-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .ticket-form-grid .form-floating,
        .ticket-form-grid .full {
            grid-column: span 1;
        }

        @media (max-width: 991px) {
            .ticket-form-grid {
                grid-template-columns: 1fr;
            }
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

    <div class="ticket-topbar">
        <div class="ticket-page-title">
            <span class="ticket-page-title-icon"><i class="bi bi-ticket-perforated-fill"></i></span>
            <div>
                <h4>Ticket Management</h4>
                <div class="muted-small">Operations center</div>
            </div>
        </div>
        <a href="{{ route('tickets.analytics') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-chart-pie me-1"></i> Analytics
        </a>
    </div>

    {{-- TICKET STATUS DASHBOARD COUNTERS --}}
    <div class="row ticket-kpi-grid g-3">
        <div class="col-lg-4 col-md-6">
            <div class="ticket-kpi-card">
                <span class="ticket-kpi-icon bg-soft-warning text-warning"><i class="bi bi-clock-fill"></i></span>
                <div>
                    <div class="ticket-kpi-label text-warning">Pending Tickets</div>
                    <div class="ticket-kpi-value">{{ $ticketCounts['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="ticket-kpi-card">
                <span class="ticket-kpi-icon bg-soft-primary text-primary"><i class="bi bi-tools"></i></span>
                <div>
                    <div class="ticket-kpi-label text-primary">In Progress</div>
                    <div class="ticket-kpi-value">{{ $ticketCounts['progress'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="ticket-kpi-card">
                <span class="ticket-kpi-icon bg-soft-success text-success"><i class="bi bi-check-circle-fill"></i></span>
                <div>
                    <div class="ticket-kpi-label text-success">Closed Tickets</div>
                    <div class="ticket-kpi-value">{{ $ticketCounts['closed'] }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- END TICKET STATUS DASHBOARD COUNTERS --}}

    {{-- Ticket charts removed to keep the operations UI compact. --}}

    {{-- Create Ticket Card --}}
    <div class="card ticket-create-wrap mb-4">
        <div class="card-body p-4">
            <div class="ticket-create-head">
                <div>
                    <h5><i class="bi bi-plus-circle me-2"></i> Create New Ticket</h5>
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
            <div class="card bg-light border-0 p-3 mb-4" style="border-radius: 1rem;">
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
            <span class="muted-small">Total Tickets (Filtered)</span>
            <span class="mx-2 text-muted">|</span>
            <span class="muted-small">Page {{ $tickets->currentPage() }}</span>
        </div>
    </div>
    {{-- END IMPROVED HEADER --}}

    <div class="row g-3 mb-4 align-items-center">

        {{-- Status Filter (MODIFIED OPTIONS) --}}
        <div class="col-md-4">
            <label class="form-label fw-semibold small text-muted mb-1">Filter by Status</label>
            <select wire:model.live="filterStatus" class="form-select rounded-pill form-select-sm">
                <option value="all">-- All Tickets --</option>
                <option value="pending">Pending</option>
                <option value="progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        {{-- Ticket ID Search --}}
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

        {{-- Reset Button --}}
        <div class="col-md-4 d-flex align-items-end">
            <button wire:click="resetFilters" class="btn btn-sm btn-outline-secondary rounded-pill w-auto">
                <i class="bi bi-x-circle me-1"></i> Clear Filters
            </button>
        </div>

    </div>
    {{-- Active Tickets List (customer-friendly split structure) --}}
    <div class="card ticket-list-panel">

        <div class="card-body p-4 pt-2">
            @forelse ($tickets as $ticket)
                @php
                    $statusClass = 'status-open';
                    if ($ticket->status === 'pending') {
                        $statusClass = 'status-pending';
                    } elseif ($ticket->status === 'assigned' || $ticket->status === 'in_progress' || $ticket->status === 'in-progress') {
                        $statusClass = 'status-assigned';
                    } elseif ($ticket->status === 'closed') {
                        $statusClass = 'status-closed';
                    }
                @endphp
                <div class="ticket-card mb-4 p-4 {{ $statusClass }}">
                    <div class="row g-3 align-items-stretch">

                        <div class="col-lg-8">
                            <div class="ticket-info-panel h-100">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="ticket-card-id">
                                        <span class="ticket-id">#{{ $ticket->id }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="ticket-card-client">
                                                {{ $ticket->client->name }} <span class="muted-small">({{ $ticket->client->username }})</span>
                                            </div>
                                            <span class="badge badge-priority @if($ticket->priority=='high') bg-danger @elseif($ticket->priority=='medium') bg-warning text-dark @else bg-secondary @endif">
                                                {{ ucfirst($ticket->priority) }} Priority
                                            </span>
                                        </div>
                                        <div class="ticket-card-meta mt-1">
                                            <span><i class="bi bi-telephone"></i> {{ $ticket->client->contact }}</span>
                                            <span class="mx-2">•</span>
                                            <span title="Client Address"><i class="bi bi-geo-alt"></i> {{ Str::limit($ticket->client->address, 30) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <span class="chip bg-light-info text-primary border border-info-subtle">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        <span class="fw-bold">Complain Type:</span>
                                        <span>{{ $ticket->complainType->name ?? 'N/A' }}</span>
                                    </span>
                                </div>

                                <div class="ticket-card-description mb-3">
                                    {{ Str::limit($ticket->description, 160) }}
                                </div>

                                <div class="ticket-card-footer d-flex flex-wrap gap-3 align-items-center">
                                    <span class="chip bg-light-info text-primary border border-info-subtle">
                                        <i class="bi bi-person-fill"></i> Status: <strong class="text-dark">{{ ucfirst($ticket->status) }}</strong>
                                    </span>
                                    @if($ticket->technician)
                                        @php
                                            $priorityEtaHours = $ticket->priority === 'high' ? 1 : ($ticket->priority === 'medium' ? 1.5 : 2);
                                            $etaText = $ticket->priority === 'high' ? '1 hour' : ($ticket->priority === 'medium' ? '1.5 hours' : '2 hours');
                                        @endphp
                                        <span class="chip bg-light-warning text-warning border border-warning-subtle">
                                            👨‍🔧 Assigned: <strong>{{ $ticket->technician->name }}</strong>
                                        </span>
                                        <span class="chip bg-light-success text-success border border-success-subtle">
                                            ⏱ ETA: <strong>{{ $etaText }}</strong>
                                        </span>
                                    @endif
                                    <span class="muted-small ms-auto">Created: {{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="ticket-action-panel h-100 d-flex flex-column justify-content-between">
                                <div class="ticket-action-head">
                                    <div class="small fw-bold text-uppercase text-muted mb-2">Operations</div>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle ticket-card-action-btn w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-list-check me-2"></i>Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#timeline-{{ $ticket->id }}">
                                                    <i class="bi bi-clock-history me-2"></i>Timeline
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#comment-{{ $ticket->id }}">
                                                    <i class="bi bi-chat-dots me-2"></i>Add Comment
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button
                                                    onclick="confirm('Are you sure you want to CLOSE this ticket?') || event.stopImmediatePropagation()"
                                                    wire:click="updateStatus({{ $ticket->id }}, 'closed')"
                                                    class="dropdown-item text-success"
                                                    @if($ticket->status == 'closed') disabled @endif>
                                                    <i class="bi bi-check-circle-fill me-2"></i>Close Ticket
                                                </button>
                                            </li>
                                            <li>
                                                <button
                                                    onclick="confirm('Are you absolutely sure you want to PERMANENTLY DELETE ticket #{{ $ticket->id }}?') || event.stopImmediatePropagation()"
                                                    wire:click="deleteTicket({{ $ticket->id }})"
                                                    class="dropdown-item text-danger">
                                                    <i class="bi bi-trash-fill me-2"></i>Delete Ticket
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="assign-control-wrap">
                                        <div class="small fw-bold text-uppercase text-muted mb-1">Assign Technician</div>
                                        <div class="input-group input-group-sm assign-dropdown-wrap">
                                            <select wire:model="technician_map.{{ $ticket->id }}" class="form-select rounded-start-pill assign-select">
                                                <option value="">Select technician</option>
                                                @foreach ($technicians as $tech)
                                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                                @endforeach
                                            </select>
                                            <button
                                                onclick="confirm('Confirm assignment to the selected technician?') || event.stopImmediatePropagation()"
                                                wire:click="assignTechnician({{ $ticket->id }})"
                                                class="btn btn-primary rounded-end-pill flex-shrink-0 assign-button"
                                                wire:loading.attr="disabled">
                                                <i class="bi bi-person-check-fill me-1"></i>Assign
                                            </button>
                                        </div>
                                    </div>

                                    <div class="collapse w-100" id="comment-{{ $ticket->id }}" data-bs-parent=".ticket-card">
                                        <div class="input-group input-group-sm">
                                            <input type="text" wire:model.defer="technician_map.comment-{{ $ticket->id }}" class="form-control rounded-start-pill" placeholder="Add a quick note...">
                                            <button wire:click="quickComment({{ $ticket->id }})" class="btn btn-success rounded-end-pill" wire:loading.attr="disabled">Save</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="collapse mt-3" id="timeline-{{ $ticket->id }}" data-bs-parent=".ticket-card">
                                    <div class="card bg-light p-3 shadow-sm border-0" style="max-height:300px; overflow-y:auto; border-radius:0.75rem;">
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
                    </div>
                </div>
            @empty
                <p class="text-center p-5 muted-small">🎉 Great job! No tickets found matching the current filters.</p>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>

    {{-- Chart.js removed with the chart panel, leaving the ticket share popup logic intact. --}}
    <script>
    (function () {
        Livewire.on('ticket-created', function (id, clientName, clientId, contact, address, complainType, priority, description) {
                const d = { id, clientName, clientId, contact, address, complainType, priority, description };

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
    })();
    </script>

    <script>
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
