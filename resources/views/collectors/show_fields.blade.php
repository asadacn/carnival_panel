<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', __('models/collectors.fields.name').':') !!}
    <p>{{ $collector->name }}</p>
</div>

<!-- Fathers Name Field -->
<div class="form-group">
    {!! Form::label('fathers_name', __('models/collectors.fields.fathers_name').':') !!}
    <p>{{ $collector->fathers_name }}</p>
</div>

<!-- Contact Field -->
<div class="form-group">
    {!! Form::label('contact', __('models/collectors.fields.contact').':') !!}
    <p>{{ $collector->contact }}</p>
</div>

<!-- Address Field -->
<div class="form-group">
    {!! Form::label('address', __('models/collectors.fields.address').':') !!}
    <p>{{ $collector->address }}</p>
</div>

<!-- Nid Field -->
<div class="form-group">
    {!! Form::label('nid', __('models/collectors.fields.nid').':') !!}
    <p>{{ $collector->nid }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/collectors.fields.created_at').':') !!}
    <p>{{ $collector->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/collectors.fields.updated_at').':') !!}
    <p>{{ $collector->updated_at }}</p>
</div>

