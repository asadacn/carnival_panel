@extends('layouts.app')
@section('title') Payment Details @endsection

@section('css')
<style>
    .payment-detail-container {
        background-color: #f8f9fa;
        padding: 2rem 0;
        min-height: 100vh;
    }

    .payment-detail-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .payment-detail-header {
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
    }

    .payment-detail-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .payment-info-section {
        margin-bottom: 2rem;
    }

    .payment-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .payment-info-item:last-child {
        border-bottom: none;
    }

    .payment-info-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .payment-info-value {
        color: #1f2937;
        font-weight: 600;
        text-align: right;
    }

    .payment-info-value.amount {
        font-size: 1.25rem;
        color: #10b981;
    }

    .payment-badge {
        display: inline-block;
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .payment-badge.cash {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .payment-badge.bkash {
        background-color: #dcfce7;
        color: #15803d;
    }

    .payment-badge.nagad {
        background-color: #fecaca;
        color: #7f1d1d;
    }

    .payment-badge.bank {
        background-color: #fef3c7;
        color: #78350f;
    }

    .payment-badge.other {
        background-color: #e9d5ff;
        color: #581c87;
    }

    .notes-section {
        background-color: #f9fafb;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1.5rem;
    }

    .notes-section p {
        margin: 0;
        color: #374151;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .back-button {
        display: inline-block;
        margin-top: 1.5rem;
        padding: 0.75rem 1.5rem;
        background-color: #e5e7eb;
        color: #374151;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .back-button:hover {
        background-color: #d1d5db;
        transform: translateY(-2px);
    }

    .transaction-id-section {
        background-color: #f0f9ff;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #bfdbfe;
    }

    .transaction-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #1e40af;
    }

    @media (max-width: 768px) {
        .payment-detail-wrapper {
            padding: 1.5rem;
        }

        .payment-info-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .payment-info-value {
            text-align: left;
            margin-top: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="payment-detail-container">
    <div class="payment-detail-wrapper">
        <div class="payment-detail-header">
            <h1><i class="fas fa-receipt"></i> Payment Details</h1>
        </div>

        <div class="payment-info-section">
            <div class="payment-info-item">
                <span class="payment-info-label"><i class="fas fa-user"></i> Client</span>
                <span class="payment-info-value">{{ $payment->client->name }}</span>
            </div>

            <div class="payment-info-item">
                <span class="payment-info-label"><i class="fas fa-file-invoice"></i> Bill Period</span>
                <span class="payment-info-value">{{ $payment->dueBill->month_year }}</span>
            </div>

            <div class="payment-info-item">
                <span class="payment-info-label"><i class="fas fa-money-bill"></i> Amount</span>
                <span class="payment-info-value amount">৳ {{ number_format($payment->amount, 2) }}</span>
            </div>

            <div class="payment-info-item">
                <span class="payment-info-label"><i class="fas fa-calendar"></i> Payment Date</span>
                <span class="payment-info-value">{{ $payment->payment_date->format('d-m-Y') }}</span>
            </div>

            <div class="payment-info-item">
                <span class="payment-info-label"><i class="fas fa-credit-card"></i> Method</span>
                <span class="payment-info-value">
                    <span class="payment-badge {{ strtolower($payment->payment_method) }}">
                        {{ ucfirst($payment->payment_method) }}
                    </span>
                </span>
            </div>

            @if($payment->transaction_id)
            <div class="payment-info-item">
                <span class="payment-info-label"><i class="fas fa-hashtag"></i> Transaction ID</span>
                <span class="payment-info-value transaction-id">{{ $payment->transaction_id }}</span>
            </div>
            @endif
        </div>

        @if($payment->notes)
        <div class="notes-section">
            <strong style="color: #1f2937; margin-bottom: 0.5rem; display: block;">
                <i class="fas fa-sticky-note"></i> Notes
            </strong>
            <p>{{ $payment->notes }}</p>
        </div>
        @endif

        <a href="{{ route('due-bill-payments.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
    </div>
</div>
@endsection
