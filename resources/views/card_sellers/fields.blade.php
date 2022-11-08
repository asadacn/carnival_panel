<!-- Seller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('seller', __('models/cardSellers.fields.seller').':') !!}
    {!! Form::text('seller', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact', __('models/cardSellers.fields.contact').':') !!}
    {!! Form::text('contact', null, ['class' => 'form-control']) !!}
</div>

<!-- Store Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('store_title', __('models/cardSellers.fields.store_title').':') !!}
    {!! Form::text('store_title', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', __('models/cardSellers.fields.address').':') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('cardSellers.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
