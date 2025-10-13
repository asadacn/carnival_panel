<div class="container py-4">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Message Control --}}
    <div class="card mb-4">
        <div class="card-body d-flex gap-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model="send_sms" id="smsCheck">
                <label class="form-check-label" for="smsCheck">📱 SMS পাঠানো সক্রিয়</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model="send_telegram" id="telegramCheck">
                <label class="form-check-label" for="telegramCheck">🤖 Telegram পাঠানো সক্রিয়</label>
            </div>
        </div>
    </div>

    {{-- Create Ticket --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white fw-bold">নতুন টিকেট তৈরি</div>
        <div class="card-body">
            {{-- Client Search --}}
            <div class="mb-3 position-relative">
                <input type="text" wire:model.debounce.300ms="search" class="form-control"
                       placeholder="ক্লায়েন্টের নাম, আইডি বা ফোন লিখুন ...">

                @if (!empty($clients) && $search != '')
                    <ul class="list-group position-absolute w-100" style="z-index:1000; max-height:200px; overflow-y:auto;">
                        @foreach ($clients as $client)
                            <li class="list-group-item list-group-item-action"
                                wire:click="$set('selectedClient', {{ $client->id }})"
                                style="cursor:pointer;">
                                {{ $client->name }} - {{ $client->contact }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Selected Client --}}
            @if ($selectedClient)
                @php $client = \App\Models\Client::find($selectedClient); @endphp
                @if($client)
                    <div class="alert alert-success">
                        নির্বাচিত ক্লায়েন্ট: <strong>{{ $client->name }}</strong> ({{ $client->contact }})
                    </div>
                @endif
            @endif

            {{-- Complain Type & Priority --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">অভিযোগের ধরন</label>
                    <select wire:model="complain_type_id" class="form-select">
                        <option value="">নির্বাচন করুন</option>
                        @foreach ($complain_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Priority</label>
                    <select wire:model="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="form-label">বিস্তারিত</label>
                <textarea wire:model="description" class="form-control" rows="3"></textarea>
            </div>

            {{-- Submit Button --}}
            <button wire:click="submitTicket" class="btn btn-primary">
                ➕ টিকেট তৈরি করুন
            </button>
        </div>
    </div>

    {{-- Active Tickets --}}
    <div class="card">
        <div class="card-header bg-success text-white fw-bold">Active Tickets</div>
        <div class="card-body">
            @forelse ($tickets as $ticket)
                <div class="border-bottom pb-3 mb-3">
                    <h6>#{{ $ticket->id }} - {{ $ticket->client->name }}
                        <span class="badge bg-secondary">{{ ucfirst($ticket->priority) }}</span>
                    </h6>
                    <p class="mb-1">{{ $ticket->description }}</p>
                    <small class="text-muted">📞 {{ $ticket->client->contact }} |
                        স্ট্যাটাস: {{ ucfirst($ticket->status) }}</small>

                    <div class="d-flex gap-2 mt-2">
                        {{-- Assign Technician --}}
                        <select wire:model="technician_id" class="form-select form-select-sm w-auto">
                            <option value="">Select Technician</option>
                            @foreach ($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        </select>
                        <button wire:click="assignTechnician({{ $ticket->id }})"
                                class="btn btn-sm btn-success">Assign</button>

                        {{-- Update Status --}}
                        <select wire:change="updateStatus({{ $ticket->id }}, $event.target.value)"
                                class="form-select form-select-sm w-auto">
                            <option value="">Change Status</option>
                            <option value="in_progress">In Progress</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">কোনো টিকেট নেই।</p>
            @endforelse
        </div>
    </div>
</div>
