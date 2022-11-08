<!-- Seller Field -->
<div class="form-group">
    {!! Form::label('seller', __('models/cardSellers.fields.seller').':') !!}
    <p>{{ $cardSeller->seller }}</p>
</div>

<!-- Contact Field -->
<div class="form-group">
    {!! Form::label('contact', __('models/cardSellers.fields.contact').':') !!}
    <p>{{ $cardSeller->contact }}</p>
</div>

<!-- Store Title Field -->
<div class="form-group">
    {!! Form::label('store_title', __('models/cardSellers.fields.store_title').':') !!}
    <p>{{ $cardSeller->store_title }}</p>
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

