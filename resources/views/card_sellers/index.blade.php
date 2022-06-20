@extends('layouts.app')
@section('title')
    @lang('models/cardSellers.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/cardSellers.plural')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('cardSellers.create') }}" class="btn btn-primary form-btn">@lang('crud.add_new')<i
                        class="fas fa-plus"></i></a>
                <a href="{{ route('cardseller.export') }}" class="mx-2 btn btn-primary form-btn">@lang('crud.export')<i
                        class="fas fa-file-export"></i></a>
                <a href="{{ route('cardseller.import.create') }}" class="mx-2 btn btn-primary form-btn">@lang('crud.import')<i
                        class="fas fa-file-import"></i></a>
                <a href="{{ route('cardseller.erase') }}" class="btn btn-danger form-btn">@lang('crud.erase')<i
                        class="fas fa-trash"></i></a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('card_sellers.table')
                </div>
            </div>
        </div>

    </section>
@endsection
