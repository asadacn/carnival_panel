@extends('layouts.app')

@section('title')
    Due Bills Management
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Due Bills</h1>
        <div class="section-header-button">
            <a href="{{ route('due-bills.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Bill
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Bills List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="bills-table">
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
</section>

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
