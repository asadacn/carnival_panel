@extends('layouts.app')

@section('title')
    Payment History - {{ $client->name }}
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Payment History - {{ $client->name }}</h1>
        <div class="section-header-button">
            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Client
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Due</h6>
                        <h3>৳ {{ number_format($totalDue, 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Paid</h6>
                        <h3>৳ {{ number_format($totalPaid, 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Payment Count</h6>
                        <h3>{{ $payments->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>Payment Records</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment Date</th>
                                <th>Bill Month</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Transaction ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                                    <td>{{ $payment->dueBill->month_year ?? '-' }}</td>
                                    <td>৳ {{ number_format($payment->amount, 2) }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($payment->payment_method) }}</span></td>
                                    <td>{{ $payment->transaction_id ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('due-bill-payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No payment records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
