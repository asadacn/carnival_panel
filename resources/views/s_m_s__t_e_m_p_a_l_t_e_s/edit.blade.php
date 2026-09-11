@extends('layouts.app')
@section('title')
    @lang('crud.edit') @lang('models/sMSTEMPALTES.singular')
@endsection

@section('css')
<style>
    .sms-form-page {
        background: #f5f7fb;
    }

    .sms-form-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 26px 28px;
        border: 1px solid #e5eaf2;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 8px 24px rgba(31, 41, 55, 0.05);
        margin-bottom: 24px;
    }

    .sms-form-hero h1 {
        margin: 0;
        color: #172033;
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .sms-form-hero p {
        margin: 6px 0 0;
        color: #667085;
        font-size: 0.92rem;
    }

    .sms-form-card {
        border: 1px solid #e7ebf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
        overflow: hidden;
    }

    .sms-form-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .sms-form-card-header h5 {
        margin: 0;
        color: #172033;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-form-card-body {
        padding: 20px;
    }

    .sms-form-preview {
        border: 1px solid #e7ebf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
        overflow: hidden;
        position: sticky;
        top: 20px;
    }

    .sms-form-preview-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .sms-form-preview-header h5 {
        margin: 0;
        color: #172033;
        font-size: 1rem;
        font-weight: 800;
    }

    .sms-form-preview-body {
        padding: 20px;
    }

    .sms-form-preview-meta {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .sms-form-preview-meta-item {
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
    }

    .sms-form-preview-text {
        color: #172033;
        font-size: 0.9rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .sms-form-preview-empty {
        color: #98a2b3;
        font-size: 0.85rem;
        font-style: italic;
    }

    .sms-counter-inline {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border: 1px solid #e7ebf3;
        border-radius: 10px;
        background: #f9fafb;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .sms-counter-inline .encoding,
    .sms-counter-inline .length,
    .sms-counter-inline .messages,
    .sms-counter-inline .remaining {
        font-size: 0.78rem;
        font-weight: 700;
        color: #475467;
    }

    .sms-counter-inline .messages strong,
    .sms-counter-inline .remaining strong {
        color: #172033;
    }

    @media (max-width: 991.98px) {
        .sms-form-preview {
            position: static;
            margin-top: 20px;
        }
    }
</style>
@endsection

@section('content')
<section class="section sms-form-page">
    <div class="container-fluid">
        <div class="sms-form-hero mb-4">
            <div>
                <h1>@lang('crud.edit') @lang('models/sMSTEMPALTES.singular')</h1>
                <p>@lang('models/sMSTEMPALTES.labels.form_description')</p>
            </div>
            <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i> @lang('crud.back')
            </a>
        </div>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="sms-form-card">
                    <div class="sms-form-card-header">
                        <h5>@lang('models/sMSTEMPALTES.labels.template_details')</h5>
                    </div>
                    <div class="sms-form-card-body">
                        {!! Form::model($sMSTEMPALTE, ['route' => ['sMSTEMPALTES.update', $sMSTEMPALTE->id], 'method' => 'patch', 'id' => 'sms_form']) !!}
                            @include('s_m_s__t_e_m_p_a_l_t_e_s.fields')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="sms-form-preview">
                    <div class="sms-form-preview-header">
                        <h5>@lang('models/sMSTEMPALTES.labels.live_preview')</h5>
                    </div>
                    <div class="sms-form-preview-body">
                        <div id="preview-title" class="fw-bold mb-2" style="color:#172033;font-size:1rem;"></div>
                        <div id="preview-body" class="sms-form-preview-text"></div>
                        <div id="preview-empty" class="sms-form-preview-empty">@lang('models/sMSTEMPALTES.labels.preview_placeholder')</div>
                        <div id="preview-meta" class="sms-form-preview-meta" style="display:none;">
                            <span class="sms-form-preview-meta-item" id="preview-chars">
                                <i class="fas fa-text-width"></i> <span id="preview-chars-val">0</span> @lang('models/sMSTEMPALTES.labels.chars')
                            </span>
                            <span class="sms-form-preview-meta-item" id="preview-parts">
                                <i class="fas fa-layer-group"></i> <span id="preview-parts-val">0</span> @lang('models/sMSTEMPALTES.labels.parts')
                            </span>
                            <span class="sms-form-preview-meta-item" id="preview-encoding">
                                <i class="fas fa-code"></i> <span id="preview-encoding-val">-</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/sms_counter.min.js') }}"></script>
<script>
    $(function () {
        const $title = $('#title');
        const $body = $('#sms-body');
        const $previewTitle = $('#preview-title');
        const $previewBody = $('#preview-body');
        const $previewEmpty = $('#preview-empty');
        const $previewMeta = $('#preview-meta');
        const $previewCharsVal = $('#preview-chars-val');
        const $previewPartsVal = $('#preview-parts-val');
        const $previewEncodingVal = $('#preview-encoding-val');

        function updatePreview() {
            const title = $title.val() || '';
            const text = $body.val() || '';

            $previewTitle.text(title);
            if (text) {
                $previewBody.text(text);
                $previewEmpty.hide();
                $previewMeta.show();

                if (typeof SmsCounter !== 'undefined') {
                    const count = SmsCounter.count(text);
                    $previewCharsVal.text(count.length);
                    $previewPartsVal.text(count.messages > 0 ? count.messages : '-');
                    $previewEncodingVal.text(count.encoding.replace(/_/g, ' '));
                }
            } else {
                $previewBody.text('');
                $previewEmpty.show();
                $previewMeta.hide();
            }
        }

        $title.on('input', updatePreview);
        $body.on('input', updatePreview);

        $body.countSms('#sms-counter');

        updatePreview();
    });
</script>
@endsection
