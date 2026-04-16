<!-- Type Field -->
<div class="form-group col-md-6 col-lg-4 mb-4">
    {!! Form::label('type', __('models/investments.fields.type').'', ['class' => 'form-label fw-bold text-dark']) !!}
    {!! Form::text('type', null, ['class' => 'form-control border-0 shadow-sm', 'placeholder' => 'e.g., Marketing, Equipment', 'style' => 'border-radius: 8px;']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-md-6 col-lg-4 mb-4">
    {!! Form::label('amount', __('models/investments.fields.amount').' (৳)', ['class' => 'form-label fw-bold text-dark']) !!} <span class="text-danger">*</span>
    {!! Form::number('amount', null, ['class' => 'form-control border-0 shadow-sm', 'placeholder' => 'e.g., 50000', 'required' => 'required', 'step' => '0.01', 'style' => 'border-radius: 8px;']) !!}
</div>

<!-- Purpose Field -->
<div class="form-group col-md-6 col-lg-4 mb-4">
    {!! Form::label('purpose', __('models/investments.fields.purpose').'', ['class' => 'form-label fw-bold text-dark']) !!}
    {!! Form::text('purpose', null, ['class' => 'form-control border-0 shadow-sm', 'placeholder' => 'Brief purpose of fund', 'style' => 'border-radius: 8px;']) !!}
</div>

<!-- Invested By Field -->
<div class="form-group col-md-6 col-lg-6 mb-4">
    {!! Form::label('invested_by', __('models/investments.fields.invested_by').'', ['class' => 'form-label fw-bold text-dark']) !!}
    <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-user text-primary"></i></span>
        {!! Form::text('invested_by', null, ['class' => 'form-control border-0', 'placeholder' => 'Name of the investor']) !!}
    </div>
</div>

<!-- Date Field -->
<div class="form-group col-md-6 col-lg-6 mb-4">
    {!! Form::label('created_at', __('models/investments.fields.created_at').'', ['class' => 'form-label fw-bold text-dark']) !!} <span class="text-danger">*</span>
    <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-calendar-day text-success"></i></span>
        {!! Form::date('created_at', empty($investment) ? \Carbon\Carbon::now()->format('Y-m-d') : $investment->created_at->format('Y-m-d'), ['class' => 'form-control border-0', 'required' => 'required']) !!}
    </div>
</div>

<!-- Description Field -->
<div class="form-group col-12 mb-4">
    {!! Form::label('description', __('models/investments.fields.description').'', ['class' => 'form-label fw-bold text-dark']) !!}
    {!! Form::textarea('description', null, ['class' => 'form-control border-0 shadow-sm', 'rows' => 4, 'placeholder' => 'Detailed notes about this investment...', 'style' => 'border-radius: 8px; resize: vertical;']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-12 mt-3 pt-4 border-top text-end">
    <a href="{{ route('investments.index') }}" class="btn fw-semibold px-4 py-2 me-2" style="border-radius: 8px; background-color: #f1f5f9; color: #475569; transition: all 0.2s;">
        <i class="fas fa-times me-1"></i> @lang('crud.cancel')
    </a>
    {!! Form::button('<i class="fas fa-save me-1"></i> '.__('crud.save'), ['type' => 'submit', 'class' => 'btn btn-primary fw-semibold px-4 py-2 border-0 shadow-sm', 'style' => 'border-radius: 8px; transition: all 0.2s;']) !!}
</div>
