@extends('layouts.app')
@section('title', $hotspotClient->name . ' Details')

@section('content')
<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-dark">Hotspot Client Details</h1>
        <div class="section-header-breadcrumb m-0">
            <a href="{{ route('hotspotClients.index') }}" class="btn btn-light shadow-sm rounded-pill px-4 border font-weight-bold">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>
    
    <div class="content">
        @include('stisla-templates::common.errors')
        <div class="section-body">
            @include('hotspot_clients.show_fields')
        </div>
    </div>
</section>

<!-- Quick Edit Expiry Date Modal for Show View -->
<div class="modal fade" id="showExpiryModal" tabindex="-1" role="dialog" aria-labelledby="showExpiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fs-5 fw-bold" id="showExpiryModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i> Edit Expiry Date
                </h5>
                <button type="button" class="btn-close btn-close-white close text-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('hotspotClients.update', $hotspotClient->id) }}">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Client Name</label>
                        <input type="text" class="form-control bg-light fw-bold" value="{{ $hotspotClient->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="showModalExpiresAt" class="form-label fw-bold">Expiry Date</label>
                        <input type="date" name="expires_at" id="showModalExpiresAt" class="form-control shadow-sm"
                               value="{{ $hotspotClient->expires_at ? $hotspotClient->expires_at->format('Y-m-d') : '' }}">
                        <div class="mt-2 d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setShowModalExpiryDays(30)">+30 Days</button>
                            <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setShowModalExpiryDays(15)">+15 Days</button>
                            <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setShowModalExpiryDays(7)">+7 Days</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setShowModalExpiryToday()">Today</button>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="clearShowModalExpiry()">Clear</button>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="showModalStatus" class="form-label fw-bold">Status</label>
                        <select name="status" id="showModalStatus" class="form-select shadow-sm">
                            <option value="active" {{ $hotspotClient->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $hotspotClient->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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

<style>
    .section-header {
        box-shadow: 0 4px 8px rgba(0,0,0,0.02);
        border-radius: 10px;
        padding: 20px 30px;
        background: #fff;
    }
    .btn-light {
        background-color: #fff;
        color: #6c757d;
    }
    .btn-light:hover {
        background-color: #f8f9fa;
        color: #495057;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
        transition: all 0.3s ease;
    }
</style>
@endsection

@push('scripts')
<script>
    function openShowExpiryModal(expiresAt, status) {
        if (expiresAt) {
            document.getElementById('showModalExpiresAt').value = expiresAt;
        }
        if (status) {
            document.getElementById('showModalStatus').value = status;
        }

        var modalEl = document.getElementById('showExpiryModal');
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

    function formatShowModalDate(date) {
        var y = date.getFullYear();
        var m = String(date.getMonth() + 1).padStart(2, '0');
        var d = String(date.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    function setShowModalExpiryDays(days) {
        var target = new Date();
        target.setDate(target.getDate() + days);
        document.getElementById('showModalExpiresAt').value = formatShowModalDate(target);
        document.getElementById('showModalStatus').value = 'active';
    }

    function setShowModalExpiryToday() {
        document.getElementById('showModalExpiresAt').value = formatShowModalDate(new Date());
    }

    function clearShowModalExpiry() {
        document.getElementById('showModalExpiresAt').value = '';
        document.getElementById('showModalStatus').value = 'inactive';
    }
</script>
@endpush
