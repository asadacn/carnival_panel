@extends('layouts.app')
@section('title') Edit Payment @endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Payment</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('due-bill-payments.update', $payment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Amount (৳)</label>
                                <input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" step="0.01" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cash" @if($payment->payment_method == 'cash') selected @endif>Cash</option>
                                    <option value="bkash" @if($payment->payment_method == 'bkash') selected @endif>bKash</option>
                                    <option value="nagad" @if($payment->payment_method == 'nagad') selected @endif>Nagad</option>
                                    <option value="bank" @if($payment->payment_method == 'bank') selected @endif>Bank</option>
                                    <option value="other" @if($payment->payment_method == 'other') selected @endif>Other</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Transaction ID</label>
                                <input type="text" name="transaction_id" class="form-control" value="{{ $payment->transaction_id }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $payment->notes }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                            <a href="{{ route('due-bill-payments.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
