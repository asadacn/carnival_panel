<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', __('models/hotspotClients.fields.name').':') !!}
    <p>{{ $hotspotClient->name }}</p>
</div>

<!-- Contact Field -->
<div class="form-group">
    {!! Form::label('contact', __('models/hotspotClients.fields.contact').':') !!}
    <p>{{ $hotspotClient->contact }}</p>
</div>

<!-- Cable Field -->
<div class="form-group">
    {!! Form::label('cable', __('models/hotspotClients.fields.cable').':') !!}
    <p>{{ $hotspotClient->cable }}</p>
</div>

<!-- Cable Owner Field -->
<div class="form-group">
    {!! Form::label('cable_owner', __('models/hotspotClients.fields.cable_owner').':') !!}
    <p>{{ $hotspotClient->cable_owner }}</p>
</div>

<!-- Onu Mac Field -->
<div class="form-group">
    {!! Form::label('onu_mac', __('models/hotspotClients.fields.onu_mac').':') !!}
    <p>{{ $hotspotClient->onu_mac }}</p>
</div>

<!-- Onu Owner Field -->
<div class="form-group">
    {!! Form::label('onu_owner', __('models/hotspotClients.fields.onu_owner').':') !!}
    <p>{{ $hotspotClient->onu_owner }}</p>
</div>

<!-- Adrress Field -->
<div class="form-group">
    {!! Form::label('adrress', __('models/hotspotClients.fields.adrress').':') !!}
    <p>{{ $hotspotClient->adrress }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/hotspotClients.fields.created_at').':') !!}
    <p>{{ $hotspotClient->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/hotspotClients.fields.updated_at').':') !!}
    <p>{{ $hotspotClient->updated_at }}</p>
</div>

