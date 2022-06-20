@extends('layouts.app')
@section('title')
    @lang('cardseller.import') @lang('models/cardseller.singular')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">@lang('cardseller.import') @lang('models/cardseller.singular')</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('cardseller.index') }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body ">
                            <form class="row" action="{{ route('cardseller.import') }}" enctype="multipart/form-data"
                            method="POST">
                            @csrf
                            <div class="col-8">
                                <input class="form-control" name="cardseller_file" type="file" accept=".xls,.xlsx,.csv"
                                    id="formFile">
                            </div>

                            <div class="col-4">
                                <button id="import" type="submit" class="btn btn-primary btn-block">Import</button>
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

@section('scripts')
<script>
$('#import').on('click',function(){
    Swal.showLoading();
})
</script>
@endsection


