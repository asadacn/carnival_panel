<!-- Title Field -->
<div class="form-group col-sm-12">
    {!! Form::label('title', __('models/sMSTEMPALTES.fields.title').':') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('models/sMSTEMPALTES.labels.title_placeholder')]) !!}
</div>

<!-- Sms Template Field -->
<div class="form-group col-sm-12">
    {!! Form::label('sms_template', __('models/sMSTEMPALTES.fields.sms_template').':') !!}
    {!! Form::textarea('sms_template', null, ['class' => 'form-control', 'style' => 'min-height:150px', 'id' => 'sms-body', 'placeholder' => __('models/sMSTEMPALTES.labels.template_placeholder')]) !!}
    <div id="sms-counter" class="sms-counter-inline">
        <span class="encoding"></span>
        <span class="length"></span>
        <span class="messages"><strong>0</strong> @lang('models/sMSTEMPALTES.labels.parts')</span>
        <span class="remaining"><strong>0</strong> @lang('models/sMSTEMPALTES.labels.remaining')</span>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i> @lang('crud.save')
    </button>
    <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-light">
        <i class="fas fa-times me-1"></i> @lang('crud.cancel')
    </a>
</div>
