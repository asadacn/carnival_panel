@extends('layouts.app')
@section('title') Edit Due Bill @endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Due Bill</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('due-bills.update', $bill->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label>Bill Date</label>
                                <input type="date" name="bill_date" class="form-control" value="{{ $bill->bill_date }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Due Date</label>
                                <input type="date" name="due_date" class="form-control" value="{{ $bill->due_date }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Amount (৳)</label>
                                <input type="number" name="amount" class="form-control" value="{{ $bill->amount }}" step="0.01" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="unpaid" @if($bill->status == 'unpaid') selected @endif>Unpaid</option>
                                    <option value="partially_paid" @if($bill->status == 'partially_paid') selected @endif>Partially Paid</option>
                                    <option value="paid" @if($bill->status == 'paid') selected @endif>Paid</option>
                                    <option value="overdue" @if($bill->status == 'overdue') selected @endif>Overdue</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $bill->notes }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                            <a href="{{ route('due-bills.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
