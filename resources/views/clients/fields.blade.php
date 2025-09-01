<div class="container mt-4">
    <!-- Client Info Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-user me-2"></i>Client Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    {!! Form::label('name', __('models/clients.fields.name').':', ['class' => 'form-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter client name']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('contact', __('models/clients.fields.contact').':', ['class' => 'form-label']) !!}
                    {!! Form::text('contact', null, ['class' => 'form-control', 'placeholder' => 'Enter primary contact number']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('secondary_contact', __('models/clients.fields.secondary_contact').':', ['class' => 'form-label']) !!}
                    {!! Form::text('secondary_contact', null, ['class' => 'form-control', 'placeholder' => 'Enter secondary contact number']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('address', __('models/clients.fields.address').':', ['class' => 'form-label']) !!}
                    {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Enter client address']) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- PPPoE Subsection -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-network-wired me-2"></i>PPPoE Details</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Carnival ID / Username -->
                <div class="col-md-4">
                    {!! Form::label('username', __('models/clients.fields.username').':', ['class' => 'form-label']) !!}
                    {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Enter Carnival ID']) !!}
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    {!! Form::label('password', __('models/clients.fields.password').':', ['class' => 'form-label']) !!}
                    {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Enter PPPoE password']) !!}
                </div>

                <!-- Package Dropdown -->
                <div class="col-md-4">
                    {!! Form::label('package', __('models/clients.fields.package').':', ['class' => 'form-label']) !!}
                    {!! Form::select('package', $packages->pluck('title', 'id'), null, ['class' => 'form-select', 'placeholder' => 'Select package']) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Info Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5><i class="fas fa-cogs me-2"></i>Equipment Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    {!! Form::label('Onu_mac', __('models/clients.fields.Onu_mac').':', ['class' => 'form-label']) !!}
                    {!! Form::text('Onu_mac', null, ['class' => 'form-control', 'placeholder' => 'Enter ONU MAC address']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('onu_serial', __('models/clients.fields.onu_serial').':', ['class' => 'form-label']) !!}
                    {!! Form::text('onu_serial', null, ['class' => 'form-control', 'placeholder' => 'Enter ONU serial number']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('onu_brand', __('models/clients.fields.onu_brand').':', ['class' => 'form-label']) !!}
                    {!! Form::text('onu_brand', null, ['class' => 'form-control', 'placeholder' => 'Enter ONU brand']) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('onu_free', __('models/clients.fields.onu_free').':', ['class' => 'form-label']) !!}
                    {!! Form::select('onu_free', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-select']) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('onu_returned', __('models/clients.fields.onu_returned').':', ['class' => 'form-label']) !!}
                    {!! Form::select('onu_returned', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-select']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('onu_owner', __('models/clients.fields.onu_owner').':', ['class' => 'form-label']) !!}
                    {!! Form::select('onu_owner', ['company' => 'Company', 'client' => 'Client'], null, ['class' => 'form-select']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('cable', __('models/clients.fields.cable').':', ['class' => 'form-label']) !!}
                    {!! Form::number('cable', null, ['class' => 'form-control', 'placeholder' => 'Enter cable length (if any)']) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('cable_returned', __('models/clients.fields.cable_returned').':', ['class' => 'form-label']) !!}
                    {!! Form::select('cable_returned', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-select']) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('cable_owner', __('models/clients.fields.cable_owner').':', ['class' => 'form-label']) !!}
                    {!! Form::select('cable_owner', ['company' => 'Company', 'client' => 'Client'], null, ['class' => 'form-select']) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Billing & Status Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5><i class="fas fa-info-circle me-2"></i>Status & Billing</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    {!! Form::label('billing_type', __('models/clients.fields.billing_type').':', ['class' => 'form-label']) !!}
                    {!! Form::select('billing_type', ['prepaid' => 'Prepaid', 'postpaid' => 'Postpaid'], null, ['class' => 'form-select']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('status', __('models/clients.fields.status').':', ['class' => 'form-label']) !!}
                    {!! Form::select('status', ['registered' => 'Registered', 'expired' => 'Expired'], null, ['class' => 'form-select']) !!}
                </div>
                <div class="col-12 mt-3">
                    {!! Form::label('comment', __('models/clients.fields.comment').':', ['class' => 'form-label']) !!}
                    {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Any additional comments']) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="mb-4 text-end">
        {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary me-2']) !!}
        <a href="{{ route('clients.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
    </div>
</div>
