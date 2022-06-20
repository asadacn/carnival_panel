<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', __('models/cardSellers.fields.name').':') !!}
    <p>{{ $cardSeller->name }}</p>
</div>

<!-- Contact Field -->
<div class="form-group">
    {!! Form::label('contact', __('models/cardSellers.fields.contact').':') !!}
    <p>{{ $cardSeller->contact }}</p>
</div>

<!-- Shopname Field -->
<div class="form-group">
    {!! Form::label('shopName', __('models/cardSellers.fields.shopName').':') !!}
    <p>{{ $cardSeller->shopName }}</p>
</div>

<!-- Village Field -->
<div class="form-group">
    {!! Form::label('village', __('models/cardSellers.fields.village').':') !!}
    <p>{{ $cardSeller->village }}</p>
</div>

<!-- Union Field -->
<div class="form-group">
    {!! Form::label('union', __('models/cardSellers.fields.union').':') !!}
    <p>{{ $cardSeller->union }}</p>
</div>

<!-- Address Field -->
<div class="form-group">
    {!! Form::label('address', __('models/cardSellers.fields.address').':') !!}
    <p>{{ $cardSeller->address }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/cardSellers.fields.created_at').':') !!}
    <p>{{ $cardSeller->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/cardSellers.fields.updated_at').':') !!}
    <p>{{ $cardSeller->updated_at }}</p>
</div>

