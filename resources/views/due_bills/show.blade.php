@extends('layouts.app')

@section('title')
    Due Bill Details
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Due Bill Details</h1>
        <div class="section-header-button">
            <a href="{{ route('due-bills.edit', $bill->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('due-bills.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Bill Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Client:</strong> {{ $bill->client->name ?? '-' }}</p>
                                <p><strong>Bill Date:</strong> {{ $bill->bill_date->format('d-m-Y') }}</p>
                                <p><strong>Due Date:</strong> {{ $bill->due_date->format('d-m-Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Month/Year:</strong> {{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }}</p>
                                <p>
                                    <strong>Status:</strong>
                                    @if($bill->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->status === 'partially_paid')
                                        <span class="badge bg-warning">Partially Paid</span>
                                    @elseif($bill->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-secondary">Unpaid</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Total Amount</h6>
                                        <h4>৳ {{ number_format($bill->amount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Paid Amount</h6>
                                        <h4>৳ {{ number_format($bill->paid_amount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Remaining</h6>
                                        <h4 class="text-danger">৳ {{ number_format($bill->remaining_balance, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Payment %</h6>
                                        <h4>{{ $bill->payment_percentage }}%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($bill->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong>
                                <p>{{ $bill->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('due-bill-payments.create', ['bill_id' => $bill->id, 'client_id' => $bill->client_id]) }}" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-plus"></i> Add Payment
                        </a>
                        <button onclick="markAsPaid({{ $bill->id }})" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-check"></i> Mark as Paid
                        </button>
                        <button onclick="deleteBill({{ $bill->id }})" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($bill->payments->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h4>Payments History</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Transaction ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bill->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                                <td>৳ {{ number_format($payment->amount, 2) }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($payment->payment_method) }}</span></td>
                                <td>{{ $payment->transaction_id ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deletePayment({{ $payment->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<script>
    function markAsPaid(billId) {
        if(!confirm('Mark this bill as paid?')) return;
        window.location.href = `/due-bills/${billId}/mark-paid`;
    }

    function deleteBill(billId) {
        if(!confirm('Delete this bill?')) return;
        // AJAX delete
    }

    function deletePayment(paymentId) {
        if(!confirm('Delete this payment?')) return;
        // AJAX delete
    }
</script>
@endsection
