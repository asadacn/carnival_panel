<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Technician Manager</h5>
            <button wire:click="resetForm" class="btn btn-light btn-sm">+ New</button>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="name">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" wire:model="phone">
                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Telegram ID</label>
                        <input type="text" class="form-control" wire:model="telegram_id">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control" wire:model="whatsapp_number">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success">
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>
                    @if ($isEdit)
                        <button type="button" class="btn btn-secondary" wire:click="resetForm">Cancel</button>
                    @endif
                </div>
            </form>

            <hr>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Telegram</th>
                        <th>WhatsApp</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($technicians as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->phone }}</td>
                            <td>{{ $t->telegram_id }}</td>
                            <td>{{ $t->whatsapp_number }}</td>
                            <td>
                                <span class="badge bg-{{ $t->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button wire:click="edit({{ $t->id }})" class="btn btn-sm btn-warning">Edit</button>
                                <button wire:click="delete({{ $t->id }})" class="btn btn-sm btn-danger" onclick="confirm('Delete this?') || event.stopImmediatePropagation()">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
