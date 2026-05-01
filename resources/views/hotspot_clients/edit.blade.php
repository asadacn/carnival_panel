@extends('layouts.app')
@section('title')
    Edit Hotspot Client
@endsection
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0 text-dark">Edit Hotspot Client</h1>
            <div class="section-header-breadcrumb m-0">
                <a href="{{ route('hotspotClients.index') }}" class="btn btn-light shadow-sm rounded-pill px-4 border font-weight-bold">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
        
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h5 class="text-warning mb-0"><i class="fas fa-user-edit me-2"></i> Client Information</h5>
                            </div>
                            <div class="card-body p-4">
                                {!! Form::model($hotspotClient, ['route' => ['hotspotClients.update', $hotspotClient->id], 'method' => 'patch', 'class' => 'needs-validation']) !!}
                                    <div class="row g-4">
                                        @include('hotspot_clients.fields')
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .section-header {
            box-shadow: 0 4px 8px rgba(0,0,0,0.02);
            border-radius: 10px;
            padding: 20px 30px;
            background: #fff;
        }
        .btn-light {
            background-color: #fff;
            color: #6c757d;
        }
        .btn-light:hover {
            background-color: #f8f9fa;
            color: #495057;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
            transition: all 0.3s ease;
        }
    </style>
@endsection
