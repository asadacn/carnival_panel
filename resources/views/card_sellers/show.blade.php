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
