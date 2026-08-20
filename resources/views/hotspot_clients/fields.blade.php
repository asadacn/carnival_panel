<!-- Name Field -->
<div class="form-group col-md-6 col-lg-4 mb-3">
    {!! Form::label('name', 'Client Name <span class="text-danger">*</span>', ['class' => 'form-label fw-bold'], false) !!}
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
        {!! Form::text('name', null, ['class' => 'form-control border-start-0 ps-0', 'placeholder' => 'Enter client name', 'required']) !!}
    </div>
</div>

<!-- Contact Field -->
<div class="form-group col-md-6 col-lg-4 mb-3">
    {!! Form::label('contact', 'Contact Number <span class="text-danger">*</span>', ['class' => 'form-label fw-bold'], false) !!}
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone-alt text-muted"></i></span>
        {!! Form::text('contact', null, ['class' => 'form-control border-start-0 ps-0', 'placeholder' => 'Enter contact number', 'required']) !!}
    </div>
</div>

<!-- Address Field -->
<div class="form-group col-md-12 col-lg-4 mb-3">
    {!! Form::label('adrress', 'Address', ['class' => 'form-label fw-bold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
        {!! Form::text('adrress', null, ['class' => 'form-control border-start-0 ps-0', 'placeholder' => 'Enter full address']) !!}
    </div>
</div>

<div class="col-12"><hr class="my-3 text-muted"></div>

<div class="col-12 mb-2">
    <h6 class="text-secondary fw-bold"><i class="fas fa-network-wired me-2"></i> Connection Details</h6>
</div>

<!-- Onu Mac Field -->
<div class="form-group col-md-6 col-lg-3 mb-3">
    {!! Form::label('onu_mac', 'ONU MAC Address <span class="text-danger">*</span>', ['class' => 'form-label fw-bold'], false) !!}
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-ethernet text-muted"></i></span>
        {!! Form::text('onu_mac', null, ['class' => 'form-control border-start-0 ps-0', 'placeholder' => 'XX:XX:XX:XX:XX:XX', 'required']) !!}
    </div>
</div>

<!-- Onu Owner Field -->
<div class="form-group col-md-6 col-lg-3 mb-3">
    {!! Form::label('onu_owner', 'ONU Owner', ['class' => 'form-label fw-bold']) !!}
    {!! Form::select('onu_owner', ['Client' => 'Client', 'Company' => 'Company'], null, ['class' => 'form-select']) !!}
</div>

<!-- Cable Field -->
<div class="form-group col-md-6 col-lg-3 mb-3">
    {!! Form::label('cable', 'Cable Length (m)', ['class' => 'form-label fw-bold']) !!}
    <div class="input-group">
        {!! Form::number('cable', null, ['class' => 'form-control border-end-0', 'placeholder' => '0']) !!}
        <span class="input-group-text bg-light text-muted">meters</span>
    </div>
</div>

<!-- Cable owner Field -->
<div class="form-group col-md-6 col-lg-3 mb-3">
    {!! Form::label('cable_owner', 'Cable Owner', ['class' => 'form-label fw-bold']) !!}
    {!! Form::select('cable_owner', ['Company' => 'Company', 'Client' => 'Client'], null, ['class' => 'form-select']) !!}
</div>

<div class="col-12"><hr class="my-3 text-muted"></div>

<div class="col-12 mb-2">
    <h6 class="text-secondary fw-bold"><i class="fas fa-calendar-alt me-2"></i> Package & Expiration Settings</h6>
</div>

<!-- Expiry Date Field -->
<div class="form-group col-md-6 col-lg-4 mb-3">
    <label for="expires_at" class="form-label fw-bold">
        Expiry Date
        @if(isset($hotspotClient) && $hotspotClient->expires_at)
            @if($hotspotClient->isExpired())
                <span class="badge bg-danger ms-1">Expired</span>
            @else
                <span class="badge bg-success ms-1">Active</span>
            @endif
        @endif
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-check text-muted"></i></span>
        <input type="date" name="expires_at" id="expires_at" class="form-control border-start-0 ps-0"
               value="{{ isset($hotspotClient) && $hotspotClient->expires_at ? $hotspotClient->expires_at->format('Y-m-d') : old('expires_at') }}">
    </div>
    <div class="mt-2 d-flex flex-wrap gap-1">
        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setExpiryDays(30)">+30 Days</button>
        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setExpiryDays(15)">+15 Days</button>
        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setExpiryDays(7)">+7 Days</button>
        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="setExpiryToday()">Today</button>
        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="clearExpiry()">Clear</button>
    </div>
</div>

<!-- Status Field -->
<div class="form-group col-md-6 col-lg-4 mb-3">
    {!! Form::label('status', 'Client Status', ['class' => 'form-label fw-bold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-toggle-on text-muted"></i></span>
        {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], isset($hotspotClient) ? $hotspotClient->status : 'active', ['class' => 'form-select border-start-0 ps-0', 'id' => 'status']) !!}
    </div>
</div>

<!-- Package Days Field -->
<div class="form-group col-md-6 col-lg-4 mb-3">
    {!! Form::label('package_days', 'Package Validity (Days)', ['class' => 'form-label fw-bold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-clock text-muted"></i></span>
        {!! Form::number('package_days', isset($hotspotClient) ? $hotspotClient->package_days : null, ['class' => 'form-control border-start-0 ps-0', 'placeholder' => 'e.g. 30', 'id' => 'package_days', 'min' => '1']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 mt-4 text-end">
    <a href="{{ route('hotspotClients.index') }}" class="btn btn-light px-4 me-2 shadow-sm rounded-pill fw-bold">Cancel</a>
    {!! Form::button('<i class="fas fa-save me-1"></i> Save Client', ['type' => 'submit', 'class' => 'btn btn-primary px-5 shadow-sm rounded-pill fw-bold']) !!}
</div>

<script>
    function formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function setExpiryDays(days) {
        const target = new Date();
        target.setDate(target.getDate() + days);
        document.getElementById('expires_at').value = formatDate(target);
        const pkgDaysInput = document.getElementById('package_days');
        if (pkgDaysInput) {
            pkgDaysInput.value = days;
        }
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.value = 'active';
        }
    }

    function setExpiryToday() {
        document.getElementById('expires_at').value = formatDate(new Date());
    }

    function clearExpiry() {
        document.getElementById('expires_at').value = '';
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.value = 'inactive';
        }
    }
</script>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .input-group-text {
        color: #adb5bd;
    }
    .form-control::placeholder {
        color: #ced4da;
    }
    .form-control {
        border-radius: 0 0.375rem 0.375rem 0;
    }
    .input-group > .form-control:not(:first-child) {
        border-left: 0;
    }
    .input-group > .form-control:not(:last-child) {
        border-right: 0;
        border-radius: 0.375rem 0 0 0.375rem;
    }
</style>
