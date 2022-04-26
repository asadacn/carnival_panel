<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', __('models/sMSTEMPALTES.fields.title').':') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Sms Template Field -->
<div  class="form-group col-sm-12 col-lg-10">
    {!! Form::label('sms_template', __('models/sMSTEMPALTES.fields.sms_template').':') !!}
    <label for="">  ( <small id="sms-counter">
        {{-- <li>Encoding: <span class="encoding"></span></li> --}}
        {{-- <li>Length: <span class="length"></span></li> --}}
        <span>Messages: <span class="messages"></span></span> /
        {{-- <li>Per Message: <span class="per_message"></span></li> --}}
        <span>Remaining: <span class="remaining"></span></span>
    </small> )
</label>
    {!! Form::textarea('sms_template', null, ['class' => 'form-control' , 'style'=>'min-height:135px','id'=>'sms-body']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
