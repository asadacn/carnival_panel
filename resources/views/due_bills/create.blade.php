@extends('layouts.app')

@section('title')
    Create Due Bill
@endsection

@section('css')
<style>
    .form-container { background: #f8f9fa; padding: 2rem 0; min-height: calc(100vh - 300px); }
    .form-header { margin-bottom: 2.5rem; }
    .form-header h1 { font-size: 2rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0; }
    .form-header p { color: #6b7280; font-size: 0.95rem; }
    .form-wrapper { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { font-weight: 500; color: #374151; margin-bottom: 0.6rem; display: block; font-size: 0.95rem; }
    .form-group label span { color: #ef4444; }
    .form-control, .form-select { border: 1px solid #d1d5db; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.2s ease; background: #fafbfc; }
    .form-control:focus, .form-select:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
    .btn { padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 500; cursor: pointer; transition: all 0.2s ease; font-size: 0.95rem; }
    .btn-submit { background: #3b82f6; color: white; flex: 1; }
    .btn-submit:hover { background: #2563eb; transform: translateY(-2px); }
    .btn-cancel { background: #e5e7eb; color: #374151; flex: 1; }
    .btn-cancel:hover { background: #d1d5db; }
    .form-section { margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e7eb; }
    .form-section:last-of-type { border-bottom: none; }
    .form-section-title { font-size: 0.85rem; text-transform: uppercase; letters-spacing: 0.5px; font-weight: 600; color: #6b7280; margin-bottom: 1rem; }
    @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .form-actions { flex-direction: column; } }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="container-lg">
        <div class="form-header">
            <h1>Create Due Bill</h1>
            <p>Add a new bill to your records</p>
        </div>

        <div class="form-wrapper">
            <form action="{{ route('due-bills.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <div class="form-section-title">Client Information</div>
                    <div class="form-group">
                        <label for="client_id">Client <span>*</span></label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->username }})</option>
                            @endforeach
                        </select>
                        @error('client_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Billing Period</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="month">Month <span>*</span></label>
                            <select name="month" id="month" class="form-select" required>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" @if($i == now()->month) selected @endif>{{ \Carbon\Carbon::createFromDate(2000, $i, 1)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year <span>*</span></label>
                            <input type="number" name="year" id="year" class="form-control" value="{{ now()->year }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Important Dates</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bill_date">Bill Date <span>*</span></label>
                            <input type="date" name="bill_date" id="bill_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date <span>*</span></label>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Amount Details</div>
                    <div class="form-group">
                        <label for="amount">Amount (৳) <span>*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="0.00" step="0.01" required>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Additional Notes</div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any relevant notes..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Create Bill</button>
                    <a href="{{ route('due-bills.index') }}" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
