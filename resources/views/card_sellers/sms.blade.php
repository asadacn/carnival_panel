@extends('layouts.app')
@section('title')
    @lang('crud.import') SMS — @lang('models/cardSellers.plural')
@endsection

@section('page_css')
<style>
    :root {
        --cs-primary: #6777ef;
        --cs-accent: #3abaf4;
        --cs-success: #47c363;
        --cs-danger: #fc544b;
        --cs-warning: #ffa426;
        --cs-border: #e9ecef;
        --cs-text: #2d3748;
        --cs-muted: #7a828a;
        --cs-radius: 10px;
        --cs-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .cs-page { padding: 20px 24px 32px; animation: csFadeUp .45s ease both; }
    @keyframes csFadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

    .cs-hero {
        background: linear-gradient(135deg, var(--cs-primary) 0%, var(--cs-accent) 100%);
        border-radius: var(--cs-radius);
        padding: 24px 28px;
        margin-bottom: 20px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 6px 22px rgba(103, 119, 239, 0.25);
        position: relative; overflow: hidden;
    }
    .cs-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(255,255,255,.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .cs-hero-text h1 { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0; }
    .cs-hero-text p { color: rgba(255,255,255,.85); margin: 4px 0 0; font-size: .88rem; }
    .cs-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; position: relative; z-index: 1; }
    .cs-hero-actions .btn { display: inline-flex; align-items: center; gap: 7px; font-weight: 600; font-size: .85rem; padding: 9px 16px; border-radius: 8px; }
    .cs-btn-white { background: #fff; color: var(--cs-primary); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .cs-btn-outline { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; }
    .cs-btn-outline:hover { background: rgba(255,255,255,.28); color: #fff; }

    .cs-card { background: #fff; border: 1px solid var(--cs-border); border-radius: var(--cs-radius); box-shadow: var(--cs-shadow); overflow: hidden; }
    .cs-card-header { padding: 16px 22px; border-bottom: 1px solid var(--cs-border); background: rgba(103,119,239,.03); }
    .cs-card-title { font-size: 1rem; font-weight: 700; color: var(--cs-text); margin: 0; }

    .cs-mode { display: flex; gap: 12px; }
    .cs-mode-card {
        flex: 1; border: 2px solid var(--cs-border); border-radius: var(--cs-radius);
        padding: 16px 18px; cursor: pointer; transition: all .2s; background: #fff;
        display: flex; align-items: center; gap: 14px;
    }
    .cs-mode-card:hover { border-color: rgba(103,119,239,.4); }
    .cs-mode-card.active { border-color: var(--cs-primary); background: rgba(103,119,239,.05); box-shadow: 0 0 0 3px rgba(103,119,239,.12); }
    .cs-mode-radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #c3cbe2; flex-shrink: 0; position: relative; transition: border-color .2s; }
    .cs-mode-card.active .cs-mode-radio { border-color: var(--cs-primary); }
    .cs-mode-card.active .cs-mode-radio::after {
        content: ''; position: absolute; inset: 3px; border-radius: 50%; background: var(--cs-primary);
    }
    .cs-mode-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
    .cs-mode-icon.indigo { background: rgba(103,119,239,.1); color: var(--cs-primary); }
    .cs-mode-icon.cyan { background: rgba(58,186,244,.1); color: var(--cs-accent); }
    .cs-mode-title { font-weight: 700; color: var(--cs-text); font-size: .95rem; }
    .cs-mode-sub { font-size: .8rem; color: var(--cs-muted); margin-top: 2px; }

    .cs-stat-chip {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(103,119,239,.07); border: 1px solid rgba(103,119,239,.18);
        border-radius: 30px; padding: 7px 16px; font-size: .85rem; font-weight: 600; color: var(--cs-primary);
    }

    .cs-textarea {
        border: 1.5px solid var(--cs-border); border-radius: var(--cs-radius);
        padding: 14px 16px; font-size: .95rem; width: 100%;
        transition: border-color .2s, box-shadow .2s; resize: vertical;
    }
    .cs-textarea:focus { border-color: var(--cs-primary); box-shadow: 0 0 0 3px rgba(103,119,239,.15); outline: none; }

    .cs-counter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .cs-badge {
        font-size: .78rem; font-weight: 700; padding: 5px 12px; border-radius: 20px;
    }
    .cs-badge.ok { background: rgba(71,195,99,.12); color: var(--cs-success); }
    .cs-badge.warn { background: rgba(255,164,38,.14); color: var(--cs-warning); }
    .cs-badge.info { background: rgba(58,186,244,.12); color: var(--cs-accent); }

    .cs-selected-list { max-height: 180px; overflow-y: auto; }
    .cs-selected-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 12px; border-bottom: 1px solid var(--cs-border); font-size: .87rem;
    }
    .cs-selected-item:last-child { border-bottom: none; }

    .cs-hint { font-size: .8rem; color: var(--cs-muted); }
    .cs-hint kbd {
        background: #f1f3f9; border: 1px solid var(--cs-border); border-radius: 4px;
        padding: 1px 6px; font-size: .75rem;
    }
</style>
@endsection

@section('content')
<div class="cs-page">

    <div class="cs-hero">
        <div class="cs-hero-text">
            <h1><i class="fas fa-paper-plane me-2"></i>@lang('models/cardSellers.singular') SMS</h1>
            <p>Send announcements & offers to card seller contacts</p>
        </div>
        <div class="cs-hero-actions">
            <a href="{{ route('cardSellers.index') }}" class="btn cs-btn-outline">
                <i class="fas fa-arrow-left"></i> @lang('crud.back')
            </a>
        </div>
    </div>

    <form id="cs-sms-form" action="{{ route('cardseller.sms') }}" method="POST">
        @csrf
        <input type="hidden" name="mode" id="cs-mode" value="all">
        @foreach($selected as $s)
            <input type="hidden" name="selected_ids[]" value="{{ $s->id }}">
        @endforeach

        <div class="cs-card mb-4">
            <div class="cs-card-header">
                <span class="cs-card-title"><i class="fas fa-users me-2" style="color:var(--cs-primary)"></i>Recipients</span>
                <span class="cs-stat-chip ms-2"><i class="fas fa-layer-group"></i> {{ $totalSellers }} @lang('models/cardSellers.plural')</span>
                <span class="cs-stat-chip ms-2"><i class="fas fa-phone-alt"></i> {{ $withContact }} with contact</span>
            </div>
            <div class="card-body p-4">
                <div class="cs-mode">
                    <div class="cs-mode-card active" id="cs-mode-all" data-mode="all">
                        <div class="cs-mode-radio"></div>
                        <div class="cs-mode-icon indigo"><i class="fas fa-broadcast-tower"></i></div>
                        <div>
                            <div class="cs-mode-title">All card sellers</div>
                            <div class="cs-mode-sub">{{ $withContact }} recipients with a contact number</div>
                        </div>
                    </div>
                    <div class="cs-mode-card" id="cs-mode-selected" data-mode="selected">
                        <div class="cs-mode-radio"></div>
                        <div class="cs-mode-icon cyan"><i class="fas fa-check-double"></i></div>
                        <div>
                            <div class="cs-mode-title">Selected sellers</div>
                            <div class="cs-mode-sub" id="cs-selected-count">{{ $selected->count() }} selected — pick sellers from the list page</div>
                        </div>
                    </div>
                </div>

                @if($selected->count())
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-2 text-muted">Selected recipients</h6>
                        <div class="cs-selected-list border rounded">
                            @foreach($selected as $s)
                                <div class="cs-selected-item">
                                    <span><strong>{{ $s->name }}</strong> <span class="text-muted ms-1">{{ $s->contact }}</span></span>
                                    <span class="text-muted">{{ $s->store_title ?: ($s->address ?: '') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="cs-card mb-4">
            <div class="cs-card-header">
                <span class="cs-card-title"><i class="fas fa-comment-dots me-2" style="color:var(--cs-primary)"></i>Message</span>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="cs-template" class="form-label fw-semibold">Use a template (optional)</label>
                    <select class="form-select" id="cs-template">
                        <option value="">— Select a template —</option>
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}" data-sms="{{ e($t->sms_template) }}">{{ $t->title }}</option>
                        @endforeach
                    </select>
                </div>

                <label for="cs-message" class="form-label fw-semibold">SMS message <span class="text-danger">*</span></label>
                <textarea class="cs-textarea" id="cs-message" name="message" rows="5" maxlength="1000"
                    placeholder="Write your announcement or offer here, e.g. 'আপনাকে জানাচ্ছি, কার্ড সেলার হিসেবে আপনার স্টোরে বিশেষ অফার চালু হয়েছে!'"></textarea>

                <div class="mt-2 cs-counter-bar">
                    <span class="cs-badge info" id="cs-badge-chars">0 characters</span>
                    <span class="cs-badge ok" id="cs-badge-parts">1 SMS</span>
                    <span class="cs-badge ok" id="cs-badge-encoding">Unicode</span>
                    <span class="cs-hint ms-auto">Max 1000 characters · Bengali text counts as Unicode (70 chars/SMS)</span>
                </div>

                <div class="mt-4 d-flex align-items-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary px-5 py-2" id="cs-send-btn">
                        <i class="fas fa-paper-plane me-1"></i> Send SMS
                    </button>
                    <span class="cs-hint" id="cs-send-hint">Will be sent to <strong id="cs-recipient-count">{{ $withContact }}</strong> recipient(s)</span>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
$(function () {
    var ALL_CONTACT_COUNT = {{ $withContact }};
    var selectedIds = [];

    // ---- Recipient mode toggle ----
    function setMode(mode) {
        $('#cs-mode').val(mode);
        $('#cs-mode-all').toggleClass('active', mode === 'all');
        $('#cs-mode-selected').toggleClass('active', mode === 'selected');
        updateRecipientHint();
    }

    function updateRecipientHint() {
        var mode = $('#cs-mode').val();
        var count = mode === 'all' ? ALL_CONTACT_COUNT : selectedIds.length;
        $('#cs-recipient-count').text(count);
        $('#cs-selected-count').text(selectedIds.length + ' selected — pick sellers from the list page');
    }

    $('#cs-mode-all').on('click', function () { setMode('all'); });
    $('#cs-mode-selected').on('click', function () { setMode('selected'); });

    // Pre-selected ids passed from the index page
    var initial = $('input[name="selected_ids[]"]').map(function () { return this.value; }).get();
    if (initial.length) {
        selectedIds = initial.map(Number);
        setMode('selected');
    }

    // ---- Template picker ----
    $('#cs-template').on('change', function () {
        var opt = $(this).find('option:selected');
        var sms = opt.data('sms');
        if (sms) {
            $('#cs-message').val(sms).trigger('input');
        }
    });

    // ---- Bengali-aware SMS part counter (mirrors the sms_count() helper) ----
    var GSM7 = "@£\$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";
    var GSM7EX = "^[]{}~\\|€";

    function smsStats(text) {
        var charCount = [...text].length;
        var isGsm = true, isGsmEx = true, exCount = 0;

        for (var i = 0; i < charCount; i++) {
            var ch = text[i];
            if (GSM7.indexOf(ch) === -1) isGsm = false;
            if ((GSM7 + GSM7EX).indexOf(ch) === -1) isGsmEx = false;
            if (GSM7EX.indexOf(ch) !== -1) exCount++;
            if (!isGsm && !isGsmEx) break;
        }

        var encoding, perMessage, length;
        if (isGsm) {
            encoding = 'GSM 7-bit'; perMessage = 160; length = text.length;
        } else if (isGsmEx) {
            encoding = 'GSM 7-bit Ext'; perMessage = 160; length = text.length + exCount;
        } else {
            encoding = 'Unicode'; perMessage = 70; length = charCount;
        }

        var single = perMessage;
        if (length > perMessage) perMessage = (encoding === 'Unicode') ? 67 : 153;
        var messages = length === 0 ? 0 : Math.ceil(length / perMessage);

        return { chars: charCount, messages: messages, encoding: encoding, single: single };
    }

    function refreshCounter() {
        var text = $('#cs-message').val();
        var stats = smsStats(text);
        $('#cs-badge-chars').text(stats.chars + ' characters');
        $('#cs-badge-parts').text(stats.messages + ' SMS');
        $('#cs-badge-encoding').text(stats.encoding);

        var partsBadge = $('#cs-badge-parts');
        partsBadge.removeClass('ok warn');
        if (stats.messages === 0 || stats.messages > 10) partsBadge.addClass('warn'); else partsBadge.addClass('ok');
    }

    $('#cs-message').on('input', refreshCounter);
    refreshCounter();

    // ---- Submit ----
    $('#cs-sms-form').on('submit', function (e) {
        var text = $('#cs-message').val().trim();
        if (!text) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Empty message', text: 'Please write a message before sending.' });
            return;
        }

        var mode = $('#cs-mode').val();
        var count = mode === 'all' ? ALL_CONTACT_COUNT : selectedIds.length;
        if (count === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'No recipients', text: 'No card sellers with contact numbers found for this selection.' });
            return;
        }

        var stats = smsStats(text);
        Swal.fire({
            title: 'Confirm SMS',
            html: 'Send <strong>' + stats.messages + ' SMS</strong> (' + stats.encoding + ') to <strong>' + count + ' card seller' + (count > 1 ? 's' : '') + '</strong>?<br><small class="text-muted">' + stats.chars + ' characters · ' + (count * stats.messages) + ' total messages</small>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6777ef',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, send it'
        }).then(function (result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sending SMS...',
                    text: 'Dispatching to ' + count + ' recipient(s). Please wait.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: function () { Swal.showLoading(); }
                });
                $('#cs-sms-form').off('submit').submit();
            }
        });
    });
});
</script>
@endsection
