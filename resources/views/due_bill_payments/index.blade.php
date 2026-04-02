@extends('layouts.app')

@section('title')
    Payment Records
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Payment Records</h1>
        <div class="section-header-button">
            <a href="{{ route('due-bill-payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Record Payment
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>All Payments</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="payments-table">
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
</section>

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
