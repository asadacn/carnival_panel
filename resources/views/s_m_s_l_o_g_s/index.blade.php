@extends('layouts.app')
@section('title')
    @lang('models/sMSLOGS.plural')
@endsection

@section('css')
<style>
    .sms-log-page {
        background: #f6f8fc;
    }

    .sms-log-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 26px 28px;
        border: 1px solid #e5eaf2;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 8px 24px rgba(31, 41, 55, 0.05);
    }

    .sms-page-kicker {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .sms-log-header h1 {
        margin: 0;
        color: #172033;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .sms-log-header p {
        max-width: 680px;
        margin: 8px 0 0;
        color: #667085;
        font-size: 0.94rem;
        line-height: 1.55;
    }

    .sms-live-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid #dbe4f0;
        border-radius: 999px;
        color: #475467;
        background: #ffffff;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .sms-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #16a34a;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.12);
    }

    .sms-stat-card {
        display: flex;
        align-items: center;
        gap: 15px;
        min-height: 108px;
        padding: 20px;
        border: 1px solid #e7ebf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .sms-stat-card:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
        box-shadow: 0 9px 22px rgba(31, 41, 55, 0.08);
    }

    .sms-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .sms-stat-label {
        color: #667085;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .sms-stat-value {
        margin-top: 4px;
        color: #172033;
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .sms-stat-subtitle {
        margin-top: 3px;
        color: #98a2b3;
        font-size: 0.74rem;
    }

    .sms-filter-card {
        border: 1px solid #e7ebf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
        overflow: hidden;
    }

    .sms-filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .sms-filter-header h5 {
        margin: 0;
        color: #172033;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-filter-header p {
        margin: 4px 0 0;
        color: #667085;
        font-size: 0.78rem;
    }

    .sms-filter-count {
        padding: 5px 9px;
        border-radius: 999px;
        color: #2563eb;
        background: #eff6ff;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .sms-filter-body {
        padding: 18px 20px 20px;
    }

    .sms-filter-label {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .sms-filter-input,
    .sms-filter-select {
        height: 42px;
        border: 1px solid #d0d5dd;
        border-radius: 8px;
        color: #344054;
        background-color: #ffffff;
        font-size: 0.84rem;
    }

    .sms-filter-input:focus,
    .sms-filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .sms-filter-search .input-group-text {
        border-right: 0;
        color: #667085;
        background-color: #f9fafb;
    }

    .sms-filter-search .form-control {
        border-left: 0;
    }

    .sms-filter-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .sms-table-card {
        border: 1px solid #e7ebf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
        overflow: hidden;
    }

    .sms-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .sms-table-header h5 {
        margin: 0;
        color: #172033;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-table-header p {
        margin: 4px 0 0;
        color: #667085;
        font-size: 0.78rem;
    }

    .sms-result-count {
        padding: 6px 10px;
        border-radius: 999px;
        color: #475467;
        background: #f2f4f7;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .sms-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .sms-log-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.84rem;
    }

    .sms-log-table > thead > tr > th {
        padding: 12px 16px;
        border-bottom: 1px solid #e4e7ec;
        color: #667085;
        background: #f9fafb;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sms-log-table > tbody > tr {
        border-bottom: 1px solid #edf0f5;
        transition: background-color 0.15s ease;
    }

    .sms-log-table > tbody > tr:last-child {
        border-bottom: 0;
    }

    .sms-log-table > tbody > tr:hover {
        background-color: #f8faff;
    }

    .sms-log-table > tbody > tr > td {
        padding: 14px 16px;
        color: #344054;
        vertical-align: middle;
    }

    .sms-recipient {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 185px;
    }

    .sms-recipient-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        color: #2563eb;
        background: #eff6ff;
        font-size: 0.76rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .sms-recipient-title {
        display: block;
        max-width: 150px;
        color: #172033;
        font-weight: 750;
        text-decoration: none;
    }

    .sms-recipient-title:hover {
        color: #2563eb;
    }

    .sms-recipient-meta {
        display: block;
        max-width: 170px;
        margin-top: 3px;
        overflow: hidden;
        color: #98a2b3;
        font-size: 0.72rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sms-message {
        max-width: 430px;
        color: #344054;
        font-size: 0.8rem;
        line-height: 1.45;
    }

    .sms-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .sms-campaign {
        display: block;
        max-width: 145px;
        margin-top: 4px;
        overflow: hidden;
        color: #667085;
        font-size: 0.68rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sms-parts {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 7px;
        border: 1px solid #eaecf0;
        border-radius: 6px;
        color: #475467;
        background: #f9fafb;
        font-size: 0.68rem;
        font-weight: 650;
        white-space: nowrap;
    }

    .sms-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .sms-time-primary {
        display: block;
        color: #344054;
        font-weight: 700;
        white-space: nowrap;
    }

    .sms-time-relative {
        display: block;
        margin-top: 3px;
        color: #98a2b3;
        font-size: 0.68rem;
        white-space: nowrap;
    }

    .sms-sender {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475467;
        font-size: 0.76rem;
        white-space: nowrap;
    }

    .sms-empty {
        padding: 54px 20px;
        color: #667085;
        text-align: center;
    }

    .sms-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        margin-bottom: 12px;
        border-radius: 14px;
        color: #98a2b3;
        background: #f2f4f7;
        font-size: 1.35rem;
    }

    .sms-empty h6 {
        margin: 0 0 5px;
        color: #344054;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-empty p {
        margin: 0;
        font-size: 0.8rem;
    }

    .sms-pagination-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 20px;
        border-top: 1px solid #edf0f5;
    }

    .sms-pagination-info {
        color: #667085;
        font-size: 0.74rem;
    }

    .sms-pagination .page-link {
        min-width: 32px;
        min-height: 32px;
        padding: 5px 8px;
        border: 1px solid #d0d5dd;
        border-radius: 7px !important;
        color: #475467;
        background: #ffffff;
        font-size: 0.76rem;
        font-weight: 650;
    }

    .sms-pagination .page-item.disabled .page-link {
        color: #98a2b3;
        background: #f9fafb;
    }

    .sms-pagination .page-item.active .page-link {
        border-color: #2563eb;
        color: #ffffff;
        background: #2563eb;
    }

    @media (max-width: 991.98px) {
        .sms-log-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .sms-live-pill {
            align-self: flex-start;
        }
    }

    @media (max-width: 767.98px) {
        .sms-log-header {
            padding: 20px;
        }

        .sms-log-header h1 {
            font-size: 1.65rem;
        }

        .sms-filter-header,
        .sms-filter-body,
        .sms-table-header,
        .sms-pagination-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .sms-filter-actions {
            align-items: stretch;
        }

        .sms-filter-actions .btn {
            width: 100%;
        }

        .sms-table-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<section class="section sms-log-page">
    <div class="container-fluid">
        <div class="sms-log-header mb-4">
            <div>
                <div class="sms-page-kicker">
                    <i class="fas fa-broadcast-tower"></i> Messaging operations
                </div>
                <h1>@lang('models/sMSLOGS.plural')</h1>
                <p>@lang('models/sMSLOGS.labels.page_description')</p>
            </div>
            <div class="sms-live-pill">
                <span class="sms-live-dot"></span>
                @lang('models/sMSLOGS.labels.live_audit')
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="sms-stat-card">
                    <div class="sms-stat-icon" style="color:#2563eb;background:#eff6ff;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <div class="sms-stat-label">@lang('models/sMSLOGS.labels.total_logs')</div>
                        <div class="sms-stat-value">{{ number_format($stats['total']) }}</div>
                        <div class="sms-stat-subtitle">@lang('models/sMSLOGS.labels.all_time')</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="sms-stat-card">
                    <div class="sms-stat-icon" style="color:#16a34a;background:#ecfdf5;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="sms-stat-label">@lang('models/sMSLOGS.labels.sent_logs')</div>
                        <div class="sms-stat-value">{{ number_format($stats['sent']) }}</div>
                        <div class="sms-stat-subtitle">{{ number_format($stats['total'] ? ($stats['sent'] / $stats['total']) * 100 : 0, 1) }}% delivery</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="sms-stat-card">
                    <div class="sms-stat-icon" style="color:#dc2626;background:#fef2f2;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <div class="sms-stat-label">@lang('models/sMSLOGS.labels.failed_logs')</div>
                        <div class="sms-stat-value">{{ number_format($stats['failed']) }}</div>
                        <div class="sms-stat-subtitle">@lang('models/sMSLOGS.labels.requires_review')</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="sms-stat-card">
                    <div class="sms-stat-icon" style="color:#7c3aed;background:#f5f3ff;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <div class="sms-stat-label">@lang('models/sMSLOGS.labels.today_logs')</div>
                        <div class="sms-stat-value">{{ number_format($stats['today']) }}</div>
                        <div class="sms-stat-subtitle">{{ now()->setTimezone('Asia/Dhaka')->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sms-filter-card mb-4">
            <div class="sms-filter-header">
                <div>
                    <h5>@lang('models/sMSLOGS.labels.filter_title')</h5>
                    <p>@lang('models/sMSLOGS.labels.filter_description')</p>
                </div>
                <span class="sms-filter-count">@lang('models/sMSLOGS.labels.active_filters')</span>
            </div>
            <div class="sms-filter-body">
                <form method="GET" action="{{ route('sms_log') }}" class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="sms-filter-label" for="client_search">@lang('models/sMSLOGS.labels.search_label')</label>
                        <div class="input-group sms-filter-search">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" id="client_search" name="client_search" class="form-control sms-filter-input" value="{{ request('client_search') }}" placeholder="{{ $SMSLOGS->total() ? __('models/sMSLOGS.labels.search_placeholder') : __('models/sMSLOGS.labels.search_placeholder_empty') }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="sms-filter-label" for="status">@lang('models/sMSLOGS.labels.status_label')</label>
                        <select id="status" name="status" class="form-select sms-filter-select">
                            <option value="" @if(!request('status')) selected @endif>@lang('models/sMSLOGS.labels.all_statuses')</option>
                            <option value="sent" @if(request('status') === 'sent') selected @endif>@lang('models/sMSLOGS.labels.sent_logs')</option>
                            <option value="failed" @if(request('status') === 'failed') selected @endif>@lang('models/sMSLOGS.labels.failed_logs')</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="sms-filter-label" for="message_type">@lang('models/sMSLOGS.labels.type_label')</label>
                        <select id="message_type" name="message_type" class="form-select sms-filter-select">
                            <option value="" @if(!request('message_type')) selected @endif>@lang('models/sMSLOGS.labels.all_message_types')</option>
                            @foreach(['single' => 'Single', 'bulk' => 'Bulk', 'bill' => 'Bill', 'bill_payment' => 'Bill payment', 'reminder' => 'Reminder'] as $messageType => $messageTypeLabel)
                                <option value="{{ $messageType }}" @if(request('message_type') === $messageType) selected @endif>{{ $messageTypeLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="sms-filter-label" for="from_date">@lang('models/sMSLOGS.labels.from_date')</label>
                        <input type="date" id="from_date" name="from_date" class="form-control sms-filter-input" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="sms-filter-label" for="to_date">@lang('models/sMSLOGS.labels.to_date')</label>
                        <input type="date" id="to_date" name="to_date" class="form-control sms-filter-input" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="sms-filter-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> @lang('models/sMSLOGS.labels.apply_filters')
                            </button>
                            <a href="{{ route('sms_log') }}" class="btn btn-light">
                                <i class="fas fa-rotate-left me-1"></i> @lang('models/sMSLOGS.labels.reset_filters')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="sms-table-card">
            <div class="sms-table-header">
                <div>
                    <h5>@lang('models/sMSLOGS.labels.message_activity')</h5>
                    <p>@lang('models/sMSLOGS.labels.message_activity_description')</p>
                </div>
                <span class="sms-result-count">{{ number_format($SMSLOGS->total()) }} @lang('models/sMSLOGS.labels.records')</span>
            </div>
            <div class="card-body p-0">
                @include('s_m_s_l_o_g_s.table')
            </div>
        </div>
    </div>
</section>
@endsection


