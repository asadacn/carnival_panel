<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #INV-{{ $bill->year }}{{ str_pad($bill->month, 2, '0', STR_PAD_LEFT) }}-{{ str_pad($bill->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 15mm 15mm 20mm 15mm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            color: #1e293b;
            background: #ffffff;
            font-size: 12px;
            line-height: 1.6;
        }

        .invoice-page {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .invoice-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px 25px;
            position: relative;
            color: #1e293b;
        }

        .invoice-header {
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 12px;
        }

        .company-brand h2 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
        }

        .company-brand p {
            color: #64748b;
            margin: 0;
            font-size: 11px;
            line-height: 1.5;
        }

        .invoice-title-block {
            text-align: right;
        }

        .invoice-title-block h3 {
            font-size: 16px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }

        .invoice-number {
            font-weight: 600;
            color: #475569;
            font-size: 12px;
        }

        .invoice-meta-table {
            width: 100%;
            margin-bottom: 12px;
        }

        .invoice-meta-table td {
            vertical-align: top;
            padding: 0 8px;
        }

        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            width: 100%;
        }

        .meta-box-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 6px;
        }

        .client-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .meta-line {
            font-size: 11px;
            color: #334155;
            margin-bottom: 2px;
        }

        .meta-table {
            width: 100%;
            font-size: 11px;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-table td:first-child {
            color: #64748b;
            font-weight: 500;
            width: 45%;
        }

        .meta-table td:last-child {
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }

        .status-stamp {
            display: inline-block;
            padding: 4px 12px;
            font-weight: 800;
            font-size: 12px;
            text-transform: uppercase;
            border-radius: 6px;
            border: 2px solid;
            letter-spacing: 1px;
        }

        .stamp-paid {
            color: #16a34a;
            border-color: #16a34a;
            background: rgba(240, 253, 244, 0.85);
        }

        .stamp-partial {
            color: #d97706;
            border-color: #d97706;
            background: rgba(254, 252, 232, 0.85);
        }

        .stamp-unpaid {
            color: #dc2626;
            border-color: #dc2626;
            background: rgba(254, 242, 242, 0.85);
        }

        .invoice-table-wrapper {
            margin-bottom: 12px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .invoice-table thead th {
            background-color: #0f172a;
            color: #ffffff;
            padding: 8px 10px;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
        }

        .invoice-table tbody td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .invoice-bottom-table {
            width: 100%;
            margin-bottom: 12px;
        }

        .invoice-bottom-table td {
            vertical-align: top;
            padding: 0 8px;
        }

        .payment-history-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            width: 100%;
        }

        .payment-history-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
        }

        .mini-payments-table {
            width: 100%;
            font-size: 10px;
        }

        .mini-payments-table th {
            color: #64748b;
            font-weight: 600;
            padding: 4px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .mini-payments-table td {
            padding: 4px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .summary-card-table {
            width: 100%;
            font-size: 11px;
        }

        .summary-card-table td {
            padding: 5px 8px;
        }

        .summary-card-table tr.total-row {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            font-weight: 700;
            font-size: 12px;
        }

        .summary-card-table tr.total-row td {
            padding: 8px 8px;
            color: #0f172a;
        }

        .summary-card-table tr.due-row {
            font-weight: 700;
            font-size: 13px;
            color: #dc2626;
            background: #fef2f2;
        }

        .summary-card-table tr.due-row.paid-zero {
            color: #16a34a;
            background: #f0fdf4;
        }

        .invoice-footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 10px;
            color: #64748b;
        }

        .footer-notes {
            max-width: 70%;
            line-height: 1.6;
        }

        .signature-block {
            text-align: center;
            min-width: 140px;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            margin-top: 30px;
            padding-top: 4px;
            font-size: 10px;
            font-weight: 600;
            color: #475569;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #64748b;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .small {
            font-size: 10px;
        }

        .logo-img {
            display: block;
            width: 130px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
@php
    $ispCode = $bill->client->isp_code ?? null;
    $logoPath = public_path('img/logo.png'); // Default fallback

    // Try to get ISP-specific logo first
    $isp = \App\Models\Isp::forCode($ispCode);
    if ($isp && $isp->isp_logo && file_exists(public_path('uploads/settings/' . $isp->isp_logo))) {
        $logoPath = public_path('uploads/settings/' . $isp->isp_logo);
    } elseif ($isp && $isp->logo_url && file_exists($isp->logo_url)) {
        $logoPath = $isp->logo_url;
    }

    // Fallback to global default if no ISP-specific logo
    if ($logoPath === public_path('img/logo.png') && !file_exists($logoPath)) {
        $logoPath = public_path('img/logo.png');
    }

    $logoSrc = file_exists($logoPath) ? 'file:///' . str_replace('\\', '/', $logoPath) : '';
    $currencySymbol = isp_setting('currency_symbol', '৳', $ispCode);
@endphp
    <div class="invoice-page">
        <div class="invoice-card">
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="vertical-align:top;width:60%;">
                        <div class="company-brand">
                            @if($logoSrc)
                                <img src="{{ $logoSrc }}" alt="{{ isp_name($ispCode, 'Carnival Networks') }}" class="logo-img">
                            @endif
                            <h2>{{ isp_name($ispCode, 'CARNIVAL NETWORKS') }}</h2>
                            <p>{{ isp_setting('isp_tagline', 'High-Speed Broadband Internet & Network Solutions', $ispCode) }}</p>
                            <p class="small text-muted">
                                <strong>Support:</strong> {{ isp_setting('phone', config('sms.payment_number', '01XXXXXXXXX'), $ispCode) }}
                                @if(isp_setting('website', null, $ispCode))
                                    | <strong>Web:</strong> {{ isp_setting('website', null, $ispCode) }}
                                @endif
                                @if(isp_setting('email', null, $ispCode))
                                    | <strong>Email:</strong> {{ isp_setting('email', null, $ispCode) }}
                                @endif
                            </p>
                            @if(isp_setting('address', null, $ispCode))
                                <p class="small text-muted mb-0"><strong>Address:</strong> {{ isp_setting('address', null, $ispCode) }}</p>
                            @endif
                        </div>
                    </td>
                    <td style="vertical-align:top;width:40%;">
                        <div class="invoice-title-block">
                            <h3>{{ isp_setting('invoice_title', 'MONEY RECEIPT', $ispCode) }}</h3>
                            <div class="invoice-number">
                                INV-{{ $bill->year }}{{ str_pad($bill->month, 2, '0', STR_PAD_LEFT) }}-{{ str_pad($bill->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="small text-muted mt-1">
                                Issued: {{ $bill->bill_date ? $bill->bill_date->format('d M, Y') : now()->format('d M, Y') }}
                            </div>
                            <div style="margin-top: 10px;">
                                @if($bill->status === 'paid')
                                    <span class="status-stamp stamp-paid">PAID</span>
                                @elseif($bill->status === 'partially_paid')
                                    <span class="status-stamp stamp-partial">PARTIAL</span>
                                @else
                                    <span class="status-stamp stamp-unpaid">UNPAID</span>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="invoice-meta-table">
                <tr>
                    <td style="width: 58%;">
                        <div class="meta-box">
                            <div class="meta-box-title">Billed To Client</div>
                            <div class="client-name">{{ $bill->client->name ?? 'Valued Customer' }}</div>
                            <div class="meta-line"><strong>User ID:</strong> <span style="background:#2563eb;color:#ffffff;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;">{{ $bill->client->username ?? 'N/A' }}</span></div>
                            @if($bill->client->contact)
                                <div class="meta-line"><strong>Contact:</strong> {{ $bill->client->contact }}</div>
                            @endif
                            @if($bill->client->address)
                                <div class="meta-line"><strong>Address:</strong> {{ $bill->client->address }}</div>
                            @endif
                        </div>
                    </td>
                    <td style="width: 42%;">
                        <div class="meta-box">
                            <div class="meta-box-title">Billing Particulars</div>
                            <table class="meta-table">
                                <tr>
                                    <td>Billing Month:</td>
                                    <td>{{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Package Plan:</td>
                                    <td>{{ $bill->client->package ?? 'Broadband' }}</td>
                                </tr>
                                <tr>
                                    <td>ISP Provider:</td>
                                    <td><span style="background:#f1f5f9;color:#334155;border:1px solid #e2e8f0;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;">{{ strtoupper($bill->client->isp_code ?? 'Default') }}</span></td>
                                </tr>
                                <tr>
                                    <td>Bill Date:</td>
                                    <td>{{ $bill->bill_date ? $bill->bill_date->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Due Date:</td>
                                    <td>{{ $bill->due_date ? $bill->due_date->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Status:</td>
                                    <td>
                                        @if($bill->status === 'paid')
                                            <span style="background:#dcfce7;color:#166534;padding:3px 8px;border-radius:4px;font-size:10px;font-weight:700;">Paid in Full</span>
                                        @elseif($bill->status === 'partially_paid')
                                            <span style="background:#fef9c3;color:#854d0e;padding:3px 8px;border-radius:4px;font-size:10px;font-weight:700;">Partially Paid</span>
                                        @else
                                            <span style="background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:4px;font-size:10px;font-weight:700;">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="invoice-table-wrapper">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">#</th>
                            <th style="width: 52%;">Description / Service Item</th>
                            <th style="width: 20%;" class="text-center">Billing Cycle</th>
                            <th style="width: 20%;" class="text-right">Amount ({{ $currencySymbol }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>Internet Subscription Fee</strong>
                                <div class="small text-muted">
                                    Period: {{ \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y') }}
                                    &bull; Package: {{ $bill->client->package ?? 'Standard Internet' }}
                                </div>
                            </td>
                            <td class="text-center">Monthly</td>
                            <td class="text-right">{{ $currencySymbol }} {{ bdtFormat($bill->amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table class="invoice-bottom-table">
                <tr>
                    <td style="width: 58%;">
                        <div class="payment-history-box">
                            <div class="payment-history-title">Payment Records Received</div>
                            @if($bill->payments && $bill->payments->count() > 0)
                                <table class="mini-payments-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Method</th>
                                            <th>Trx ID</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date ? $payment->payment_date->format('d-m-Y') : '-' }}</td>
                                            <td><span style="background:#f1f5f9;color:#334155;border:1px solid #e2e8f0;padding:2px 6px;border-radius:4px;font-size:9px;font-weight:600;">{{ ucfirst($payment->payment_method) }}</span></td>
                                            <td class="text-muted" style="font-family:monospace;font-size:9px;">{{ $payment->transaction_id ?? '-' }}</td>
                                            <td class="text-right font-weight-bold text-success">{{ $currencySymbol }} {{ bdtFormat($payment->amount) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="small text-muted py-2 text-center">
                                    <em>No payments recorded for this invoice yet.</em>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td style="width: 42%;">
                        <table class="summary-card-table">
                            <tr>
                                <td class="text-muted">Sub Total:</td>
                                <td class="text-right font-weight-bold">{{ $currencySymbol }} {{ bdtFormat($bill->amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Discount:</td>
                                <td class="text-right">{{ $currencySymbol }} {{ bdtFormat(0) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td>Total Payable:</td>
                                <td class="text-right">{{ $currencySymbol }} {{ bdtFormat($bill->amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-success font-weight-bold">Total Paid:</td>
                                <td class="text-right text-success font-weight-bold">{{ $currencySymbol }} {{ bdtFormat($bill->paid_amount) }}</td>
                            </tr>
                            <tr class="due-row {{ $bill->remaining_balance <= 0 ? 'paid-zero' : '' }}">
                                <td>Balance Due:</td>
                                <td class="text-right">{{ $currencySymbol }} {{ bdtFormat(max(0, $bill->remaining_balance)) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            @if($bill->notes)
            <div class="mb-2 p-2 px-3 rounded bg-light border text-muted small">
                <strong>Note:</strong> {{ $bill->notes }}
            </div>
            @endif

            <div class="invoice-footer">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="width:70%;vertical-align:top;">
                            <div class="footer-notes">
                                <strong>Payment Instructions & Terms:</strong><br>
                                {{ isp_setting('payment_instruction', 'Please pay through bKash/Nagad Merchant or Cash within the due date.', $ispCode) }}<br>
                                {{ isp_setting('invoice_footer', 'Keep this invoice receipt for future reference. Thank you for being with us!', $ispCode) }}
                            </div>
                        </td>
                        <td style="width:30%;vertical-align:bottom;text-align:center;">
                            <div class="signature-block">
                                <div class="signature-line">
                                    {{ isp_setting('signatory_title', 'Authorized Signatory', $ispCode) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
