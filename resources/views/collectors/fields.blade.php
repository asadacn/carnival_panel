<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', __('models/collectors.fields.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Fathers Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fathers_name', __('models/collectors.fields.fathers_name').':') !!}
    {!! Form::text('fathers_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact', __('models/collectors.fields.contact').':') !!}
    {!! Form::text('contact', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('address', __('models/collectors.fields.address').':') !!}
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Nid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nid', __('models/collectors.fields.nid').':') !!}
    {!! Form::text('nid', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('collectors.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
