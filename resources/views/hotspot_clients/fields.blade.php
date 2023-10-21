<!-- Name Field -->
<div class="form-group col-sm-3">
    {!! Form::label('name', __('models/hotspotClients.fields.name').'*:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Field -->
<div class="form-group col-sm-3">
    {!! Form::label('contact', __('models/hotspotClients.fields.contact').'*:') !!}
    {!! Form::text('contact', null, ['class' => 'form-control']) !!}
</div>

<!-- Cable Field -->
<div class="form-group col-sm-2">
    {!! Form::label('cable (m)', __('models/hotspotClients.fields.cable').':') !!}
    {!! Form::number('cable', null, ['class' => 'form-control']) !!}
</div>
<!-- Cable owner Field -->
<div class="form-group col-sm-2">
    {!! Form::label('cable_owner', __('models/hotspotClients.fields.cable_owner').':') !!}
    {!! Form::select('cable_owner', [ 'Company' => 'Company','Client' => 'Client',], null, ['class' => 'form-control']) !!}
</div>
<!-- Onu Mac Field -->
<div class="form-group col-sm-2">
    {!! Form::label('onu_mac*', __('models/hotspotClients.fields.onu_mac').'*:') !!}
    {!! Form::text('onu_mac', null, ['class' => 'form-control']) !!}
</div>
<!-- Onu Owner Field -->
<div class="form-group col-sm-2">
    {!! Form::label('onu_owner', __('models/hotspotClients.fields.onu_owner').':') !!}
    {!! Form::select('onu_owner', ['Client' => 'Client', 'Company' => 'Company'], null, ['class' => 'form-control']) !!}
</div>
<!-- Adrress Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adrress', __('models/hotspotClients.fields.adrress').':') !!}
    {!! Form::text('adrress', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('hotspotClients.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
