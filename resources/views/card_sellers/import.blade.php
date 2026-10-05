@extends('layouts.app')
@section('title')
    @lang('crud.import') @lang('models/cardSellers.singular')
@endsection

@section('page_css')
<style>
    .cs-dropzone {
        border: 2px dashed #c3cbe2;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f8f9fe;
        transition: all .2s ease;
        cursor: pointer;
    }
    .cs-dropzone:hover, .cs-dropzone.dragover {
        border-color: #6777ef;
        background: rgba(103, 119, 239, .06);
    }
    .cs-dropzone .dz-icon {
        width: 60px; height: 60px; margin: 0 auto 12px;
        border-radius: 50%; background: rgba(103,119,239,.12);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #6777ef;
    }
    .cs-dropzone input[type="file"] { display: none; }
</style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">@lang('crud.import') @lang('models/cardSellers.singular')</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('cardSellers.index') }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body">
                            <form id="cardseller-import-form" class="row" action="{{ route('cardseller.import') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="col-12">
                                    <label class="dz-label" for="cardseller_file">
                                        <div class="cs-dropzone" id="cs-dropzone">
                                            <div class="dz-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                            <h5 class="mb-1">@lang('crud.import') @lang('models/cardSellers.singular')</h5>
                                            <p class="text-muted mb-0">Drag & drop your file here, or click to browse</p>
                                            <p class="text-muted small mb-0 mt-2">.xlsx, .xls, .csv (max 10 MB)</p>
                                        </div>
                                    </label>
                                    <input class="form-control" name="cardseller_file" type="file" accept=".xls,.xlsx,.csv" id="cardseller_file">
                                    <div id="cs-file-name" class="text-primary mt-2 fw-semibold"></div>
                                </div>

                                <div class="col-12 mt-3 d-flex align-items-center flex-wrap gap-2">
                                    <button id="import" type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-file-import me-1"></i>@lang('crud.import')
                                    </button>
                                    <a href="{{ route('cardseller.template') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-download me-1"></i>Download Sample Template
                                    </a>
                                </div>
                            </form>

                            <hr class="my-4">

                            <div class="alert alert-light border py-3">
                                <h6 class="mb-2"><i class="fas fa-info-circle text-primary me-1"></i>Expected columns</h6>
                                <p class="mb-1 small">
                                    <span class="badge bg-primary me-1">Retailer Name</span>
                                    <span class="badge bg-primary me-1">Contact Number</span>
                                    <span class="badge bg-primary me-1">Shop Name</span>
                                    <span class="badge bg-primary me-1">Shop Address</span>
                                    <span class="badge bg-primary me-1">Village</span>
                                    <span class="badge bg-primary me-1">Union</span>
                                    <span class="badge bg-primary me-1">Upzila/Area</span>
                                    <span class="badge bg-primary me-1">District</span>
                                    <span class="badge bg-primary me-1">Division</span>
                                </p>
                                <p class="mb-0 small text-muted">Address columns are combined into one address. Rows are matched by contact number — existing sellers get updated, new ones are created.</p>
                            </div>

                            @if(session('import_skipped') || session('import_errors'))
                                <div class="mt-3">
                                    @if(session('import_skipped'))
                                        <div class="alert alert-warning mb-2">
                                            <strong>Skipped rows:</strong>
                                            <ul class="mb-0 pl-4">
                                                @foreach(session('import_skipped') as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(session('import_errors'))
                                        <div class="alert alert-danger mb-2">
                                            <strong>Failed rows:</strong>
                                            <ul class="mb-0 pl-4">
                                                @foreach(session('import_errors') as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif
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
$(function () {
    var dz = $('#cs-dropzone');
    var fileInput = $('#cardseller_file');

    dz.on('click', function () { fileInput.trigger('click'); });

    fileInput.on('change', function () {
        var name = this.files.length ? this.files[0].name : '';
        $('#cs-file-name').text(name ? 'Selected: ' + name : '');
    });

    ['dragenter', 'dragover'].forEach(function (evt) {
        dz.on(evt, function (e) { e.preventDefault(); dz.addClass('dragover'); });
    });
    ['dragleave', 'drop'].forEach(function (evt) {
        dz.on(evt, function (e) { e.preventDefault(); dz.removeClass('dragover'); });
    });
    dz.on('drop', function (e) {
        var files = e.originalEvent.dataTransfer.files;
        if (files.length) {
            fileInput[0].files = files;
            $('#cs-file-name').text('Selected: ' + files[0].name);
        }
    });

    $('form').on('submit', function () {
        Swal.fire({
            title: 'Importing Card Sellers...',
            text: 'Please wait while file is being processed.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function () { Swal.showLoading(); }
        });
    });
});
</script>
@endsection
