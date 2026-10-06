@extends('layouts.app')
@section('title')
    {{ $cardSeller->name }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/cardSellers.singular') @lang('crud.details')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('cardSellers.index') }}" class="btn btn-primary form-btn float-right">@lang('crud.back')</a>
            </div>
        </div>
        @include('stisla-templates::common.errors')
        <div class="section-body">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, #6777ef 0%, #3abaf4 100%); font-size: 2.2rem; color: #fff; font-weight: 700;">
                                {{ mb_strtoupper(mb_substr($cardSeller->name, 0, 1)) }}
                            </div>
                            <h4 class="mb-1">{{ $cardSeller->name }}</h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-store me-1"></i>{{ $cardSeller->store_title ?: '-' }}
                            </p>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary btn-sm me-2" onclick="sendCardSellerSms({{ $cardSeller->id }}, '{{ addslashes($cardSeller->name) }}')">
                                    <i class="fas fa-paper-plane me-1"></i>Send SMS
                                </button>
                                <a href="{{ route('cardSellers.edit', $cardSeller->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>@lang('crud.edit')
                                </a>
                                <a href="{{ route('cardSellers.index') }}" class="btn btn-light btn-sm">@lang('crud.back')</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            @include('card_sellers.show_fields')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
// Single-seller SMS composer (same UX as the index page)
window.sendCardSellerSms = function (id, name) {
    Swal.fire({
        title: 'SMS to ' + name,
        html:
            '<textarea id="swal-sms-text" class="cs-textarea" rows="4" maxlength="1000" ' +
            'placeholder="Write announcement or offer..."></textarea>' +
            '<div class="mt-2 text-start"><span class="cs-badge info" id="swal-sms-chars">0 characters</span> ' +
            '<span class="cs-badge ok" id="swal-sms-parts">1 SMS</span></div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6777ef',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Send SMS',
        didOpen: function () {
            var ta = $('#swal-sms-text');
            ta.on('input', function () {
                var text = ta.val();
                var chars = [...text].length;
                var isUnicode = /[^\x00-\x7F]/.test(text);
                var per = isUnicode ? 70 : 160;
                var multi = isUnicode ? 67 : 153;
                var parts = chars === 0 ? 0 : (chars <= per ? 1 : Math.ceil(chars / multi));
                $('#swal-sms-chars').text(chars + ' characters');
                $('#swal-sms-parts').text(parts + ' SMS');
            });
        },
        preConfirm: function () {
            var text = $('#swal-sms-text').val().trim();
            if (!text) {
                Swal.showValidationMessage('Please write a message first.');
                return false;
            }
            return { text: text };
        }
    }).then(function (result) {
        if (!result.isConfirmed || !result.value) return;

        Swal.fire({
            title: 'Sending SMS...',
            text: 'Sending to ' + name + '.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: function () { Swal.showLoading(); }
        });

        $.ajax({
            url: '{{ url('cardSellers') }}/' + id + '/sms',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                card_seller_id: id,
                sms: result.value.text
            },
            success: function (res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Sent!', text: res.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'SMS sending failed.' });
                }
            },
            error: function (xhr) {
                var msg = 'SMS sending failed.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Failed', text: msg });
            }
        });
    });
};
</script>
@endsection
