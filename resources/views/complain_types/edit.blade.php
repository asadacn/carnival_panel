@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Complaint Category</h3>
            <small class="text-muted">Update ticket complaint type</small>
        </div>
        <a href="{{ route('complain-types.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('complain-types.update', $complainType->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $complainType->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $complainType->description) }}</textarea>
                </div>
                <button class="btn btn-primary">Update Category</button>
            </form>
        </div>
    </div>
</div>
@endsection
