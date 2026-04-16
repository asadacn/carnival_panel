<!-- Type Field -->
<div class="col-sm-12 col-md-4 mb-4">
    <div class="p-3 bg-white rounded shadow-sm border border-light h-100">
        <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">@lang('models/investments.fields.type')</label>
        <p class="mb-0 fw-semibold text-dark fs-6">{{ $investment->type ?: 'General' }}</p>
    </div>
</div>

<!-- Amount Field -->
<div class="col-sm-12 col-md-4 mb-4">
    <div class="p-3 bg-white rounded shadow-sm border border-light h-100">
        <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">@lang('models/investments.fields.amount')</label>
        <p class="mb-0 fw-bold fs-5" style="color: #059669;">৳ {{ number_format($investment->amount, 0) }}</p>
    </div>
</div>

<!-- Date Field -->
<div class="col-sm-12 col-md-4 mb-4">
    <div class="p-3 bg-white rounded shadow-sm border border-light h-100">
        <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">@lang('models/investments.fields.created_at')</label>
        <p class="mb-0 fw-semibold text-dark fs-6">{{ $investment->created_at->format('d M, Y') }}</p>
    </div>
</div>

<!-- Invested By Field -->
<div class="col-sm-12 col-md-6 mb-4">
    <div class="p-3 bg-white rounded shadow-sm border border-light h-100 d-flex align-items-center">
        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center text-primary me-3 shadow-sm" style="width: 48px; height: 48px;">
            <i class="far fa-user fs-5"></i>
        </div>
        <div>
            <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">@lang('models/investments.fields.invested_by')</label>
            <p class="mb-0 fw-bold text-dark fs-5">{{ $investment->invested_by ?: 'Anonymous' }}</p>
        </div>
    </div>
</div>

<!-- Purpose Field -->
<div class="col-sm-12 col-md-6 mb-4">
    <div class="p-3 bg-white rounded shadow-sm border border-light h-100">
        <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">@lang('models/investments.fields.purpose')</label>
        <p class="mb-0 fw-semibold text-dark fs-6">{{ $investment->purpose ?: 'N/A' }}</p>
    </div>
</div>

<!-- Description Field -->
<div class="col-sm-12 mb-4">
    <div class="p-3 bg-white rounded shadow-sm border border-light h-100">
        <label class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">@lang('models/investments.fields.description')</label>
        <p class="mb-0 text-dark">{{ $investment->description ?: 'No additional notes provided.' }}</p>
    </div>
</div>
