@extends('layouts.app')
@section('title')
    @lang('models/sMSTEMPALTES.plural')
@endsection

@section('css')
<style>
    .sms-template-page {
        background: #f5f7fb;
    }

    .sms-template-hero {
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

    .sms-template-kicker {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .sms-template-hero h1 {
        margin: 0;
        color: #172033;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .sms-template-hero p {
        max-width: 700px;
        margin: 8px 0 0;
        color: #667085;
        font-size: 0.94rem;
        line-height: 1.55;
    }

    .sms-template-create-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 10px;
        color: #ffffff;
        background: #2563eb;
        font-size: 0.86rem;
        font-weight: 750;
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.24);
        text-decoration: none;
        white-space: nowrap;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }

    .sms-template-create-btn:hover {
        background: #1d4ed8;
        box-shadow: 0 9px 18px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .sms-template-kpi {
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

    .sms-template-kpi:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
        box-shadow: 0 9px 22px rgba(31, 41, 55, 0.08);
    }

    .sms-template-kpi-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .sms-template-kpi-label {
        color: #667085;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .sms-template-kpi-value {
        margin-top: 4px;
        color: #172033;
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .sms-template-kpi-subtitle {
        margin-top: 3px;
        color: #98a2b3;
        font-size: 0.74rem;
    }

    .sms-template-filter-card,
    .sms-template-list-card {
        border: 1px solid #e7ebf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
        overflow: hidden;
    }

    .sms-template-filter-header,
    .sms-template-list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .sms-template-filter-header h5,
    .sms-template-list-header h5 {
        margin: 0;
        color: #172033;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-template-filter-header p,
    .sms-template-list-header p {
        margin: 4px 0 0;
        color: #667085;
        font-size: 0.78rem;
    }

    .sms-template-result-count,
    .sms-template-active-count {
        padding: 6px 10px;
        border-radius: 999px;
        color: #475467;
        background: #f2f4f7;
        font-size: 0.74rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .sms-template-active-count {
        color: #2563eb;
        background: #eff6ff;
    }

    .sms-template-filter-body {
        padding: 18px 20px 20px;
    }

    .sms-template-filter-label {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .sms-template-filter-input,
    .sms-template-filter-select {
        height: 42px;
        border: 1px solid #d0d5dd;
        border-radius: 8px;
        color: #344054;
        background-color: #ffffff;
        font-size: 0.84rem;
    }

    .sms-template-filter-input:focus,
    .sms-template-filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .sms-template-search .input-group-text {
        border-right: 0;
        color: #667085;
        background-color: #f9fafb;
    }

    .sms-template-search .form-control {
        border-left: 0;
    }

    .sms-template-filter-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .sms-template-list {
        display: grid;
        gap: 13px;
        padding: 17px 20px 20px;
    }

    .sms-template-card {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 16px;
        padding: 17px;
        border: 1px solid #e7ebf3;
        border-radius: 13px;
        background: #ffffff;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }

    .sms-template-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 7px 18px rgba(31, 41, 55, 0.07);
        transform: translateY(-1px);
    }

    .sms-template-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 11px;
        color: #2563eb;
        background: #eff6ff;
        font-size: 0.9rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .sms-template-main {
        min-width: 0;
    }

    .sms-template-title-row {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
    }

    .sms-template-title {
        display: inline-block;
        max-width: calc(100% - 70px);
        margin: 0;
        color: #172033;
        font-size: 0.98rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        text-decoration: none;
    }

    .sms-template-title:hover {
        color: #2563eb;
    }

    .sms-template-id {
        padding: 3px 7px;
        border-radius: 6px;
        color: #667085;
        background: #f2f4f7;
        font-size: 0.66rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .sms-template-preview {
        display: -webkit-box;
        max-width: 760px;
        margin: 7px 0 0;
        overflow: hidden;
        color: #475467;
        font-size: 0.8rem;
        line-height: 1.5;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .sms-template-meta {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .sms-template-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border: 1px solid #eaecf0;
        border-radius: 7px;
        color: #475467;
        background: #f9fafb;
        font-size: 0.68rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .sms-template-side {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 11px;
        min-width: 145px;
    }

    .sms-template-updated {
        color: #667085;
        font-size: 0.7rem;
        line-height: 1.45;
        text-align: right;
    }

    .sms-template-updated strong {
        display: block;
        color: #344054;
        font-size: 0.74rem;
    }

    .sms-template-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sms-template-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #e4e7ec;
        border-radius: 8px;
        color: #475467;
        background: #ffffff;
        font-size: 0.78rem;
        text-decoration: none;
        transition: color 0.18s ease, background-color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
    }

    .sms-template-action-btn:hover {
        border-color: #cbd5e1;
        color: #2563eb;
        background: #eff6ff;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .sms-template-action-btn.sms-template-copy-action:hover {
        color: #16a34a;
        background: #ecfdf5;
        border-color: #a7f3d0;
    }

    .sms-template-action-btn.sms-template-delete-action:hover {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .sms-template-empty {
        padding: 54px 20px;
        color: #667085;
        text-align: center;
    }

    .sms-template-empty-icon {
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

    .sms-template-empty h6 {
        margin: 0 0 5px;
        color: #344054;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-template-empty p {
        margin: 0;
        font-size: 0.8rem;
    }

    .sms-template-pagination-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 20px;
        border-top: 1px solid #edf0f5;
    }

    .sms-template-pagination-info {
        color: #667085;
        font-size: 0.74rem;
    }

    .sms-template-pagination .page-link {
        min-width: 32px;
        min-height: 32px;
        padding: 5px 8px;
        border: 1px solid #d0d5dd;
        border-radius: 7px !important;
        color: #475467;
        background: #ffffff;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .sms-template-pagination .page-item.disabled .page-link {
        color: #98a2b3;
        background: #f9fafb;
    }

    .sms-template-pagination .page-item.active .page-link {
        border-color: #2563eb;
        color: #ffffff;
        background: #2563eb;
    }

    @media (max-width: 991.98px) {
        .sms-template-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .sms-template-card {
            grid-template-columns: auto minmax(0, 1fr);
        }

        .sms-template-side {
            grid-column: 1 / -1;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #edf0f5;
            padding-top: 12px;
        }
    }

    @media (max-width: 767.98px) {
        .sms-template-hero {
            padding: 20px;
        }

        .sms-template-hero h1 {
            font-size: 1.65rem;
        }

        .sms-template-create-btn {
            width: 100%;
            justify-content: center;
        }

        .sms-template-filter-header,
        .sms-template-list-header,
        .sms-template-pagination-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .sms-template-filter-actions {
            align-items: stretch;
        }

        .sms-template-filter-actions .btn {
            width: 100%;
        }

        .sms-template-list {
            padding: 13px;
        }

        .sms-template-card {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .sms-template-avatar {
            width: 40px;
            height: 40px;
        }

        .sms-template-side {
            grid-column: auto;
            flex-direction: column;
            align-items: flex-start;
        }

        .sms-template-updated {
            text-align: left;
        }

        .sms-template-actions {
            width: 100%;
        }

        .sms-template-action-btn {
            flex: 1;
        }
    }
</style>
@endsection

@section('content')
<section class="section sms-template-page">
    <div class="container-fluid">
        <div class="sms-template-hero mb-4">
            <div>
                <div class="sms-template-kicker">
                    <i class="fas fa-file-alt"></i> Messaging library
                </div>
                <h1>@lang('models/sMSTEMPALTES.plural')</h1>
                <p>@lang('models/sMSTEMPALTES.labels.page_description')</p>
            </div>
            <a href="{{ route('sMSTEMPALTES.create') }}" class="sms-template-create-btn">
                <i class="fas fa-plus"></i> @lang('crud.add_new')
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="sms-template-kpi">
                    <div class="sms-template-kpi-icon" style="color:#2563eb;background:#eff6ff;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="sms-template-kpi-label">@lang('models/sMSTEMPALTES.labels.total_templates')</div>
                        <div class="sms-template-kpi-value">{{ number_format($sMSTEMPALTES->total()) }}</div>
                        <div class="sms-template-kpi-subtitle">@lang('models/sMSTEMPALTES.labels.library_total')</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="sms-template-kpi">
                    <div class="sms-template-kpi-icon" style="color:#16a34a;background:#ecfdf5;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="sms-template-kpi-label">@lang('models/sMSTEMPALTES.labels.reusable')</div>
                        <div class="sms-template-kpi-value">100%</div>
                        <div class="sms-template-kpi-subtitle">@lang('models/sMSTEMPALTES.labels.ready_to_use')</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="sms-template-kpi">
                    <div class="sms-template-kpi-icon" style="color:#7c3aed;background:#f5f3ff;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="sms-template-kpi-label">@lang('models/sMSTEMPALTES.labels.last_updated')</div>
                        <div class="sms-template-kpi-value" style="font-size:1.15rem;line-height:1.35;">
                            @if($latestTemplate)
                                {{ $latestTemplate->setTimezone('Asia/Dhaka')->format('d M Y') }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="sms-template-kpi-subtitle">{{ $latestTemplate ? $latestTemplate->diffForHumans() : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sms-template-filter-card mb-4">
            <div class="sms-template-filter-header">
                <div>
                    <h5>@lang('models/sMSTEMPALTES.labels.filter_title')</h5>
                    <p>@lang('models/sMSTEMPALTES.labels.filter_description')</p>
                </div>
                <span class="sms-template-active-count">@lang('models/sMSTEMPALTES.labels.filters')</span>
            </div>
            <div class="sms-template-filter-body">
                <form method="GET" action="{{ route('sMSTEMPALTES.index') }}" class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label class="sms-template-filter-label" for="search">@lang('models/sMSTEMPALTES.labels.search_label')</label>
                        <div class="input-group sms-template-search">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" id="search" name="search" class="form-control sms-template-filter-input" value="{{ request('search') }}" placeholder="{{ $sMSTEMPALTES->total() ? __('models/sMSTEMPALTES.labels.search_placeholder') : __('models/sMSTEMPALTES.labels.search_placeholder_empty') }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="sms-template-filter-label" for="sort">@lang('models/sMSTEMPALTES.labels.sort_label')</label>
                        <select id="sort" name="sort" class="form-select sms-template-filter-select">
                            <option value="updated_at" @if(request('sort') === 'updated_at') selected @endif>@lang('models/sMSTEMPALTES.labels.sort_latest')</option>
                            <option value="title" @if(request('sort') === 'title') selected @endif>@lang('models/sMSTEMPALTES.labels.sort_title')</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="sms-template-filter-label" for="direction">@lang('models/sMSTEMPALTES.labels.direction_label')</label>
                        <select id="direction" name="direction" class="form-select sms-template-filter-select">
                            <option value="desc" @if(request('direction') === 'desc') selected @endif>@lang('models/sMSTEMPALTES.labels.desc')</option>
                            <option value="asc" @if(request('direction') === 'asc') selected @endif>@lang('models/sMSTEMPALTES.labels.asc')</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12">
                        <div class="sms-template-filter-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> @lang('models/sMSTEMPALTES.labels.apply_filters')
                            </button>
                            <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-light">
                                <i class="fas fa-rotate-left me-1"></i> @lang('models/sMSTEMPALTES.labels.reset_filters')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="sms-template-list-card">
            <div class="sms-template-list-header">
                <div>
                    <h5>@lang('models/sMSTEMPALTES.labels.template_library')</h5>
                    <p>@lang('models/sMSTEMPALTES.labels.template_library_description')</p>
                </div>
                <span class="sms-template-result-count">{{ number_format($sMSTEMPALTES->total()) }} @lang('models/sMSTEMPALTES.labels.records')</span>
            </div>
            <div class="card-body p-0">
                @include('s_m_s__t_e_m_p_a_l_t_e_s.table')
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/sms_counter.min.js') }}"></script>
<script>
    $(function () {
        const copiedMessage = @json(__('models/sMSTEMPALTES.labels.copied'));
        const deleteTitle = @json(__('models/sMSTEMPALTES.labels.delete_title'));
        const deleteMessage = @json(__('models/sMSTEMPALTES.labels.delete_message'));
        const deleteAction = @json(__('crud.delete'));
        const cancelAction = @json(__('crud.cancel'));

        $('[data-copy-template]').on('click', function () {
            const text = $(this).data('copyTemplate') || '';
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function () {
                    showTemplateToast(copiedMessage);
                }).catch(function () {
                    fallbackCopy(text);
                });
            } else {
                fallbackCopy(text);
            }
        });

        $('[data-delete-template]').on('click', function (event) {
            event.preventDefault();
            const id = $(this).data('deleteTemplate');
            const title = $(this).data('deleteTitle') || '';
            Swal.fire({
                title: deleteTitle,
                text: deleteMessage + (title ? ' (' + title + ')' : ''),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#667085',
                confirmButtonText: deleteAction,
                cancelButtonText: cancelAction
            }).then(function (result) {
                if (result.isConfirmed) {
                    $('#sms-template-delete-' + id).submit();
                }
            });
        });
    });

    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showTemplateToast('@lang('models/sMSTEMPALTES.labels.copied')');
    }

    function showTemplateToast(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1800,
                toast: true,
                position: 'top-end'
            });
        }
    }
</script>
@endsection
