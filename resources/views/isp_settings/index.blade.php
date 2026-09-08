@extends('layouts.app')

@section('title')
    @lang('messages.isp_settings_title')
@endsection

@section('css')
<style>
    .settings-container {
        padding: 2rem 0;
    }

    .settings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .settings-title {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Multi-ISP Profile Selector Cards */
    .isp-selector-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .isp-card-item {
        background: #ffffff;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 1rem 1.25rem;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: #1e293b;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .isp-card-item:hover {
        transform: translateY(-2px);
        border-color: #93c5fd;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06);
        color: #1e293b;
    }

    .isp-card-item.active {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .isp-thumb-box {
        width: 54px;
        height: 54px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        flex-shrink: 0;
    }

    .isp-thumb-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .isp-card-info {
        flex: 1;
        min-width: 0;
    }

    .isp-card-name {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .isp-card-code {
        font-family: monospace;
        font-size: 0.8rem;
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
        color: #475569;
        font-weight: 600;
    }

    .isp-card-item.active .isp-card-code {
        background: #bfdbfe;
        color: #1e40af;
    }

    .settings-card {
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .settings-card-header {
        background: #f8fafc;
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .settings-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
        font-size: 1.15rem;
    }

    .settings-card-header p {
        margin: 0.25rem 0 0 0;
        color: #64748b;
        font-size: 0.85rem;
    }

    .settings-card-body {
        padding: 1.75rem;
    }

    /* Logo Upload Box */
    .logo-upload-wrapper {
        display: flex;
        gap: 2rem;
        align-items: center;
        flex-wrap: wrap;
        padding: 1.25rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .logo-preview-box {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .logo-preview-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .upload-action-box {
        flex: 1;
        min-width: 260px;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.65rem 0.9rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .input-group-text {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 8px 0 0 8px;
        color: #64748b;
    }

    .helper-text {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    .btn-save-settings {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        cursor: pointer;
    }

    .btn-save-settings:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        color: #ffffff;
    }

    .nav-tabs-settings {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.75rem;
        display: flex;
        gap: 0.5rem;
    }

    .nav-tabs-settings .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border-radius: 6px 6px 0 0;
        background: transparent;
    }

    .nav-tabs-settings .nav-link.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
        background: rgba(37, 99, 235, 0.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="settings-container">

        <!-- Page Header -->
        <div class="settings-header">
            <div>
                <h1 class="settings-title">
                    <i class="fas fa-sliders-h text-primary"></i>
                    @lang('messages.isp_settings_title')
                </h1>
                <p class="text-muted mb-0 mt-1">@lang('messages.configure_distinct_logos_contact_information')</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createIspModal" style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-plus"></i> @lang('messages.add_new_isp_profile')
                    </button>
                    <a href="{{ route('due-bills.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> @lang('messages.back_to_bills')
                    </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                <i class="fas fa-exclamation-triangle me-2"></i> <strong>Please fix the errors below:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Available ISPs Card Selection Grid -->
        <h6 class="text-uppercase fw-bold text-muted small mb-2"><i class="fas fa-network-wired me-1"></i> @lang('messages.configured_isp_brand_profiles') ({{ $isps->count() }})</h6>
        <div class="isp-selector-wrapper">
            @foreach($isps as $isp)
                <a href="{{ route('isp-settings.index', ['isp_id' => $isp->id]) }}" class="isp-card-item {{ $selectedIsp && $selectedIsp->id === $isp->id ? 'active' : '' }}">
                    <div class="isp-thumb-box">
                        <img src="{{ $isp->logo_url }}" alt="{{ $isp->isp_name }}" class="isp-thumb-img">
                    </div>
                    <div class="isp-card-info">
                        <div class="isp-card-name">{{ $isp->isp_name }}</div>
                        <div class="d-flex align-items-center gap-1 mt-1">
                            <span class="isp-card-code">{{ $isp->isp_code }}</span>
                            @if($isp->is_default)
                                <span class="badge bg-success ms-auto" style="font-size: 0.7rem;">Default</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($selectedIsp)
        <!-- Edit Selected ISP Profile Form -->
        <form action="{{ route('isp-settings.update', $selectedIsp->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs nav-tabs-settings" id="settingsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                        <i class="fas fa-paint-brush"></i> @lang('messages.branding_logo')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                        <i class="fas fa-address-book"></i> @lang('messages.contact_location')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice" type="button" role="tab">
                        <i class="fas fa-file-invoice-dollar"></i> @lang('messages.invoice_receipt')
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="settingsTabContent">
                <!-- 1. BRANDING & LOGO TAB -->
                <div class="tab-pane fade show active" id="branding" role="tabpanel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div>
                                <h5>@lang('messages.logo_brand_identity_for') <span class="text-primary">{{ $selectedIsp->isp_name }}</span></h5>
                                <p>@lang('messages.this_logo_and_name_will_be_automatically_used') <code>{{ $selectedIsp->isp_code }}</code></p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if(!$selectedIsp->is_default)
                                    <button type="button" onclick="document.getElementById('set-default-form').submit()" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-star me-1"></i> @lang('messages.set_as_default_isp')
                                    </button>
                                    <button type="button" onclick="confirmDeleteIsp()" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt me-1"></i> @lang('messages.delete')
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <!-- Logo Upload -->
                            <div class="mb-4">
                                <label class="form-label">@lang('messages.isp_brand_logo') (@lang('messages.for') {{ $selectedIsp->isp_name }})</label>
                                <div class="logo-upload-wrapper">
                                    <div class="logo-preview-box">
                                        <img id="logo-preview" src="{{ $selectedIsp->logo_url }}" alt="{{ $selectedIsp->isp_name }}" class="logo-preview-img">
                                    </div>
                                    <div class="upload-action-box">
                                        <input type="file" name="isp_logo" id="isp_logo" class="form-control mb-2" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp">
                                        <div class="helper-text mb-2">
                                            @lang('messages.recommended_format'): <strong>@lang('messages.transparent_png_svg')</strong> @lang('messages.or_high_resolution_jpg')
                                        </div>
                                        @if($selectedIsp->isp_logo)
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo">
                                                <label class="form-check-label text-danger small fw-semibold" for="remove_logo">
                                                    <i class="fas fa-trash-alt me-1"></i> @lang('messages.remove_custom_logo_reset_to_system_default')
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.isp_company_name') <span class="text-danger">*</span></label>
                                    <input type="text" name="isp_name" class="form-control" value="{{ old('isp_name', $selectedIsp->isp_name) }}" placeholder="e.g. Bijoy Online" required>
                                    <div class="helper-text">@lang('messages.printed_as_the_main_company_header')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.isp_identifier_code') <span class="text-danger">*</span></label>
                                    <input type="text" name="isp_code" class="form-control font-monospace" value="{{ old('isp_code', $selectedIsp->isp_code) }}" placeholder="e.g. bijoy" required>
                                    <div class="helper-text">@lang('messages.matches_the_client_isp_code_in_database')</div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">@lang('messages.tagline_slogan')</label>
                                    <input type="text" name="isp_tagline" class="form-control" value="{{ old('isp_tagline', $selectedIsp->isp_tagline) }}" placeholder="e.g. High-Speed Broadband Internet & Network Solutions">
                                    <div class="helper-text">@lang('messages.appears_below_the_logo_on_printable_invoices')</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" {{ $selectedIsp->is_default ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_default">
                                            @lang('messages.make_this_the_default_isp_profile')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. CONTACT & LOCATION TAB -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div>
                                <h5>@lang('messages.contact_numbers_address_for') <span class="text-primary">{{ $selectedIsp->isp_name }}</span></h5>
                                <p>@lang('messages.hotlines_and_billing_support_contacts_specific_to_this_isp_brand')</p>
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.support_hotline_phone')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $selectedIsp->phone) }}" placeholder="e.g. 017XXXXXXXX, 096XXXXXXXX">
                                    </div>
                                    <div class="helper-text">@lang('messages.support_phone_printed_on_this_isp_invoices')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.billing_support_mobile')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                                        <input type="text" name="billing_phone" class="form-control" value="{{ old('billing_phone', $selectedIsp->billing_phone) }}" placeholder="e.g. 018XXXXXXXX">
                                    </div>
                                    <div class="helper-text">@lang('messages.dedicated_billing_query_number')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.official_email')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $selectedIsp->email) }}" placeholder="e.g. support@isp.com.bd">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.website_url')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="text" name="website" class="form-control" value="{{ old('website', $selectedIsp->website) }}" placeholder="e.g. www.carnival.com.bd">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">@lang('messages.office_billing_address')</label>
                                    <textarea name="address" rows="3" class="form-control" placeholder="Full street address, area, city, postal code">{{ old('address', $selectedIsp->address) }}</textarea>
                                    <div class="helper-text">@lang('messages.physical_address_printed_on_official_invoices')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. INVOICE & RECEIPT TAB -->
                <div class="tab-pane fade" id="invoice" role="tabpanel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div>
                                <h5>@lang('messages.invoice_receipt_payment_settings_for') <span class="text-primary">{{ $selectedIsp->isp_name }}</span></h5>
                                <p>@lang('messages.custom_money_receipt_headers')</p>
                            </div>
                        </div>
                        <div class="settings-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.invoice_receipt_header_title')</label>
                                    <input type="text" name="invoice_title" class="form-control" value="{{ old('invoice_title', $selectedIsp->invoice_title) }}" placeholder="e.g. MONEY RECEIPT or INVOICE">
                                    <div class="helper-text">@lang('messages.title_shown_on_the_printable_invoice_top_corner')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.currency_symbol')</label>
                                    <input type="text" name="currency_symbol" class="form-control" value="{{ old('currency_symbol', $selectedIsp->currency_symbol) }}" placeholder="e.g. ৳ or BDT">
                                    <div class="helper-text">@lang('messages.currency_prefix_used_across_bill_amounts')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.payment_methods')</label>
                                    <input type="text" name="payment_methods" class="form-control" value="{{ old('payment_methods', $selectedIsp->payment_methods) }}" placeholder="e.g. bKash / Nagad / Cash / Bank">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.payment_merchant_agent_number')</label>
                                    <input type="text" name="payment_number" class="form-control" value="{{ old('payment_number', $selectedIsp->payment_number) }}" placeholder="e.g. 017XXXXXXXX">
                                    <div class="helper-text">@lang('messages.bkas_nagad_number_mentioned_in_sms_notifications')</div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">@lang('messages.payment_instructions_note')</label>
                                    <textarea name="payment_instruction" rows="2" class="form-control" placeholder="e.g. Please pay through bKash/Nagad Merchant or Cash within the due date.">{{ old('payment_instruction', $selectedIsp->payment_instruction) }}</textarea>
                                    <div class="helper-text">@lang('messages.instruction_message_printed_on_invoices')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.authorized_signatory_title')</label>
                                    <input type="text" name="signatory_title" class="form-control" value="{{ old('signatory_title', $selectedIsp->signatory_title) }}" placeholder="e.g. Authorized Signatory / Accounts Manager">
                                    <div class="helper-text">@lang('messages.designation_printed_beneath_the_signature_line')</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">@lang('messages.invoice_footer_note_disclaimer')</label>
                                    <input type="text" name="invoice_footer" class="form-control" value="{{ old('invoice_footer', $selectedIsp->invoice_footer) }}" placeholder="e.g. Keep this invoice receipt for future reference. Thank you!">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky / Bottom Submit Button -->
            <div class="d-flex justify-content-end align-items-center gap-3 mt-4 mb-5">
                <button type="submit" class="btn-save-settings">
                    <i class="fas fa-save"></i> @lang('messages.save') {{ $selectedIsp->isp_name }} @lang('messages.settings')
                </button>
            </div>
        </form>

        <!-- Hidden Forms for Actions -->
        @if(!$selectedIsp->is_default)
            <form id="set-default-form" action="{{ route('isp-settings.default', $selectedIsp->id) }}" method="POST" class="d-none">
                @csrf
            </form>
            <form id="delete-isp-form" action="{{ route('isp-settings.destroy', $selectedIsp->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endif

        @endif

    </div>
</div>

<!-- Add New ISP Modal -->
<div class="modal fade" id="createIspModal" tabindex="-1" aria-labelledby="createIspModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createIspModalLabel"><i class="fas fa-plus-circle me-2"></i> @lang('messages.add_new_isp_profile')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('isp-settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.isp_name') <span class="text-danger">*</span></label>
                            <input type="text" name="isp_name" class="form-control" placeholder="e.g. Link3 Technologies" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.isp_identifier_code_required') <span class="text-danger">*</span></label>
                            <input type="text" name="isp_code" class="form-control font-monospace" placeholder="e.g. link3" required>
                            <div class="helper-text">@lang('messages.unique_code_assigned_to_clients')</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">@lang('messages.tagline')</label>
                            <input type="text" name="isp_tagline" class="form-control" placeholder="e.g. Connecting You to the World">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">@lang('messages.isp_brand_logo')</label>
                            <input type="file" name="isp_logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.support_hotline')</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 017XXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.email_label')</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. support@isp.com.bd">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">@lang('messages.office_address')</label>
                            <textarea name="address" rows="2" class="form-control" placeholder="Street, area, city"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.payment_methods')</label>
                            <input type="text" name="payment_methods" class="form-control" placeholder="e.g. bKash / Nagad / Cash" value="bKash / Nagad / Cash">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.payment_number')</label>
                            <input type="text" name="payment_number" class="form-control" placeholder="e.g. 01XXXXXXXXX">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">@lang('messages.payment_instructions_note')</label>
                            <input type="text" name="payment_instruction" class="form-control" value="Please pay through bKash/Nagad Merchant or Cash within the due date.">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> @lang('messages.create_isp_profile')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Live image preview for uploaded logo
        const logoInput = document.getElementById('isp_logo');
        const logoPreview = document.getElementById('logo-preview');

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        logoPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    function confirmDeleteIsp() {
        if (confirm('Are you sure you want to delete this ISP profile? This cannot be undone.')) {
            document.getElementById('delete-isp-form').submit();
        }
    }
</script>
@endsection
