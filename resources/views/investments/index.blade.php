@extends('layouts.app')
@section('title')
    @lang('models/investments.plural') Dashboard
@endsection
@section('content')
    <section class="section">
        <!-- Modern Section Header -->
        <div class="modern-section-header mb-4 mt-2 shadow-sm">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="header-text">
                    <h3 class="header-title">Investments Dashboard</h3>
                    <p class="header-subtitle">Overview of financial assets, capital allocations, and investor portfolios</p>
                </div>
                <div class="ms-auto" style="margin-left: auto; margin-right: 105px;">
                    <a href="{{ route('investments.create')}}" class="btn btn-smooth-vibe px-4 py-2" style="border-radius: 50px; font-weight: 600; font-size: 1.1rem; white-space: nowrap; display: inline-flex; align-items: center;">
                        <i class="fas fa-plus me-2"></i> New Investment
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div id="dashboard-section" class="mb-4">
            <!-- Top Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="db-kpi-card" style="--kpi-accent:#1d4ed8; --kpi-soft:rgba(59,130,246,.10);">
                        <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(59,130,246,.10)); color: var(--kpi-accent, #1d4ed8);">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">Total Invested</span>
                            <span class="db-kpi-value">৳ {{ number_format($totalInvestment, 0) }}</span>
                        </div>
                        <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="db-kpi-card" style="--kpi-accent:#10b981; --kpi-soft:rgba(16,185,129,.10);">
                        <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(16,185,129,.10)); color: var(--kpi-accent, #10b981);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">This Month</span>
                            <span class="db-kpi-value">৳ {{ number_format($thisMonthInvestment, 0) }}</span>
                        </div>
                        <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="db-kpi-card" style="--kpi-accent:#8b5cf6; --kpi-soft:rgba(139,92,246,.10);">
                        <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(139,92,246,.10)); color: var(--kpi-accent, #8b5cf6);">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="db-kpi-body">
                            <span class="db-kpi-label">This Year</span>
                            <span class="db-kpi-value">৳ {{ number_format($thisYearInvestment, 0) }}</span>
                        </div>
                        <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </div>

            <!-- Distribution Row -->
            <div class="row">
                <!-- Investment By Type -->
                <div class="col-lg-6 mb-3">
                    <div class="card shadow-sm border-0 h-100 distribution-card">
                        <div class="card-header bg-white border-bottom pb-2 pt-3">
                            <h6 class="text-uppercase mb-0 fw-bold text-secondary" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                <i class="fas fa-tags me-1 text-primary"></i> Portfolio by Type
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            @if(count($investmentsByType) > 0)
                                @php
                                    // Colors for types
                                    $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];
                                @endphp
                                @foreach($investmentsByType as $index => $typeData)
                                    @php
                                        $percentage = $totalInvestment > 0 ? round(($typeData->total / $totalInvestment) * 100, 1) : 0;
                                        $color = $colors[$index % count($colors)];
                                    @endphp
                                    <div class="mb-3 distribution-item">
                                        <div class="d-flex justify-content-between align-items-end mb-1">
                                            <span class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $typeData->type ?: 'Uncategorized' }}</span>
                                            <div class="text-end">
                                                <span class="fw-bold" style="font-size: 0.85rem; color: {{ $color }};">৳ {{ number_format($typeData->total, 0) }}</span>
                                                <small class="text-muted ms-1">({{ $percentage }}%)</small>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 6px; border-radius: 4px; background-color: #f1f5f9;">
                                            <div class="progress-bar progress-fill" role="progressbar" data-width="{{ $percentage }}" style="width: 0%; background-color: {{ $color }}; border-radius: 4px; transition: width 1s ease-in-out;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted p-4">
                                    <i class="fas fa-chart-bar fs-3 mb-2" style="opacity: 0.5;"></i>
                                    <p class="mb-0" style="font-size: 0.85rem;">No category data available yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Investment By Investor -->
                <div class="col-lg-6 mb-3">
                    <div class="card shadow-sm border-0 h-100 distribution-card">
                        <div class="card-header bg-white border-bottom pb-2 pt-3">
                            <h6 class="text-uppercase mb-0 fw-bold text-secondary" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                <i class="fas fa-users me-1 text-success"></i> Top Investors
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            @if(count($investmentsByInvestor) > 0)
                                @php
                                    $investorColors = ['#0ea5e9', '#84cc16', '#f97316', '#a855f7', '#ec4899', '#14b8a6', '#64748b'];
                                @endphp
                                @foreach($investmentsByInvestor->take(6) as $index => $investorData)
                                    @php
                                        $percentage = $totalInvestment > 0 ? round(($investorData->total / $totalInvestment) * 100, 1) : 0;
                                        $color = $investorColors[$index % count($investorColors)];
                                    @endphp
                                    <div class="mb-3 distribution-item">
                                        <div class="d-flex justify-content-between align-items-end mb-1">
                                            <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><i class="far fa-user-circle me-1" style="color: {{ $color }}; opacity: 0.8;"></i> {{ $investorData->invested_by ?: 'Anonymous' }}</span>
                                            <div class="text-end">
                                                <span class="fw-bold" style="font-size: 0.85rem; color: {{ $color }};">৳ {{ number_format($investorData->total, 0) }}</span>
                                                <small class="text-muted ms-1">({{ $percentage }}%)</small>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 6px; border-radius: 4px; background-color: #f1f5f9;">
                                            <div class="progress-bar progress-fill" role="progressbar" data-width="{{ $percentage }}" style="width: 0%; background-color: {{ $color }}; border-radius: 4px; transition: width 1s ease-in-out;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted p-4">
                                    <i class="fas fa-user-slash fs-3 mb-2" style="opacity: 0.5;"></i>
                                    <p class="mb-0" style="font-size: 0.85rem;">No investor data available yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="section-body mt-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom pb-3 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">Investment Ledger</h5>
                </div>
                <div class="card-body p-0">
                    @include('investments.table')
                </div>
            </div>
        </div>
    </section>

    <!-- UI Customizations -->
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

        .header-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
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

        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .bg-primary-light { background-color: #eff6ff; }
        .text-primary { color: #3b82f6 !important; }

        .bg-success-light { background-color: #ecfdf5; }
        .text-success { color: #10b981 !important; }

        .bg-purple-light { background-color: #f5f3ff; }
        .text-purple { color: #8b5cf6 !important; }

        .distribution-card {
            border-radius: 12px;
            transition: all 0.2s ease;
            border: 1px solid #f1f5f9;
        }

        .distribution-item {
            padding: 6px;
            border-radius: 6px;
            transition: background 0.2s ease;
        }

        .distribution-item:hover {
            background-color: #f8fafc;
        }

        .btn-smooth-vibe {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .btn-smooth-vibe:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            filter: brightness(1.1);
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Animate progress bars on load
        $(document).ready(function() {
            setTimeout(function() {
                $('.progress-fill').each(function() {
                    $(this).css('width', $(this).data('width') + '%');
                });
            }, 300);

            // Initialize DataTable if table ID exists
            if($('#investments-table').length) {
                $('#investments-table').DataTable({
                    pageLength: 10,
                    responsive: true,
                    order: [[4, 'desc']], // Descending by date
                    columnDefs: [
                        { orderable: false, targets: [5] } // Disable sorting on Action column
                    ]
                });
            }
        });
    </script>
@endsection
