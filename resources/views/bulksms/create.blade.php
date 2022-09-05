@extends('layouts.app')
@section('title')
   Bulk SMS
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">Bulk SMS</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body ">

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
<script> $('#sms-body').countSms('#sms-counter')</script>
@endsection

