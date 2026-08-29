<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Statement - {{ $client->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            background: white;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            border-bottom: 3px solid #10b981;
            margin-bottom: 30px;
            padding-bottom: 20px;
        }

        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
        }

        .company-details {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.8;
            text-align: right;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0 10px 0;
        }

        .report-date {
            font-size: 12px;
            color: #6b7280;
        }

        /* Client Info */
        .client-info {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .info-item label {
            font-weight: bold;
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
        }

        .info-item p {
            font-size: 13px;
            color: #1f2937;
            margin-top: 3px;
        }

        /* Summary Cards */
        .summary-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .summary-card.total {
            background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%);
            border-color: #86efac;
        }

        .summary-card.cash {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border-color: #93c5fd;
        }

        .summary-card.mobile {
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            border-color: #f87171;
        }

        .summary-card.bank {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: #fcd34d;
        }

        .summary-label {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }

        .total .summary-value {
            color: #15803d;
        }

        .cash .summary-value {
            color: #1e40af;
        }

        .mobile .summary-value {
            color: #b91c1c;
        }

        .bank .summary-value {
            color: #92400e;
        }

        /* Table */
        .table-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead {
            background-color: #f3f4f6;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
            text-transform: uppercase;
            font-size: 11px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }

        .amount-right {
            text-align: right;
        }

        .method-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            min-width: 60px;
        }

        .method-cash {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .method-bkash {
            background-color: #dcfce7;
            color: #15803d;
        }

        .method-nagad {
            background-color: #fecaca;
            color: #7f1d1d;
        }

        .method-bank {
            background-color: #fef3c7;
            color: #92400e;
        }

        .method-other {
            background-color: #e9d5ff;
            color: #581c87;
        }

        /* Method Summary */
        .method-summary {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .method-summary-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .method-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }

        .method-item:last-child {
            border-bottom: none;
        }

        .method-name {
            font-weight: 500;
            color: #374151;
        }

        .method-amount {
            font-weight: bold;
            color: #1f2937;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }

        .footer-content {
            margin-bottom: 10px;
        }

        .print-only {
            display: none;
        }

        @media print {
            .print-only {
                display: block;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
            }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px;
            background: #f9fafb;
            border-radius: 8px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ isp_logo() }}" alt="{{ isp_name() }}" style="max-height: 50px; max-width: 130px; object-fit: contain;">
                    <div>
                        <div class="company-name">{{ isp_name('CARNIVAL NETWORKS') }}</div>
                        <div style="font-size: 11px; color: #6b7280;">{{ isp_setting('isp_tagline', 'High-Speed Broadband Internet') }}</div>
                    </div>
                </div>
                <div class="company-details">
                    <div>Support: {{ isp_setting('phone', '01XXXXXXXXX') }}</div>
                    @if(isp_setting('email'))<div>{{ isp_setting('email') }}</div>@endif
                    <div>Report Generated: {{ now()->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="report-title">Payment Statement</div>
            <div class="report-date">For Client: {{ $client->name }} ({{ $client->username }})</div>
        </div>

        <!-- Client Information -->
        <div class="client-info">
            <div class="info-item">
                <label>Client Name</label>
                <p>{{ $client->name }}</p>
            </div>
            <div class="info-item">
                <label>Username</label>
                <p>{{ $client->username }}</p>
            </div>
            <div class="info-item">
                <label>Package</label>
                <p>{{ $client->package->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-card total">
                <div class="summary-label">Total Payments</div>
                <div class="summary-value">{{ $payments->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Amount</div>
                <div class="summary-value">৳ {{ number_format($totalAmount, 2) }}</div>
            </div>
            @foreach($byMethod as $method => $amount)
            <div class="summary-card @if($method === 'cash') cash @elseif(in_array($method, ['bkash', 'nagad'])) mobile @endif">
                <div class="summary-label">{{ ucfirst($method) }}</div>
                <div class="summary-value">৳ {{ number_format($amount, 2) }}</div>
            </div>
            @if($loop->index === 2) @break @endif
            @endforeach
        </div>

        <!-- Payments Table -->
        <div class="table-section">
            <div class="section-title">Payment Transactions</div>
            @if($payments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bill Period</th>
                        <th class="amount-right">Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::createFromDate($payment->dueBill->year, $payment->dueBill->month, 1)->format('F Y') }}</td>
                        <td class="amount-right"><strong>৳ {{ number_format($payment->amount, 2) }}</strong></td>
                        <td>
                            <span class="method-badge method-{{ strtolower($payment->payment_method) }}">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td>{{ $payment->transaction_id ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Payment Method Summary -->
            <div class="method-summary">
                <div class="method-summary-title">Payment Summary by Method</div>
                @foreach($byMethod as $method => $amount)
                <div class="method-item">
                    <span class="method-name">{{ ucfirst($method) }}</span>
                    <span class="method-amount">৳ {{ number_format($amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                No payments found for this client.
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                This is an auto-generated statement. For inquiries, please contact support.
            </div>
            <div class="footer-content">
                © {{ now()->year }} Carnival Networks. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
