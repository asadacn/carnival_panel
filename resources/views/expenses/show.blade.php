@extends('layouts.app')

@section('title')
    Expense Details
@endsection

@section('css')
<style>
    .expense-detail-container {
        background-color: #f8fafc;
        padding: 2rem 0;
        min-height: 100vh;
    }

    .expense-detail-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .expense-detail-header {
        margin-bottom: 2rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1.5rem;
    }

    .expense-detail-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .expense-badge-inline {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 1rem;
    }

    .expense-info-section {
        margin-bottom: 2rem;
    }

    .expense-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .expense-info-row:last-child {
        border-bottom: none;
    }

    .expense-info-label {
        font-weight: 500;
        color: #64748b;
        font-size: 0.9rem;
    }

    .expense-info-value {
        color: #1e293b;
        font-weight: 500;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    .expense-info-value.amount {
        font-size: 1.25rem;
        color: #dc2626;
        font-weight: 700;
    }

    .expense-badge {
        display: inline-block;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .category-office_rent { background: #e0e7ff; color: #3730a3; }
    .category-electricity { background: #fef3c7; color: #92400e; }
    .category-internet { background: #dbeafe; color: #1e40af; }
    .category-salary { background: #dcfce7; color: #166534; }
    .category-equipment { background: #fce7f3; color: #9d174d; }
    .category-maintenance { background: #fef3c7; color: #b45309; }
    .category-transport { background: #e0e7ff; color: #3730a3; }
    .category-food { background: #fef3c7; color: #b45309; }
    .category-marketing { background: #e9d5ff; color: #6b21a8; }
    .category-software { background: #dbeafe; color: #1e40af; }
    .category-bank_charge { background: #fee2e2; color: #991b1b; }
    .category-other { background: #f1f5f9; color: #475569; }

    .method-cash { background: #fef3c7; color: #92400e; }
    .method-bkash { background: #dcfce7; color: #166534; }
    .method-nagad { background: #fecaca; color: #991b1b; }
    .method-bank { background: #dbeafe; color: #1e40af; }
    .method-card { background: #e9d5ff; color: #6b21a8; }
    .method-other { background: #f1f5f9; color: #475569; }

    .notes-section {
        background-color: #f8fafc;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }

    .notes-section h6 {
        margin: 0 0 0.75rem 0;
        color: #1e293b;
        font-weight: 600;
    }

    .notes-section p {
        margin: 0;
        color: #475569;
        line-height: 1.7;
    }

    .receipt-section {
        background-color: #f0f9ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .receipt-section h6 {
        margin: 0 0 1rem 0;
        color: #1e293b;
        font-weight: 600;
    }

    .receipt-preview {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .receipt-preview img {
        max-height: 180px;
        max-width: 100%;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
    }

    .receipt-preview .pdf-icon {
        width: 150px;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #dc2626;
        font-size: 4rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-primary {
        border: 2px solid #3b82f6;
        color: #3b82f6;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-outline-warning {
        border: 2px solid #f59e0b;
        color: #f59e0b;
        background: transparent;
    }

    .btn-outline-warning:hover {
        background: #f59e0b;
        color: white;
    }

    .btn-outline-danger {
        border: 2px solid #ef4444;
        color: #ef4444;
        background: transparent;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: #2563eb;
        color: white;
    }

    @media (max-width: 768px) {
        .expense-detail-wrapper {
            padding: 1.5rem;
        }

        .expense-info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .expense-info-value {
            text-align: left;
            max-width: 100%;
        }

        .expense-badge-inline {
            margin-left: 0;
            margin-top: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="expense-detail-container">
    <div class="expense-detail-wrapper">
        <div class="expense-detail-header">
            <h1>
                <i class="fas fa-file-invoice-dollar" style="color: #3b82f6;"></i>
                Expense Details
                <span class="expense-badge category-{{ $expense->category }}">{{ $expense->category_label }}</span>
            </h1>
        </div>

        <div class="expense-info-section">
            <div class="expense-info-row">
                <span class="expense-info-label"><i class="fas fa-calendar-alt"></i> Date</span>
                <span class="expense-info-value">{{ $expense->expense_date->format('d M, Y') }} <span class="text-muted" style="font-weight: 400; font-size: 0.85rem;">({{ $expense->expense_date->diffForHumans() }})</span></span>
            </div>

            <div class="expense-info-row">
                <span class="expense-info-label"><i class="fas fa-heading"></i> Title</span>
                <span class="expense-info-value">{{ $expense->title }}</span>
            </div>

            @if($expense->description)
                <div class="expense-info-row">
                    <span class="expense-info-label"><i class="fas fa-align-left"></i> Description</span>
                    <span class="expense-info-value">{{ $expense->description }}</span>
                </div>
            @endif

            <div class="expense-info-row">
                <span class="expense-info-label"><i class="fas fa-money-bill-wave"></i> Amount</span>
                <span class="expense-info-value amount">{{ isp_setting('currency_symbol', '৳') }} {{ number_format($expense->amount, 2) }}</span>
            </div>

            <div class="expense-info-row">
                <span class="expense-info-label"><i class="fas fa-credit-card"></i> Payment Method</span>
                <span class="expense-info-value">
                    <span class="expense-badge method-{{ $expense->payment_method }}">{{ $expense->payment_method_label }}</span>
                </span>
            </div>

            @if($expense->reference_number)
                <div class="expense-info-row">
                    <span class="expense-info-label"><i class="fas fa-hashtag"></i> Reference #</span>
                    <span class="expense-info-value">{{ $expense->reference_number }}</span>
                </div>
            @endif

            <div class="expense-info-row">
                <span class="expense-info-label"><i class="fas fa-user"></i> Added By</span>
                <span class="expense-info-value">{{ $expense->creator ? $expense->creator->name : 'System' }}</span>
            </div>

            <div class="expense-info-row">
                <span class="expense-info-label"><i class="fas fa-clock"></i> Recorded</span>
                <span class="expense-info-value">{{ $expense->created_at->format('d M, Y h:i A') }}</span>
            </div>
        </div>

        @if($expense->notes)
            <div class="notes-section">
                <h6><i class="fas fa-sticky-note"></i> Notes</h6>
                <p>{{ $expense->notes }}</p>
            </div>
        @endif

        @if($expense->receipt_path)
            <div class="receipt-section">
                <h6><i class="fas fa-paperclip"></i> Receipt Attachment</h6>
                <div class="receipt-preview">
                    @if(str_ends_with($expense->receipt_path, '.pdf'))
                        <div class="pdf-icon"><i class="fas fa-file-pdf"></i></div>
                    @else
                        <img src="{{ asset('storage/' . $expense->receipt_path) }}" alt="Receipt">
                    @endif
                    <div>
                        <div class="fw-bold">{{ basename($expense->receipt_path) }}</div>
                        <div class="form-text">
                            <a href="{{ route('office-expenses.receipt', $expense) }}" target="_blank" class="me-3"><i class="fas fa-eye"></i> View</a>
                            <a href="{{ route('office-expenses.receipt', $expense) }}" download><i class="fas fa-download"></i> Download</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('office-expenses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('office-expenses.edit', $expense) }}" class="btn btn-outline-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('office-expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this expense?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection