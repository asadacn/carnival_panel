@extends('layouts.app')
@section('title')
     @lang('models/hotspotClients.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/hotspotClients.plural')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('hotspotClients.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('hotspot_clients.table')
            </div>
       </div>
   </div>
    
    </section>
@endsection



