@extends('layouts.app')

@section('title')
    Due Bill Details
@endsection

@section('css')
<style>
    .bill-detail-container {
        background-color: #f8f9fa;
        padding: 2rem 0;
        min-height: 100vh;
    }

    .bill-detail-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    .bill-detail-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
    }

    .bill-detail-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .bill-header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .bill-header-actions .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bill-header-actions .btn-warning {
        background-color: #f59e0b;
        color: white;
    }

    .bill-header-actions .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
    }

    .bill-header-actions .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .bill-header-actions .btn-secondary:hover {
        background-color: #d1d5db;
        transform: translateY(-2px);
    }

    .bill-info-section {
        margin-bottom: 2rem;
    }

    .bill-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .bill-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .bill-info-item:last-child {
        border-bottom: none;
    }

    .bill-info-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .bill-info-value {
        color: #1f2937;
        font-weight: 600;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid #d1d5db;
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .metric-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
    }

    .metric-card.metric-danger .metric-value {
        color: #dc2626;
    }

    .metric-card.metric-success .metric-value {
        color: #10b981;
    }

    .metric-card.metric-info .metric-value {
        color: #3b82f6;
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
    }

    .quick-actions-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid #e5e7eb;
        position: sticky;
        top: 1rem;
    }

    .quick-actions-card h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 0.75rem;
        text-align: center;
        justify-content: center;
        text-decoration: none;
    }

    .action-button:last-child {
        margin-bottom: 0;
    }

    .action-button.btn-success {
        background-color: #10b981;
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .action-button.btn-success:hover {
        background-color: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .action-button.btn-primary {
        background-color: #3b82f6;
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
    }

    .action-button.btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .action-button.btn-danger {
        background-color: #ef4444;
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
    }

    .action-button.btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .payments-table-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        margin-top: 2rem;
        overflow: hidden;
    }

    .payments-table-header {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .payments-table thead {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .payments-table thead th {
        padding: 1rem 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-align: left;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .payments-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .payments-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .payments-table tbody td {
        padding: 1rem 0.75rem;
        color: #374151;
    }

    .payment-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8rem;
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
        background-color: #dddd99;
        color: #3d3d00;
    }

    .payment-badge.other {
        background-color: #e9d5ff;
        color: #581c87;
    }

    .delete-payment-btn {
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .delete-payment-btn:hover {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-badge.paid {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.partially-paid {
        background-color: #fef08a;
        color: #78350f;
    }

    .status-badge.unpaid {
        background-color: #e5e7eb;
        color: #374151;
    }

    .status-badge.overdue {
        background-color: #fee2e2;
        color: #7f1d1d;
    }

    @media (max-width: 768px) {
        .bill-detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .bill-header-actions {
            width: 100%;
            flex-wrap: wrap;
        }

        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .quick-actions-card {
            position: static;
            margin-top: 1.5rem;
        }

        .payments-table {
            font-size: 0.8rem;
        }

        .payments-table thead th,
        .payments-table tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="bill-detail-container">
    <div class="container-lg">
        <!-- Header Section -->
        <div class="bill-detail-wrapper">
            <div class="bill-detail-header">
                <h1><i class="fas fa-receipt"></i> Bill Details</h1>
                <div class="bill-header-actions">
                    <a href="{{ route('due-bills.edit', $bill->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('due-bills.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <!-- Bill Info Section -->
            <div class="bill-info-section">
                <div class="bill-info-grid">
                    <div>
                        <div class="bill-info-item">
                            <span class="bill-info-label">Client Name:</span>
                            <span class="bill-info-value">{{ $bill->client->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="bill-info-item">
                            <span class="bill-info-label">Bill Period:</span>
                            <span class="bill-info-value">{{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="bill-info-item">
                            <span class="bill-info-label">Bill Date:</span>
                            <span class="bill-info-value">{{ $bill->bill_date->format('d-m-Y') }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="bill-info-item">
                            <span class="bill-info-label">Due Date:</span>
                            <span class="bill-info-value">{{ $bill->due_date->format('d-m-Y') }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="bill-info-item">
                            <span class="bill-info-label">Status:</span>
                            <span class="bill-info-value">
                                @if($bill->status === 'paid')
                                    <span class="status-badge paid">✓ Paid</span>
                                @elseif($bill->status === 'partially_paid')
                                    <span class="status-badge partially-paid">⚠ Partially Paid</span>
                                @elseif($bill->status === 'overdue')
                                    <span class="status-badge overdue">✕ Overdue</span>
                                @else
                                    <span class="status-badge unpaid">○ Unpaid</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-label">
                        <i class="fas fa-receipt"></i> Total Amount
                    </div>
                    <div class="metric-value">৳ {{ number_format($bill->amount, 2) }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">
                        <i class="fas fa-check-circle"></i> Paid Amount
                    </div>
                    <div class="metric-value">৳ {{ number_format($bill->paid_amount, 2) }}</div>
                </div>
                <div class="metric-card metric-danger">
                    <div class="metric-label">
                        <i class="fas fa-exclamation-circle"></i> Remaining
                    </div>
                    <div class="metric-value">৳ {{ number_format($bill->remaining_balance, 2) }}</div>
                </div>
                <div class="metric-card metric-info">
                    <div class="metric-label">
                        <i class="fas fa-chart-pie"></i> Progress
                    </div>
                    <div class="metric-value">{{ $bill->payment_percentage }}%</div>
                </div>
            </div>

            @if($bill->notes)
            <div class="notes-section">
                <strong style="color: #1f2937; margin-bottom: 0.5rem; display: block;">
                    <i class="fas fa-sticky-note"></i> Notes
                </strong>
                <p>{{ $bill->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Layout Grid for Quick Actions -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <!-- Payments History -->
                @if($bill->payments->count() > 0)
                <div class="payments-table-wrapper">
                    <div class="payments-table-header">
                        <i class="fas fa-history"></i> Payment History ({{ $bill->payments->count() }} records)
                    </div>
                    <div class="table-responsive">
                        <table class="payments-table">
                            <thead>
                                <tr>
                                    <th>Payment Date</th>
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
                                    <td><strong>৳ {{ number_format($payment->amount, 2) }}</strong></td>
                                    <td>
                                        <span class="payment-badge {{ strtolower($payment->payment_method) }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->transaction_id ?? '—' }}</td>
                                    <td>
                                        <button class="delete-payment-btn" onclick="deletePayment({{ $payment->id }})" title="Delete payment">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions Sidebar -->
            <div class="col-lg-4">
                <div class="quick-actions-card">
                    <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
                    <a href="{{ route('due-bill-payments.create', ['bill_id' => $bill->id, 'client_id' => $bill->client_id]) }}" class="action-button btn-success">
                        <i class="fas fa-plus-circle"></i> Add Payment
                    </a>
                    @if($bill->status !== 'paid')
                    <form method="POST" action="{{ route('due-bills.send-reminder', $bill->id) }}">
                        @csrf
                        <button type="submit" class="action-button btn-primary">
                            <i class="fas fa-sms"></i> Send SMS Reminder
                        </button>
                    </form>
                    @endif
                    @if($bill->remaining_balance > 0)
                    <button onclick="markAsPaid({{ $bill->id }})" class="action-button btn-primary">
                        <i class="fas fa-check-double"></i> Mark as Paid
                    </button>
                    @endif
                    <button onclick="deleteBill({{ $bill->id }})" class="action-button btn-danger">
                        <i class="fas fa-trash-alt"></i> Delete Bill
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function markAsPaid(billId) {
        if(!confirm('Mark this bill as completely paid?')) return;
        window.location.href = `/due-bills/${billId}/mark-paid`;
    }

    function deleteBill(billId) {
        if(!confirm('Are you sure you want to delete this bill? This action cannot be undone.')) return;
        $.ajax({
            url: "{{ route('due-bills.destroy', ':id') }}".replace(':id', billId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Bill deleted successfully.');
                window.location.href = "{{ route('due-bills.index') }}";
            },
            error: function(xhr) {
                alert('Something went wrong. Could not delete the bill.');
            }
        });
    }

    function deletePayment(paymentId) {
        if(!confirm('Delete this payment record?')) return;
        $.ajax({
            url: "{{ route('due-bill-payments.destroy', ':id') }}".replace(':id', paymentId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Payment deleted successfully.');
                window.location.reload();
            },
            error: function(xhr) {
                alert('Something went wrong. Could not delete the payment.');
            }
        });
    }
</script>
@endsection
