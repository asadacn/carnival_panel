@extends('layouts.app')

@section('title')
    Add Daily Expense
@endsection

@section('css')
<style>
    .expense-form-container {
        background-color: #f8fafc;
        padding: 2rem 0;
        min-height: 100vh;
    }

    .expense-form-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin: 0 auto;
        max-width: 700px;
    }

    .expense-form-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1.5rem;
    }

    .expense-form-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .expense-form-header .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .expense-form-header .btn-secondary {
        background-color: #e2e8f0;
        color: #334155;
    }

    .expense-form-header .btn-secondary:hover {
        background-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        font-weight: 500;
        color: #334155;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.65rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background-color: #fafbfc;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .input-group-text {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 8px 0 0 8px;
        color: #64748b;
    }

    .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    .form-text {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.4rem;
    }

    .text-danger {
        color: #ef4444;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.825rem;
        margin-top: 0.25rem;
        display: block;
    }

    .is-invalid {
        border-color: #dc2626 !important;
    }

    .receipt-preview {
        margin-top: 1rem;
        display: none;
    }

    .receipt-preview.show {
        display: block;
    }

    .receipt-preview img {
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .receipt-preview .pdf-icon {
        width: 100%;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #dc2626;
        font-size: 3rem;
    }

    .form-section-divider {
        height: 1px;
        background-color: #e2e8f0;
        margin: 2rem 0;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .form-actions .btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .form-actions .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }

    .form-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .form-actions .btn-secondary {
        background-color: #e2e8f0;
        color: #334155;
    }

    .form-actions .btn-secondary:hover {
        background-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .category-color-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .expense-form-wrapper {
            padding: 1.5rem;
        }

        .expense-form-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="expense-form-container">
    <div class="expense-form-wrapper">
        <div class="expense-form-header">
            <h1><i class="fas fa-file-invoice-dollar" style="color: #3b82f6;"></i> Add Daily Expense</h1>
            <a href="{{ route('office-expenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('office-expenses.store') }}" method="POST" enctype="multipart/form-data" id="expense-form">
            @csrf

            <!-- Section 1: Basic Info -->
            <div class="form-section">
                <h5 class="form-section-title"><i class="fas fa-info-circle text-primary"></i> Basic Information</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expense_date">Date <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $today) }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Office rent for September" required maxlength="120" value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Optional details about the expense...">{{ old('description') }}</textarea>
                    <div class="form-text">Maximum 1000 characters</div>
                </div>
            </div>

            <!-- Section 2: Financial Details -->
            <div class="form-section">
                <h5 class="form-section-title"><i class="fas fa-money-bill-wave text-success"></i> Financial Details</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ isp_setting('currency_symbol', '৳') }}</span>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" step="0.01" min="0.01" placeholder="0.00" required value="{{ old('amount') }}">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">-- Select Method --</option>
                                @foreach($paymentMethods as $key => $label)
                                    <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reference_number">Reference / Transaction ID</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-control" placeholder="Invoice #, Transaction ID, Receipt #..." maxlength="100" value="{{ old('reference_number') }}">
                    <div class="form-text">Optional reference number for tracking</div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                    <div class="form-text">Internal notes (not shown on reports by default)</div>
                </div>
            </div>

            <!-- Section 3: Receipt Upload -->
            <div class="form-section">
                <h5 class="form-section-title"><i class="fas fa-paperclip text-info"></i> Receipt / Attachment</h5>

                <div class="form-group">
                    <label for="receipt">Upload Receipt (Image/PDF)</label>
                    <input type="file" name="receipt" id="receipt" class="form-control @error('receipt') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    @error('receipt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Max 2MB. Supported: JPG, PNG, PDF</div>
                </div>

                <div class="receipt-preview" id="receipt-preview">
                    <label class="form-label">Preview</label>
                    <div class="preview-content" id="preview-content"></div>
                    <div class="form-text mt-2"><i class="fas fa-info-circle"></i> Preview will appear here after file selection</div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('office-expenses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Set today as max date
        document.getElementById('expense_date').max = new Date().toISOString().split('T')[0];

        // Receipt preview
        const receiptInput = document.getElementById('receipt');
        const previewContainer = document.getElementById('receipt-preview');
        const previewContent = document.getElementById('preview-content');

        if (receiptInput) {
            receiptInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    previewContainer.classList.remove('show');
                    return;
                }

                previewContainer.classList.add('show');

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContent.innerHTML = '<img src="' + e.target.result + '" alt="Receipt preview">';
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    previewContent.innerHTML = '<div class="pdf-icon"><i class="fas fa-file-pdf"></i></div>';
                } else {
                    previewContent.innerHTML = '<div class="pdf-icon" style="background:#fef3c7;border-color:#fde68a;color:#b45309;"><i class="fas fa-file"></i></div>';
                }
            });
        }

        // Form validation feedback styling
        const form = document.getElementById('expense-form');
        form.addEventListener('submit', function() {
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        });
    });
</script>
@endsection