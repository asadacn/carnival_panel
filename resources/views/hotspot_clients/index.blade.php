@extends('layouts.app')
@section('title')
    Hotspot Clients
@endsection
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Hotspot Clients</h1>
            <div class="section-header-breadcrumb m-0">
                <a href="{{ route('hotspotClients.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-plus me-2"></i> Add New Client
                </a>
            </div>
        </div>

        <div class="section-body">
            @include('hotspot_clients.table')
        </div>
    </section>

    <!-- Quick Edit Expiry Date Modal -->
    <div class="modal fade" id="quickExpiryModal" tabindex="-1" role="dialog" aria-labelledby="quickExpiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fs-5 fw-bold" id="quickExpiryModalLabel">
                        <i class="fas fa-calendar-alt me-2"></i> Edit Expiry Date
                    </h5>
                    <button type="button" class="btn-close btn-close-white close text-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="quickExpiryForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Client Name</label>
                            <input type="text" id="modalClientName" class="form-control bg-light fw-bold" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalExpiresAt" class="form-label fw-bold">Expiry Date</label>
                            <input type="date" name="expires_at" id="modalExpiresAt" class="form-control shadow-sm">
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setModalExpiryDays(30)">+30 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setModalExpiryDays(15)">+15 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setModalExpiryDays(7)">+7 Days</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setModalExpiryToday()">Today</button>
                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="clearModalExpiry()">Clear</button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="modalStatus" class="form-label fw-bold">Status</label>
                            <select name="status" id="modalStatus" class="form-select shadow-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 px-4 py-3">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-save me-1"></i> Update Expiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Custom Styles for Premium Look --}}
    <style>
        .section-header {
            box-shadow: 0 4px 8px rgba(0,0,0,0.03);
            border-radius: 10px;
            padding: 20px 30px;
            background: #fff;
            margin-bottom: 30px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important;
        }
    </style>
@endsection

@push('scripts')
<script>
    function openExpiryModal(clientId, clientName, expiresAt, status) {
        var nameInput = document.getElementById('modalClientName');
        var expiresInput = document.getElementById('modalExpiresAt');
        var statusSelect = document.getElementById('modalStatus');
        var form = document.getElementById('quickExpiryForm');

        if (nameInput) nameInput.value = clientName || '';
        if (expiresInput) expiresInput.value = expiresAt || '';
        if (statusSelect) statusSelect.value = status || 'active';
        if (form) form.action = "{{ url('hotspotClients') }}/" + clientId;

        var modalEl = document.getElementById('quickExpiryModal');
        if (!modalEl) return;

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                var bsModal = bootstrap.Modal.getOrCreateInstance ? bootstrap.Modal.getOrCreateInstance(modalEl) : new bootstrap.Modal(modalEl);
                bsModal.show();
                return;
            } catch (err) {
                console.warn('Bootstrap 5 modal show failed, falling back to jQuery:', err);
            }
        }

        if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modalEl).modal('show');
        }
    }

    function formatModalDate(date) {
        var y = date.getFullYear();
        var m = String(date.getMonth() + 1).padStart(2, '0');
        var d = String(date.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    function setModalExpiryDays(days) {
        var target = new Date();
        target.setDate(target.getDate() + days);
        document.getElementById('modalExpiresAt').value = formatModalDate(target);
        document.getElementById('modalStatus').value = 'active';
    }

    function setModalExpiryToday() {
        document.getElementById('modalExpiresAt').value = formatModalDate(new Date());
    }

    function clearModalExpiry() {
        document.getElementById('modalExpiresAt').value = '';
        document.getElementById('modalStatus').value = 'inactive';
    }
</script>
@endpush
