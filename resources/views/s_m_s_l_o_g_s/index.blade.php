@extends('layouts.app')
@section('title')
     @lang('models/sMSLOGS.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/sMSLOGS.plural')</h1>
            <div class="section-header-breadcrumb">
                {{-- <a href="{{ route('sMSLOGS.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a> --}}
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('s_m_s_l_o_g_s.table')
            </div>
       </div>
   </div>

    </section>
@endsection



