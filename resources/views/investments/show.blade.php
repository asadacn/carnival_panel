@extends('layouts.app')
@section('title')
    @lang('models/investments.singular') Details
@endsection
@section('content')
    <section class="section">
        <div class="modern-section-header mb-4 mt-2 shadow-sm">
            <div class="header-content">
                <div class="header-icon bg-gradient-info">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="header-text">
                    <h3 class="header-title">Investment Record</h3>
                    <p class="header-subtitle">Viewing details for "{{ $investment->invested_by ?: 'this investment' }}"</p>
                </div>
                <div class="ms-auto" style="margin-left: auto; margin-right: 105px;">
                    <a href="{{ route('investments.index') }}" class="btn btn-smooth-vibe px-4 py-2" style="border-radius: 50px; font-weight: 600; font-size: 1.1rem; white-space: nowrap; display: inline-flex; align-items: center;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Ledger
                    </a>
                </div>
            </div>
        </div>
        
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-white border-bottom pb-3 pt-4">
                        <h5 class="mb-0 fw-bold" style="color: #1e293b;"><i class="fas fa-list-alt me-2 text-info"></i>Detailed Information</h5>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="row">
                            @include('investments.show_fields')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <style>
        .modern-section-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 12px;
            padding: 24px 28px;
            border: 1px solid #e2e8f0;
        }

        .header-content {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 20px;
            flex-wrap: wrap;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }

        .header-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        }

        .header-text h3.header-title {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .header-text p.header-subtitle {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: 13px;
        }

        .btn-smooth-vibe {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.2);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .btn-smooth-vibe:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
            filter: brightness(1.1);
        }
    </style>
@endsection
