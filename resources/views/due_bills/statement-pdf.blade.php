<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Due Bill Statement - {{ $client->name }}</title>
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
            border-bottom: 3px solid #3b82f6;
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
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border-color: #93c5fd;
        }

        .summary-card.paid {
            background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%);
            border-color: #86efac;
        }

        .summary-card.remaining {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-color: #fca5a5;
        }

        .summary-card.percentage {
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
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }

        .total .summary-value {
            color: #1e40af;
        }

        .paid .summary-value {
            color: #15803d;
        }

        .remaining .summary-value {
            color: #b91c1c;
        }

        .percentage .summary-value {
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

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            min-width: 70px;
        }

        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-partial {
            background-color: #fef08a;
            color: #78350f;
        }

        .status-unpaid {
            background-color: #e5e7eb;
            color: #374151;
        }

        .status-overdue {
            background-color: #fee2e2;
            color: #7f1d1d;
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
            <div class="company-info">
                <div>
                    <div class="company-name">CARNIVAL NETWORKS</div>
                </div>
                <div class="company-details">
                    <div>ISP Management System</div>
                    <div>Report Generated: {{ now()->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="report-title">Due Bill Statement</div>
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
                <div class="summary-label">Total Bills</div>
                <div class="summary-value">{{ $bills->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Amount</div>
                <div class="summary-value">৳ {{ number_format($totalAmount, 2) }}</div>
            </div>
            <div class="summary-card paid">
                <div class="summary-label">Total Paid</div>
                <div class="summary-value">৳ {{ number_format($totalPaid, 2) }}</div>
            </div>
            <div class="summary-card remaining">
                <div class="summary-label">Outstanding</div>
                <div class="summary-value">৳ {{ number_format($totalRemaining, 2) }}</div>
            </div>
        </div>

        <!-- Bills Table -->
        <div class="table-section">
            <div class="section-title">Bill Details</div>
            @if($bills->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Bill Date</th>
                        <th>Due Date</th>
                        <th class="amount-right">Bill Amount</th>
                        <th class="amount-right">Paid</th>
                        <th class="amount-right">Remaining</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bills as $bill)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }}</strong></td>
                        <td>{{ $bill->bill_date->format('d-m-Y') }}</td>
                        <td>{{ $bill->due_date->format('d-m-Y') }}</td>
                        <td class="amount-right">৳ {{ number_format($bill->amount, 2) }}</td>
                        <td class="amount-right">৳ {{ number_format($bill->paid_amount, 2) }}</td>
                        <td class="amount-right"><strong>৳ {{ number_format($bill->amount - $bill->paid_amount, 2) }}</strong></td>
                        <td>
                            @if($bill->status === 'paid')
                                <span class="status-badge status-paid">✓ PAID</span>
                            @elseif($bill->status === 'partially_paid')
                                <span class="status-badge status-partial">⚠ PARTIAL</span>
                            @elseif($bill->status === 'overdue')
                                <span class="status-badge status-overdue">✕ OVERDUE</span>
                            @else
                                <span class="status-badge status-unpaid">○ UNPAID</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                No bills found for this client.
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
