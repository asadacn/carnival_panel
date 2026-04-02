@extends('layouts.app')

@section('title')
    Record Payment
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Record Payment</h1>
        <div class="section-header-button">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Payment Details</h4>
                    </div>
                    <div class="card-body">
                        <!-- Client Info Section -->
                        <div id="client-info-section" class="alert alert-secondary mb-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <small>
                                        <strong>Client Name:</strong><br>
                                        <span id="client-name">-</span>
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small>
                                        <strong>Username:</strong><br>
                                        <span id="client-username">-</span>
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small>
                                        <strong>Package:</strong><br>
                                        <span id="client-package">-</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('due-bill-payments.store') }}" method="POST">
                            @csrf

                            <!-- Client Selection -->
                            <div class="form-group mb-3">
                                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required onchange="loadClientBills(); loadClientPackagePrice();">
                                    <option value="">-- Select Client --</option>
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
                            <div class="form-group mb-3">
                                <label for="due_bill_id" class="form-label">Due Bill <span class="text-danger">*</span></label>
                                <select name="due_bill_id" id="due_bill_id" class="form-select @error('due_bill_id') is-invalid @enderror" required onchange="updateBillAmount()">
                                    <option value="">-- Select Bill --</option>
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

                            <div id="bill-info" class="alert alert-info" style="display: none;">
                                <small>
                                    <strong>Bill Amount:</strong> <span id="bill-amount">-</span> |
                                    <strong>Already Paid:</strong> <span id="bill-paid">-</span> |
                                    <strong>Remaining:</strong> <span id="bill-remaining">-</span>
                                </small>
                            </div>

                            <!-- Payment Date -->
                            <div class="form-group mb-3">
                                <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" step="0.01" min="0.01" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div class="form-group mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="cash">Cash</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Transaction ID -->
                            <div class="form-group mb-3">
                                <label for="transaction_id" class="form-label">Transaction ID</label>
                                <input type="text" name="transaction_id" id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" placeholder="Optional">
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="form-group mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Optional notes..."></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Record Payment
                                </button>
                                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
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

<script>
    // Bills data loaded from server
    const billsData = {!! json_encode($allBills ?? []) !!};

    // Clients data loaded from server
    const clientsData = {!! json_encode($clients->keyBy('id')->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'username' => $c->username, 'package' => $c->package ?? 'N/A'])->toArray()) !!};

    function loadClientBills() {
        const clientId = document.getElementById('client_id').value;
        const billSelect = document.getElementById('due_bill_id');

        if (!clientId) {
            billSelect.innerHTML = '<option value="">-- Select Bill --</option>';
            document.getElementById('client-info-section').style.display = 'none';
            return;
        }

        // Show client info section
        const clientInfo = clientsData[clientId];
        if (clientInfo) {
            document.getElementById('client-name').textContent = clientInfo.name;
            document.getElementById('client-username').textContent = clientInfo.username;
            document.getElementById('client-package').textContent = clientInfo.package;
            document.getElementById('client-info-section').style.display = 'block';
            console.log('✓ Client info displayed:', clientInfo);
        }

        console.log('=== BILLS DEBUG ===');
        console.log('Selected client ID:', clientId, 'Type:', typeof clientId);
        console.log('Available client keys in billsData:', Object.keys(billsData));
        console.log('Full billsData:', billsData);

        // Try both string and number keys
        let bills = billsData[clientId] || billsData[String(clientId)] || billsData[parseInt(clientId)] || [];

        console.log('Bills found for client:', bills);

        billSelect.innerHTML = '<option value="">-- Select Bill --</option>';

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
            billInfo.style.display = 'none';
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
        billInfo.style.display = 'block';
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
