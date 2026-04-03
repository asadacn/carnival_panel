@extends('layouts.app')
@section('title')
    @lang('models/clients.singular')  @lang('crud.details')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/clients.singular') @lang('crud.details')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('clients.bills-statement-pdf', $client->id) }}" class="btn btn-info form-btn" target="_blank" title="Download Bill Statement">
                    <i class="fas fa-file-pdf"></i> Bill Statement (PDF)
                </a>
                <a href="{{ route('clients.payments-statement-pdf', $client->id) }}" class="btn btn-success form-btn" target="_blank" title="Download Payment Statement">
                    <i class="fas fa-file-pdf"></i> Payment Statement (PDF)
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-primary form-btn float-right">
                    @lang('crud.back')
                </a>
            </div>
        </div>
   @include('stisla-templates::common.errors')
    <div class="section-body">
           <div class="card">
            <div class="card-body">
                    @include('clients.show_fields')
            </div>
            </div>
    </div>
    </section>
@endsection

