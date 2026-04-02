@extends('layouts.app')
@section('title') Payment Details @endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Payment Details</h1>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <p><strong>Client:</strong> {{ $payment->client->name }}</p>
                <p><strong>Bill:</strong> {{ $payment->dueBill->month_year }}</p>
                <p><strong>Amount:</strong> ৳ {{ number_format($payment->amount, 2) }}</p>
                <p><strong>Payment Date:</strong> {{ $payment->payment_date->format('d-m-Y') }}</p>
                <p><strong>Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
                <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? '-' }}</p>
                @if($payment->notes)
                <p><strong>Notes:</strong> {{ $payment->notes }}</p>
                @endif
                <a href="{{ route('due-bill-payments.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
</section>
@endsection
