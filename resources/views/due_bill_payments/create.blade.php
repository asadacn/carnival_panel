@extends('layouts.app')

@section('title')
    Record Payment
@endsection

@section('css')
<style>
    .payment-form-container {
        background-color: #f8f9fa;
        padding: 2rem 0;
        min-height: 100vh;
    }

    .payment-form-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin: 0 auto;
        max-width: 600px;
    }

    .payment-form-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
    }

    .payment-form-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .payment-form-header .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .payment-form-header .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .payment-form-header .btn-secondary:hover {
        background-color: #d1d5db;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .client-info-card {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: none;
        border: 1px solid #d1d5db;
    }

    .client-info-card.show {
        display: block;
    }

    .client-info-item {
        margin-bottom: 0.75rem;
    }

    .client-info-item:last-child {
        margin-bottom: 0;
    }

    .client-info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .client-info-value {
        font-size: 1rem;
        color: #1f2937;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 500;
        color: #374151;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.65rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background-color: #fafbfc;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .bill-info-alert {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
        border: 1px solid #93c5fd;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: none;
        font-size: 0.9rem;
        color: #1e40af;
    }

    .bill-info-alert.show {
        display: block;
    }

    .bill-info-item {
        display: inline-block;
        margin-right: 1.5rem;
    }

    .bill-info-label {
        font-weight: 600;
        color: #1e3a8a;
    }

    .form-section-divider {
        height: 1px;
        background-color: #e5e7eb;
        margin: 2rem 0;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
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

    .form-actions .btn-success {
        background-color: #10b981;
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .form-actions .btn-success:hover {
        background-color: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .form-actions .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .form-actions .btn-secondary:hover {
        background-color: #d1d5db;
        transform: translateY(-2px);
    }

    .text-danger {
        color: #ef4444;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc2626;
    }

    @media (max-width: 768px) {
        .payment-form-wrapper {
            padding: 1.5rem;
        }

        .payment-form-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .bill-info-item {
            display: block;
            margin-right: 0;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="payment-form-container">
    <div class="payment-form-wrapper">
        <div class="payment-form-header">
            <h1><i class="fas fa-money-check"></i> Record Payment</h1>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Client Info Section -->
        <div id="client-info-section" class="client-info-card">
            <div class="row">
                <div class="col-md-4">
                    <div class="client-info-item">
                        <div class="client-info-label">Client Name</div>
                        <div class="client-info-value"><span id="client-name">-</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="client-info-item">
                        <div class="client-info-label">Username</div>
                        <div class="client-info-value"><span id="client-username">-</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="client-info-item">
                        <div class="client-info-label">Package</div>
                        <div class="client-info-value"><span id="client-package">-</span></div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('due-bill-payments.store') }}" method="POST">
            @csrf

            <!-- Section 1: Client & Bill Selection -->
            <div class="form-section">
                <!-- Client Selection -->
                <div class="form-group">
                    <label for="client_id" class="form-label">Select Client <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required onchange="loadClientBills(); loadClientPackagePrice();">
                        <option value="">-- Choose a Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @if(request()->has('client_id') && request()->client_id == $client->id) selected @endif>
                                {{ $client->name }} ({{ $client->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bill Selection -->
                <div class="form-group">
                    <label for="due_bill_id" class="form-label">Select Due Bill <span class="text-danger">*</span></label>
                    <select name="due_bill_id" id="due_bill_id" class="form-select @error('due_bill_id') is-invalid @enderror" required onchange="updateBillAmount()">
                        <option value="">-- Choose a Bill --</option>
                        @if($bill)
                            <option value="{{ $bill->id }}" selected>
                                {{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }} - ৳ {{ number_format($bill->amount - $bill->paid_amount, 2) }} remaining
                            </option>
                        @endif
                    </select>
                    @error('due_bill_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bill Info Alert -->
                <div id="bill-info" class="bill-info-alert">
                    <div class="bill-info-item">
                        <span class="bill-info-label">Total:</span> <span id="bill-amount">-</span>
                    </div>
                    <div class="bill-info-item">
                        <span class="bill-info-label">Paid:</span> <span id="bill-paid">-</span>
                    </div>
                    <div class="bill-info-item">
                        <span class="bill-info-label">Remaining:</span> <span id="bill-remaining">-</span>
                    </div>
                </div>
            </div>

            <div class="form-section-divider"></div>

            <!-- Section 2: Payment Details -->
            <div class="form-section">
                <!-- Payment Date -->
                <div class="form-group">
                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                    @error('payment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="form-group">
                    <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" step="0.01" min="0.01" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                        <option value="">-- Select Method --</option>
                        <option value="cash">💵 Cash</option>
                        <option value="bkash">📱 bKash</option>
                        <option value="nagad">📱 Nagad</option>
                        <option value="bank">🏦 Bank Transfer</option>
                        <option value="other">📋 Other</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section-divider"></div>

            <!-- Section 3: Additional Info -->
            <div class="form-section">
                <!-- Transaction ID -->
                <div class="form-group">
                    <label for="transaction_id" class="form-label">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" placeholder="e.g., TXN123456 (optional)">
                    @error('transaction_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Add any additional notes (optional)"></textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Record Payment
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Bills data loaded from server
    const billsData = {!! json_encode($allBills ?? []) !!};

    // Clients data loaded from server
    const clientsData = {!! json_encode($clients->keyBy('id')->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'username' => $c->username, 'package' => $c->package ?? 'N/A'])->toArray()) !!};

    function loadClientBills() {
        const clientId = document.getElementById('client_id').value;
        const billSelect = document.getElementById('due_bill_id');
        const clientInfoSection = document.getElementById('client-info-section');

        if (!clientId) {
            billSelect.innerHTML = '<option value="">-- Choose a Bill --</option>';
            clientInfoSection.classList.remove('show');
            return;
        }

        // Show client info section
        const clientInfo = clientsData[clientId];
        if (clientInfo) {
            document.getElementById('client-name').textContent = clientInfo.name;
            document.getElementById('client-username').textContent = clientInfo.username;
            document.getElementById('client-package').textContent = clientInfo.package;
            clientInfoSection.classList.add('show');
            console.log('✓ Client info displayed:', clientInfo);
        }

        console.log('=== BILLS DEBUG ===');
        console.log('Selected client ID:', clientId, 'Type:', typeof clientId);
        console.log('Available client keys in billsData:', Object.keys(billsData));
        console.log('Full billsData:', billsData);

        // Try both string and number keys
        let bills = billsData[clientId] || billsData[String(clientId)] || billsData[parseInt(clientId)] || [];

        console.log('Bills found for client:', bills);

        billSelect.innerHTML = '<option value="">-- Choose a Bill --</option>';

        if (bills.length > 0) {
            // Separate unpaid and paid bills
            const unpaidBills = bills.filter(b => ['unpaid', 'partially_paid'].includes(b.status));
            const paidBills = bills.filter(b => b.status === 'paid');

            // Add unpaid bills first
            if (unpaidBills.length > 0) {
                unpaidBills.forEach(bill => {
                    const option = document.createElement('option');
                    option.value = bill.id;

                    // Format date without moment.js
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                      'July', 'August', 'September', 'October', 'November', 'December'];
                    const billDate = monthNames[bill.month - 1] + ' ' + bill.year;

                    const remaining = bill.amount - bill.paid_amount;
                    const statusLabel = bill.status === 'partially_paid' ? '⚠️ Partial' : '❌ Unpaid';
                    option.textContent = `${billDate} - ৳ ${bill.amount} (${statusLabel})`;
                    billSelect.appendChild(option);
                });
            }

            // Add separator if both types exist
            if (unpaidBills.length > 0 && paidBills.length > 0) {
                const separator = document.createElement('option');
                separator.disabled = true;
                separator.textContent = '─── Paid Bills ───';
                billSelect.appendChild(separator);
            }

            // Add paid bills
            if (paidBills.length > 0) {
                paidBills.forEach(bill => {
                    const option = document.createElement('option');
                    option.value = bill.id;

                    // Format date without moment.js
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                      'July', 'August', 'September', 'October', 'November', 'December'];
                    const billDate = monthNames[bill.month - 1] + ' ' + bill.year;

                    option.textContent = `${billDate} - ৳ ${bill.amount} (✅ Paid)`;
                    billSelect.appendChild(option);
                });
            }

            console.log(`✓ Bills loaded: ${unpaidBills.length} unpaid, ${paidBills.length} paid`);
        } else {
            billSelect.innerHTML += '<option disabled>❌ No bills found. Create one using Quick Bill first!</option>';
            console.warn('No bills found in billsData for client:', clientId);

            // Show what bills exist in database
            if (Object.keys(billsData).length === 0) {
                console.warn('⚠️ billsData is completely empty - no bills exist in the database at all');
            }
        }
    }

    function loadClientPackagePrice() {
        const clientId = document.getElementById('client_id').value;

        if (!clientId) {
            return;
        }

        $.ajax({
            url: `{{ route('clients.package-price', ':id') }}`.replace(':id', clientId),
            type: 'GET',
            success: function(response) {
                if (response.success && response.price) {
                    document.getElementById('amount').value = response.price;
                    console.log('✓ Package price auto-filled:', response.package_name, '=', response.price);
                }
            },
            error: function(xhr) {
                console.log('⚠️ Could not load package price:', xhr.responseJSON?.message || 'Unknown error');
            }
        });
    }

    function updateBillAmount() {
        const billId = document.getElementById('due_bill_id').value;
        const billInfo = document.getElementById('bill-info');

        if (!billId) {
            billInfo.classList.remove('show');
            return;
        }

        // Find the bill in billsData
        let selectedBill = null;
        const clientId = document.getElementById('client_id').value;
        const bills = billsData[clientId] || [];

        for (let bill of bills) {
            if (bill.id == billId) {
                selectedBill = bill;
                break;
            }
        }

        if (selectedBill) {
            // Populate the bill info
            document.getElementById('bill-amount').textContent = '৳ ' + parseFloat(selectedBill.amount).toFixed(2);
            document.getElementById('bill-paid').textContent = '৳ ' + parseFloat(selectedBill.paid_amount).toFixed(2);

            const remaining = selectedBill.amount - selectedBill.paid_amount;
            document.getElementById('bill-remaining').textContent = '৳ ' + parseFloat(remaining).toFixed(2);

            // Set the amount field to the remaining amount
            document.getElementById('amount').value = parseFloat(remaining).toFixed(2);

            console.log('✓ Bill info updated:', selectedBill);
            console.log('✓ Amount set to remaining balance:', parseFloat(remaining).toFixed(2));
        } else {
            console.warn('⚠️ Bill not found:', billId);
        }

        // Show the info section
        billInfo.classList.add('show');
    }

    // Load bills and package price if client is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== PAYMENT FORM INITIALIZED ===');
        console.log('URL client_id parameter:', new URLSearchParams(window.location.search).get('client_id'));
        console.log('URL bill_id parameter:', new URLSearchParams(window.location.search).get('bill_id'));
        console.log('Total clients in billsData:', Object.keys(billsData).length);

        const clientId = document.getElementById('client_id').value;
        const billId = document.getElementById('due_bill_id').value;

        if (clientId) {
            console.log('Pre-selected client:', clientId);
            loadClientBills();
            loadClientPackagePrice();

            // If a bill is also pre-selected, populate its details
            if (billId) {
                console.log('Pre-selected bill:', billId);
                // Wait a bit for JS to process, then update bill details
                setTimeout(function() {
                    updateBillAmount();
                }, 100);
            }
        } else {
            console.warn('⚠️ No client pre-selected');
        }
    });
</script>
@endsection
