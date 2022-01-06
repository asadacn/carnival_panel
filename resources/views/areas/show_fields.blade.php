<!-- Code Field -->
<div class="form-group">
    {!! Form::label('code', __('models/areas.fields.code').':') !!}
    <p>{{ $area->code }}</p>
</div>

<!-- Area Field -->
<div class="form-group">
    {!! Form::label('area', __('models/areas.fields.area').':') !!}
    <p>{{ $area->area }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', __('models/areas.fields.description').':') !!}
    <p>{{ $area->description }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/areas.fields.created_at').':') !!}
    <p>{{ $area->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/areas.fields.updated_at').':') !!}
    <p>{{ $area->updated_at }}</p>
</div>

