@extends('layouts.app')
@section('title')
    @lang('clients.import') @lang('models/clients.singular')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">@lang('clients.import') @lang('models/clients.singular')</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('clients.index') }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body ">
                            <form class="row" action="{{ route('clients.import') }}" enctype="multipart/form-data"
                            method="POST">
                            @csrf
                            <div class="col-8">
                                <input class="form-control" name="clients_file" type="file" accept=".xls,.xlsx,.csv"
                                    id="formFile">
                            </div>

                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Import</button>
                            </div>
                        </form>
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection




