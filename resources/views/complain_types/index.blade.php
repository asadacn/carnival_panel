@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Complaint Categories</h3>
            <small class="text-muted">Manage ticket complaint types</small>
        </div>
        <a href="{{ route('complain-types.create') }}" class="btn btn-primary">+ Add Category</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complainTypes as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $type->name }}</strong></td>
                            <td>{{ $type->description ?: '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('complain-types.edit', $type->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('complain-types.destroy', $type->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No complaint categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
