@extends('layouts.app')
@section('title') Payment Report @endsection

@section('css')
<style>
    .report-container { padding: 2rem 0; }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .page-title {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        white-space: nowrap;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 108px;
        color: #1f2937;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        height: 100%;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 26px -8px rgba(0,0,0,.14);
        border-color: var(--metric-accent, #6366f1);
    }

    .summary-card::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -20%;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--metric-soft, rgba(99,102,241,.06));
        filter: blur(12px);
        pointer-events: none;
    }

    .summary-content {
        flex: 1;
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .summary-content h6 {
        font-weight: 700;
        font-size: 0.73rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.96;
        margin-bottom: 0.45rem;
    }

    .summary-content h3 {
        font-weight: 800;
        font-size: 1.45rem;
        line-height: 1.2;
        margin: 0;
        color: #111827;
        white-space: nowrap;
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--metric-soft, rgba(99,102,241,.08));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--metric-accent, #6366f1);
        position: relative;
        z-index: 1;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="report-container">
        <div class="page-header">
            <h1 class="page-title">
                <i data-lucide="bar-chart-3" style="width:24px; height:24px; color:#3b82f6;"></i>
                <div>
                    Payments Report
                    <div id="report-subtitle" style="font-size: 0.82rem; font-weight: 500; color: #64748b; margin-top: 2px;">
                        Showing all payment records
                    </div>
                </div>
            </h1>
            <div class="header-actions">
                <a href="{{ route('due-bill-payments.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; padding: 0.45rem 0.9rem;">
                    <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="summary-card" style="--metric-accent:#3b82f6; --metric-soft:rgba(59,130,246,.10);">
                    <div class="summary-content">
                        <h6>Total Amount</h6>
                        <h3>৳ {{ number_format($totalAmount, 0) }}</h3>
                    </div>
                    <div class="summary-icon">
                        <i data-lucide="receipt" style="width:24px; height:24px;"></i>
                    </div>
                </div>
            </div>
            @foreach($byMethod as $method => $amount)
                <div class="col-md-3">
                    <div class="summary-card" style="--metric-accent:#10b981; --metric-soft:rgba(16,185,129,.10);">
                        <div class="summary-content">
                            <h6>{{ ucfirst($method) }}</h6>
                            <h3>৳ {{ number_format($amount, 0) }}</h3>
                        </div>
                        <div class="summary-icon">
                            <i data-lucide="wallet" style="width:24px; height:24px;"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
