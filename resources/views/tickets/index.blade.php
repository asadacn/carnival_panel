@extends('layouts.app')

@section('title','Tickets')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Tickets</h3>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">+ New Ticket</a>
    </div>

    <div class="section-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th><th>Client</th><th>Type</th><th>Technician</th><th>Status</th><th>Priority</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->client->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->complainType->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->technician->name ?? 'Unassigned' }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($ticket->status) }}</span></td>
                        <td>{{ ucfirst($ticket->priority) }}</td>
                        <td>
                            <a href="{{ route('tickets.show',$ticket->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('tickets.edit',$ticket->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tickets->links() }}
        </div>
    </div>
</section>
@endsection
