@extends('layouts.app')
@section('title')
    @lang('crud.edit') @lang('models/sMSTEMPALTES.singular')
@endsection
@section('content')
    <section class="section">
            <div class="section-header">
                <h3 class="page__heading m-0">@lang('crud.edit') @lang('models/sMSTEMPALTES.singular')</h3>
                <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                    <a href="{{ route('sMSTEMPALTES.index') }}"  class="btn btn-primary">@lang('crud.back')</a>
                </div>
            </div>
  <div class="content">
              @include('stisla-templates::common.errors')
              <div class="section-body">
                 <div class="row">
                     <div class="col-lg-12">
                         <div class="card">
                             <div class="card-body ">
                                    {!! Form::model($sMSTEMPALTE, ['route' => ['sMSTEMPALTES.update', $sMSTEMPALTE->id], 'method' => 'patch', 'id'=>'sms_form']) !!}
                                        <div class="row">
                                            @include('s_m_s__t_e_m_p_a_l_t_e_s.fields')
                                        </div>

                                    {!! Form::close() !!}
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
