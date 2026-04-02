@extends('layouts.app')

@section('title')
    Due Bills - {{ $client->name }}
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Due Bills for {{ $client->name }}</h1>
        <div class="section-header-button">
            <a href="{{ route('due-bill-payments.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Payment
            </a>
            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Client
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Billing Summary</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted">Total Bills</h6>
                                <h3>{{ $bills->total() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted">Total Amount</h6>
                                <h3>৳ {{ number_format($bills->sum('amount'), 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted">Total Paid</h6>
                                <h3>৳ {{ number_format($bills->sum('paid_amount'), 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted">Total Remaining</h6>
                                <h3 class="text-danger">৳ {{ number_format($bills->sum(function($b) { return $b->amount - $b->paid_amount; }), 0) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>Bills List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month/Year</th>
                                <th>Bill Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }}</strong></td>
                                    <td>{{ $bill->bill_date->format('d-m-Y') }}</td>
                                    <td>
                                        {{ $bill->due_date->format('d-m-Y') }}
                                        @if($bill->is_overdue)
                                            <span class="badge bg-danger">OVERDUE</span>
                                        @endif
                                    </td>
                                    <td>৳ {{ number_format($bill->amount, 2) }}</td>
                                    <td>৳ {{ number_format($bill->paid_amount, 2) }}</td>
                                    <td>৳ {{ number_format($bill->remaining_balance, 2) }}</td>
                                    <td>
                                        @if($bill->status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($bill->status === 'partially_paid')
                                            <span class="badge bg-warning">Partially Paid</span>
                                        @elseif($bill->status === 'overdue')
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('due-bills.show', $bill->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('due-bill-payments.create', ['bill_id' => $bill->id, 'client_id' => $client->id]) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i> Payment
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No bills found for this client
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
