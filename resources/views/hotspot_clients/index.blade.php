@extends('layouts.app')
@section('title')
    Hotspot Clients
@endsection
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Hotspot Clients</h1>
            <div class="section-header-breadcrumb m-0">
                <a href="{{ route('hotspotClients.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-plus me-2"></i> Add New Client
                </a>
            </div>
        </div>

        <div class="section-body">
            @include('hotspot_clients.table')
        </div>
    </section>

    {{-- Custom Styles for Premium Look --}}
    <style>
        .section-header {
            box-shadow: 0 4px 8px rgba(0,0,0,0.03);
            border-radius: 10px;
            padding: 20px 30px;
            background: #fff;
            margin-bottom: 30px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important;
        }
    </style>
@endsection
