@extends('layouts.app')

@section('title')
    Create Due Bill
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Due Bill</h1>
        <div class="section-header-button">
            <a href="{{ route('due-bills.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('due-bills.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-select" required>
                                    <option value="">-- Select Client --</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->username }})</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                                        <select name="month" id="month" class="form-select" required>
                                            @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" @if($i == now()->month) selected @endif>{{ \Carbon\Carbon::createFromDate(2000, $i, 1)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                        <input type="number" name="year" id="year" class="form-control" value="{{ now()->year }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="bill_date" class="form-label">Bill Date <span class="text-danger">*</span></label>
                                        <input type="date" name="bill_date" id="bill_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                        <input type="date" name="due_date" id="due_date" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="0.00" step="0.01" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Create Bill
                                </button>
                                <a href="{{ route('due-bills.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
