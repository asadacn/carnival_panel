<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', __('models/sMSTEMPALTES.fields.title').':') !!}
    <p>{{ $sMSTEMPALTE->title }}</p>
</div>

<!-- Sms Template Field -->
<div class="form-group">
    {!! Form::label('sms_template', __('models/sMSTEMPALTES.fields.sms_template').':') !!}
    <p>{{ $sMSTEMPALTE->sms_template }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/sMSTEMPALTES.fields.created_at').':') !!}
    <p>{{ $sMSTEMPALTE->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/sMSTEMPALTES.fields.updated_at').':') !!}
    <p>{{ $sMSTEMPALTE->updated_at }}</p>
</div>

