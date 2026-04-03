@extends('layouts.app')

@section('title')
    Payment Records
@endsection

@section('css')
<style>
    .payments-container { background: #f8f9fa; padding: 2rem 0; }
    .payments-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding: 0 1rem; }
    .payments-header h1 { font-size: 2rem; font-weight: 600; color: #1f2937; margin: 0; }
    .btn-create { background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2); }
    .btn-create:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
    .payments-table-wrapper { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
    .payments-table { margin: 0; }
    .payments-table thead { background: #f3f4f6; }
    .payments-table thead th { padding: 1rem 1.25rem; font-weight: 600; color: #6b7280; border: none; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .payments-table tbody tr { border-top: 1px solid #e5e7eb; transition: all 0.2s ease; }
    .payments-table tbody tr:hover { background-color: #f9fafb; }
    .payments-table td { padding: 1rem 1.25rem; color: #374151; font-size: 0.95rem; }
    .amount { font-weight: 600; color: #10b981; }
    .method-badge { display: inline-block; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500; background: #e0e7ff; color: #3730a3; }
    .action-btn { padding: 0.5rem 0.9rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.2s ease; font-weight: 500; }
    .btn-view { background: #dbeafe; color: #1e40af; }
    .btn-view:hover { background: #bfdbfe; }
    .btn-delete { background: #fee2e2; color: #991b1b; }
    .btn-delete:hover { background: #fecaca; }
</style>
@endsection

@section('content')
<div class="payments-container">
    <div class="container-lg">
        <div class="payments-header">
            <h1>Payment Records</h1>
            <a href="{{ route('due-bill-payments.create') }}" class="btn-create">
                <i class="fas fa-plus"></i> Record Payment
            </a>
        </div>

        <div class="payments-table-wrapper">
            <div class="table-responsive">
                <table class="table table-hover payments-table" id="payments-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Bill Month</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#payments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('due-bill-payments.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'client_name', name: 'client_name' },
                { data: 'bill_month', name: 'bill_month' },
                { data: 'amount_formatted', name: 'amount' },
                { data: 'payment_date_formatted', name: 'payment_date' },
                { data: 'method_badge', name: 'payment_method', orderable: false, searchable: false },
                { data: 'payment_date', name: 'payment_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
@endsection
