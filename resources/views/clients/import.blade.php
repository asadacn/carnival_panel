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
                        <div class="card-body">

                            <form class="row" action="{{ route('clients.import') }}"
                                  enctype="multipart/form-data" method="POST">
                                @csrf

                                {{-- ISP SELECT --}}
                                <div class="col-md-4 mb-2">
                                    <label class="font-weight-bold">ISP নির্বাচন করুন</label>
                                    <select name="isp_code" class="form-control" required>
                                        <option value="">-- ISP Select --</option>
                                        <option value="carnival">Carnival Internet</option>
                                        <option value="bijoy">Bijoy ISP</option>
                                    </select>
                                </div>

                                {{-- FILE --}}
                                <div class="col-md-5 mb-2">
                                    <label class="font-weight-bold">Client File</label>
                                    <input class="form-control" name="clients_file"
                                           type="file" accept=".xls,.xlsx,.csv" required>
                                </div>

                                {{-- BUTTON --}}
                                <div class="col-md-3 mb-2 d-flex align-items-end">
                                    <button id="import" type="submit"
                                            class="btn btn-primary btn-block">
                                        Import
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

            @if(session('debug_logs'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Debug Logs</h4>
                        </div>
                        <div class="card-body">
                            <ul>
                                @foreach(session('debug_logs') as $log)
                                <li>{{ $log }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$('#import').on('click', function () {
    Swal.showLoading();
});
</script>
@endsection
