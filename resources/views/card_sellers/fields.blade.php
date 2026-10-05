<!-- Seller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', __('models/cardSellers.fields.seller').':', ['class' => 'fw-semibold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-user text-primary"></i></span>
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('models/cardSellers.fields.seller'), 'required']) !!}
    </div>
</div>

<!-- Contact Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact', __('models/cardSellers.fields.contact').':', ['class' => 'fw-semibold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-phone-alt text-primary"></i></span>
        {!! Form::text('contact', null, ['class' => 'form-control', 'placeholder' => __('models/cardSellers.fields.contact'), 'required']) !!}
    </div>
</div>

<!-- Store Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('store_title', __('models/cardSellers.fields.store_title').':', ['class' => 'fw-semibold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-store text-primary"></i></span>
        {!! Form::text('store_title', null, ['class' => 'form-control', 'placeholder' => __('models/cardSellers.fields.store_title')]) !!}
    </div>
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', __('models/cardSellers.fields.address').':', ['class' => 'fw-semibold']) !!}
    <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-primary"></i></span>
        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => __('models/cardSellers.fields.address')]) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 mt-3">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary px-4']) !!}
    <a href="{{ route('cardSellers.index') }}" class="btn btn-light px-4">@lang('crud.cancel')</a>
</div>
