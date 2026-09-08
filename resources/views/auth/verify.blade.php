@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7" style="margin-top: 2%">
                <div class="box">
                    <h3 class="box-title" style="padding: 2%">@lang('messages.verify_email_title')</h3>

                    <div class="box-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">@lang('messages.verification_link_sent')</div>
                        @endif
                        <p>@lang('messages.verify_email_instruction')</p>
                        <a href="{{ route('verification.resend') }}">@lang('messages.click_here_resend')</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection