<!-- Contact Field -->
<div class="form-group row border-bottom py-3 mb-0">
    <div class="col-sm-4 fw-semibold"><i class="fas fa-phone-alt text-primary me-2"></i>@lang('models/cardSellers.fields.contact')</div>
    <div class="col-sm-8">
        <a href="tel:{{ $cardSeller->contact }}" class="text-decoration-none fw-semibold">{{ $cardSeller->contact ?: '-' }}</a>
    </div>
</div>

<!-- Store Title Field -->
<div class="form-group row border-bottom py-3 mb-0">
    <div class="col-sm-4 fw-semibold"><i class="fas fa-store text-primary me-2"></i>@lang('models/cardSellers.fields.store_title')</div>
    <div class="col-sm-8">{{ $cardSeller->store_title ?: '-' }}</div>
</div>

<!-- Address Field -->
<div class="form-group row border-bottom py-3 mb-0">
    <div class="col-sm-4 fw-semibold"><i class="fas fa-map-marker-alt text-primary me-2"></i>@lang('models/cardSellers.fields.address')</div>
    <div class="col-sm-8">{{ $cardSeller->address ?: '-' }}</div>
</div>

<!-- Created At Field -->
<div class="form-group row border-bottom py-3 mb-0">
    <div class="col-sm-4 fw-semibold"><i class="fas fa-calendar text-primary me-2"></i>@lang('models/cardSellers.fields.created_at')</div>
    <div class="col-sm-8">{{ \Carbon\Carbon::parse($cardSeller->created_at)->setTimezone('Asia/Dhaka')->format('d-M-Y h:i A') }}</div>
</div>
