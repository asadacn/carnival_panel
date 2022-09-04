@extends('layouts.app')
@section('title')
     @lang('models/hotspotZones.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/hotspotZones.plural')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('hotspotZones.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('hotspot_zones.table')
            </div>
       </div>
   </div>

    </section>

    <script>

        function copyText(obj)
        {
            var tmpInput = $("<input>");
            $("body").append(tmpInput);
            var tdVal = $(obj).parent().text();
            tmpInput.val(tdVal).select();
            document.execCommand("copy");
            tmpInput.remove();
            alert(tdVal + "ONU mac copied to clipboard");
        }

        </script>
@endsection



