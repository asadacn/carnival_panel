@extends('layouts.app')

@section('title')
    Due Bills Management
@endsection

@section('css')
<style>
    .bills-container { background: #f8f9fa; padding: 2rem 0; }
    .bills-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding: 0 1rem; }
    .bills-header h1 { font-size: 2rem; font-weight: 600; color: #1f2937; margin: 0; }
    .btn-create { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2); }
    .btn-create:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .bills-table-wrapper { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
    .bills-table { margin: 0; }
    .bills-table thead { background: #f3f4f6; }
    .bills-table thead th { padding: 1rem 1.25rem; font-weight: 600; color: #6b7280; border: none; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .bills-table tbody tr { border-top: 1px solid #e5e7eb; transition: all 0.2s ease; }
    .bills-table tbody tr:hover { background-color: #f9fafb; }
    .bills-table td { padding: 1rem 1.25rem; color: #374151; font-size: 0.95rem; }
    .status-badge { display: inline-block; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500; text-transform: capitalize; }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-partially-paid { background: #fef3c7; color: #92400e; }
    .status-unpaid { background: #f3f4f6; color: #4b5563; }
    .status-overdue { background: #fee2e2; color: #991b1b; }
    .amount { font-weight: 600; color: #1f2937; }
    .action-btn { padding: 0.5rem 0.9rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.2s ease; font-weight: 500; }
    .btn-view { background: #dbeafe; color: #1e40af; }
    .btn-view:hover { background: #bfdbfe; }
    .btn-edit { background: #fef3c7; color: #92400e; }
    .btn-edit:hover { background: #fde68a; }
    .btn-delete { background: #fee2e2; color: #991b1b; }
    .btn-delete:hover { background: #fecaca; }
</style>
@endsection

@section('content')
<div class="bills-container">
    <div class="container-lg">
        <div class="bills-header">
            <h1>Due Bills</h1>
            <a href="{{ route('due-bills.create') }}" class="btn-create">
                <i class="fas fa-plus"></i> Create Bill
            </a>
        </div>

        <div class="bills-table-wrapper">
            <div class="table-responsive">
                <table class="table table-hover bills-table" id="bills-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Month/Year</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
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
        $('#bills-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('due-bills.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'client_name', name: 'client_name' },
                { data: 'month_year', name: 'month_year' },
                { data: 'amount', name: 'amount' },
                { data: 'paid_amount', name: 'paid_amount' },
                { data: 'remaining', name: 'remaining' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    function viewBill(billId) {
        window.location.href = "{{ route('due-bills.show', ':id') }}".replace(':id', billId);
    }

    function editBill(billId) {
        window.location.href = "{{ route('due-bills.edit', ':id') }}".replace(':id', billId);
    }

    function deleteBill(billId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('due-bills.destroy', ':id') }}".replace(':id', billId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Due bill has been deleted successfully.',
                            'success'
                        ).then(() => {
                            $('#bills-table').DataTable().ajax.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Failed to delete the due bill.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endsection
@endsection
