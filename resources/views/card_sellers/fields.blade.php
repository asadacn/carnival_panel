<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', __('models/cardSellers.fields.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact', __('models/cardSellers.fields.contact').':') !!}
    {!! Form::text('contact', null, ['class' => 'form-control']) !!}
</div>

<!-- Shopname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shopName', __('models/cardSellers.fields.shopName').':') !!}
    {!! Form::text('shopName', null, ['class' => 'form-control']) !!}
</div>

<!-- Village Field -->
<div class="form-group col-sm-6">
    {!! Form::label('village', __('models/cardSellers.fields.village').':') !!}
    {!! Form::text('village', null, ['class' => 'form-control']) !!}
</div>

<!-- Union Field -->
<div class="form-group col-sm-6">
    {!! Form::label('union', __('models/cardSellers.fields.union').':') !!}
    {!! Form::text('union', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('address', __('models/cardSellers.fields.address').':') !!}
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('cardSellers.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
