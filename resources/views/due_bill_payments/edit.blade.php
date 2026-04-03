@extends('layouts.app')
@section('title') Edit Payment @endsection

@section('css')
<style>
    .payment-edit-container {
        background-color: #f8f9fa;
        padding: 2rem 0;
        min-height: 100vh;
    }

    .payment-edit-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .payment-edit-header {
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
    }

    .payment-edit-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 500;
        color: #374151;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.65rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background-color: #fafbfc;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .form-actions .btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .form-actions .btn-success {
        background-color: #10b981;
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .form-actions .btn-success:hover {
        background-color: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .form-actions .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .form-actions .btn-secondary:hover {
        background-color: #d1d5db;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .payment-edit-wrapper {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="payment-edit-container">
    <div class="payment-edit-wrapper">
        <div class="payment-edit-header">
            <h1><i class="fas fa-edit"></i> Edit Payment</h1>
        </div>

        <form action="{{ route('due-bill-payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="payment_date">Payment Date <span style="color: #ef4444;">*</span></label>
                <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ $payment->payment_date }}" required>
                @error('payment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount">Amount (৳) <span style="color: #ef4444;">*</span></label>
                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ $payment->amount }}" step="0.01" min="0.01" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method <span style="color: #ef4444;">*</span></label>
                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                    <option value="">-- Select Method --</option>
                    <option value="cash" @if($payment->payment_method == 'cash') selected @endif>💵 Cash</option>
                    <option value="bkash" @if($payment->payment_method == 'bkash') selected @endif>📱 bKash</option>
                    <option value="nagad" @if($payment->payment_method == 'nagad') selected @endif>📱 Nagad</option>
                    <option value="bank" @if($payment->payment_method == 'bank') selected @endif>🏦 Bank Transfer</option>
                    <option value="other" @if($payment->payment_method == 'other') selected @endif>📋 Other</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="transaction_id">Transaction ID</label>
                <input type="text" name="transaction_id" id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" value="{{ $payment->transaction_id }}" placeholder="e.g., TXN123456 (optional)">
                @error('transaction_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Add any additional notes (optional)">{{ $payment->notes }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Payment
                </button>
                <a href="{{ route('due-bill-payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
