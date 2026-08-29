@extends('layouts.app')

@section('title')
    Invoice #INV-{{ $bill->year }}{{ str_pad($bill->month, 2, '0', STR_PAD_LEFT) }}-{{ str_pad($bill->id, 5, '0', STR_PAD_LEFT) }}
@endsection

@section('css')
<style>
    /* ─── Screen Layout ────────────────────────────────────────── */
    .invoice-wrapper {
        padding: 2rem 0;
        background-color: #f1f5f9;
        min-height: 100vh;
    }

    .invoice-action-bar {
        max-width: 860px;
        margin: 0 auto 1.5rem auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .invoice-card {
        background: #ffffff;
        max-width: 860px;
        margin: 0 auto;
        padding: 2.5rem 3rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
    }

    /* ─── Header ───────────────────────────────────────────────── */
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.75rem;
    }

    .company-brand h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .company-brand p {
        color: #64748b;
        margin: 0;
        font-size: 0.875rem;
    }

    .invoice-title-block {
        text-align: right;
    }

    .invoice-title-block h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2563eb;
        margin: 0 0 0.25rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .invoice-number {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 600;
        color: #475569;
        font-size: 0.95rem;
    }

    /* ─── Meta & Client Details ────────────────────────────────── */
    .invoice-meta-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
    }

    .meta-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.25rem;
    }

    .meta-box-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .client-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .meta-line {
        font-size: 0.875rem;
        color: #334155;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .meta-line:last-child {
        margin-bottom: 0;
    }

    .meta-table {
        width: 100%;
        font-size: 0.875rem;
    }

    .meta-table td {
        padding: 0.25rem 0;
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

    /* ─── Status Watermark Stamp ───────────────────────────────── */
    .status-stamp-container {
        position: absolute;
        top: 130px;
        right: 40px;
        pointer-events: none;
        opacity: 0.85;
    }

    .stamp {
        display: inline-block;
        padding: 0.35rem 1.25rem;
        font-weight: 800;
        font-size: 1.4rem;
        text-transform: uppercase;
        border-radius: 8px;
        border: 3px solid;
        transform: rotate(-12deg);
        letter-spacing: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

    /* ─── Table ────────────────────────────────────────────────── */
    .invoice-table-wrapper {
        margin-bottom: 1.5rem;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .invoice-table thead th {
        background-color: #0f172a;
        color: #ffffff;
        padding: 0.75rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .invoice-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: middle;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: 2px solid #cbd5e1;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    /* ─── Summary & Calculations ───────────────────────────────── */
    .invoice-bottom-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .payment-history-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
    }

    .payment-history-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .mini-payments-table {
        width: 100%;
        font-size: 0.8rem;
    }

    .mini-payments-table th {
        color: #64748b;
        font-weight: 600;
        padding: 0.3rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .mini-payments-table td {
        padding: 0.4rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .mini-payments-table tr:last-child td {
        border-bottom: none;
    }

    .summary-card-table {
        width: 100%;
        font-size: 0.9rem;
    }

    .summary-card-table td {
        padding: 0.45rem 0.5rem;
    }

    .summary-card-table tr.total-row {
        border-top: 2px solid #0f172a;
        border-bottom: 2px solid #0f172a;
        font-weight: 700;
        font-size: 1.05rem;
    }

    .summary-card-table tr.total-row td {
        padding: 0.65rem 0.5rem;
        color: #0f172a;
    }

    .summary-card-table tr.due-row {
        font-weight: 700;
        font-size: 1.15rem;
        color: #dc2626;
        background: #fef2f2;
    }

    .summary-card-table tr.due-row.paid-zero {
        color: #16a34a;
        background: #f0fdf4;
    }

    /* ─── Footer & Signatures ──────────────────────────────────── */
    .invoice-footer {
        border-top: 1px dashed #cbd5e1;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }

    .invoice-footer-grid {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .footer-notes {
        font-size: 0.8rem;
        color: #64748b;
        max-width: 60%;
        line-height: 1.5;
    }

    .signature-block {
        text-align: center;
        min-width: 180px;
    }

    .signature-line {
        border-top: 1px solid #94a3b8;
        margin-top: 40px;
        padding-top: 5px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
    }

    /* ─── Action Buttons ───────────────────────────────────────── */
    .btn-action {
        padding: 0.55rem 1.1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-print {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
    }
    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
        color: #ffffff;
    }

    .btn-back {
        background: #e2e8f0;
        color: #334155 !important;
    }
    .btn-back:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
    }

    .btn-details {
        background: #ffffff;
        color: #2563eb !important;
        border: 1px solid #bfdbfe;
    }
    .btn-details:hover {
        background: #eff6ff;
        transform: translateY(-2px);
    }

    .btn-pay {
        background: #10b981;
        color: #ffffff !important;
    }
    .btn-pay:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    /* ─── Print Stylesheet ─────────────────────────────────────── */
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }

        body, html {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            color: #000000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide all UI navigation elements */
        .navbar,
        .navbar-bg,
        .main-sidebar,
        .main-footer,
        .invoice-action-bar,
        .alert,
        #toast-container,
        .iziToast-wrapper,
        .sidebar-menu,
        aside {
            display: none !important;
        }

        .main-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            min-height: auto !important;
            animation: none !important;
        }

        .invoice-wrapper {
            background: transparent !important;
            padding: 0 !important;
            min-height: auto !important;
        }

        .invoice-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            border-radius: 0 !important;
        }

        .status-stamp-container {
            top: 100px !important;
            right: 20px !important;
            opacity: 0.9 !important;
        }

        .invoice-table thead th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
        }

        .meta-box, .payment-history-box {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            -webkit-print-color-adjust: exact !important;
        }
    }
</style>
@endsection

@section('content')
@php
    $ispCode = $bill->client->isp_code ?? null;
@endphp
<div class="invoice-wrapper">
    <!-- Top Action Bar (Hidden during printing) -->
    <div class="invoice-action-bar">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('due-bills.index') }}" class="btn-action btn-back">
                <i class="fas fa-arrow-left"></i> Back to Bills
            </a>
            <a href="{{ route('due-bills.show', $bill->id) }}" class="btn-action btn-details">
                <i class="fas fa-file-alt"></i> Bill Details
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($bill->status !== 'paid' && $bill->remaining_balance > 0)
                <a href="{{ route('due-bill-payments.create', ['bill_id' => $bill->id, 'client_id' => $bill->client_id]) }}" class="btn-action btn-pay">
                    <i class="fas fa-credit-card"></i> Add Payment
                </a>
            @endif
            <button type="button" onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Printable Invoice Sheet -->
    <div class="invoice-card">
        <!-- Status Stamp -->
        <div class="status-stamp-container">
            @if($bill->status === 'paid')
                <div class="stamp stamp-paid">
                    <i class="fas fa-check-circle me-1"></i> PAID
                </div>
            @elseif($bill->status === 'partially_paid')
                <div class="stamp stamp-partial">
                    <i class="fas fa-adjust me-1"></i> PARTIAL
                </div>
            @else
                <div class="stamp stamp-unpaid">
                    <i class="fas fa-clock me-1"></i> UNPAID
                </div>
            @endif
        </div>

        <!-- Header -->
        <div class="invoice-header">
            <div class="company-brand d-flex align-items-center gap-3">
                <img src="{{ isp_logo($ispCode) }}" alt="{{ isp_name($ispCode) }}" style="max-height: 55px; max-width: 150px; object-fit: contain;">
                <div>
                    <h2>
                        {{ isp_name($ispCode, 'CARNIVAL NETWORKS') }}
                    </h2>
                    <p>{{ isp_setting('isp_tagline', 'High-Speed Broadband Internet & Network Solutions', $ispCode) }}</p>
                    <p class="small text-muted mt-1">
                        <i class="fas fa-phone-alt me-1"></i> Support: {{ isp_setting('phone', config('sms.payment_number', '01XXXXXXXXX'), $ispCode) }}
                        @if(isp_setting('website', null, $ispCode))
                            &nbsp;|&nbsp; <i class="fas fa-globe me-1"></i> {{ isp_setting('website', null, $ispCode) }}
                        @endif
                        @if(isp_setting('email', null, $ispCode))
                            &nbsp;|&nbsp; <i class="fas fa-envelope me-1"></i> {{ isp_setting('email', null, $ispCode) }}
                        @endif
                    </p>
                    @if(isp_setting('address', null, $ispCode))
                        <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i> {{ isp_setting('address', null, $ispCode) }}</p>
                    @endif
                </div>
            </div>
            <div class="invoice-title-block">
                <h3>{{ isp_setting('invoice_title', 'MONEY RECEIPT', $ispCode) }}</h3>
                <div class="invoice-number">
                    INV-{{ $bill->year }}{{ str_pad($bill->month, 2, '0', STR_PAD_LEFT) }}-{{ str_pad($bill->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                <div class="small text-muted mt-1">
                    Issued: {{ $bill->bill_date ? $bill->bill_date->format('d M, Y') : now()->format('d M, Y') }}
                </div>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="invoice-meta-grid">
            <!-- Client Info -->
            <div class="meta-box">
                <div class="meta-box-title">
                    <i class="fas fa-user-circle"></i> Billed To Client
                </div>
                <div class="client-name">{{ $bill->client->name ?? 'Valued Customer' }}</div>
                <div class="meta-line">
                    <strong class="text-dark">User ID:</strong> 
                    <span class="badge bg-primary text-white ms-1 px-2">{{ $bill->client->username ?? 'N/A' }}</span>
                </div>
                @if($bill->client->contact)
                <div class="meta-line">
                    <i class="fas fa-phone text-muted" style="width:14px;"></i> {{ $bill->client->contact }}
                </div>
                @endif
                @if($bill->client->address)
                <div class="meta-line">
                    <i class="fas fa-map-marker-alt text-muted" style="width:14px;"></i> {{ $bill->client->address }}
                </div>
                @endif
            </div>

            <!-- Billing Meta Info -->
            <div class="meta-box">
                <div class="meta-box-title">
                    <i class="fas fa-info-circle"></i> Billing Particulars
                </div>
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
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($bill->client->isp_code ?? 'Default') }}</span></td>
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
                                <span class="badge bg-success">Paid in Full</span>
                            @elseif($bill->status === 'partially_paid')
                                <span class="badge bg-warning text-dark">Partially Paid</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Main Items Table -->
        <div class="invoice-table-wrapper">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 52%;">Description / Service Item</th>
                        <th style="width: 20%;" class="text-center">Billing Cycle</th>
                        <th style="width: 20%;" class="text-right">Amount ({{ isp_setting('currency_symbol', '৳', $ispCode) }})</th>
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
                        <td class="text-right">{{ isp_setting('currency_symbol', '৳', $ispCode) }} {{ number_format($bill->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bottom Grid: Payment History & Calculation Summary -->
        <div class="invoice-bottom-grid">
            <!-- Payment Transactions -->
            <div class="payment-history-box">
                <div class="payment-history-title">
                    <i class="fas fa-receipt"></i> Payment Records Received
                </div>
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
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($payment->payment_method) }}</span></td>
                                <td class="text-muted font-monospace small">{{ $payment->transaction_id ?? '-' }}</td>
                                <td class="text-right font-weight-bold text-success">{{ isp_setting('currency_symbol', '৳', $ispCode) }} {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="small text-muted py-3 text-center">
                        <em>No payments recorded for this invoice yet.</em>
                    </div>
                @endif
            </div>

            <!-- Summary Totals -->
            <div>
                <table class="summary-card-table">
                    <tr>
                        <td class="text-muted">Sub Total:</td>
                        <td class="text-right font-weight-bold">{{ isp_setting('currency_symbol', '৳', $ispCode) }} {{ number_format($bill->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Discount:</td>
                        <td class="text-right">{{ isp_setting('currency_symbol', '৳', $ispCode) }} 0.00</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Payable:</td>
                        <td class="text-right">{{ isp_setting('currency_symbol', '৳', $ispCode) }} {{ number_format($bill->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-success font-weight-bold">Total Paid:</td>
                        <td class="text-right text-success font-weight-bold">{{ isp_setting('currency_symbol', '৳', $ispCode) }} {{ number_format($bill->paid_amount, 2) }}</td>
                    </tr>
                    <tr class="due-row {{ $bill->remaining_balance <= 0 ? 'paid-zero' : '' }}">
                        <td>Balance Due:</td>
                        <td class="text-right">{{ isp_setting('currency_symbol', '৳', $ispCode) }} {{ number_format(max(0, $bill->remaining_balance), 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($bill->notes)
        <div class="mb-3 p-2 px-3 rounded bg-light border text-muted small">
            <strong><i class="fas fa-sticky-note me-1"></i> Note:</strong> {{ $bill->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="invoice-footer-grid">
                <div class="footer-notes">
                    <strong>Payment Instructions & Terms:</strong><br>
                    {{ isp_setting('payment_instruction', 'Please pay through bKash/Nagad Merchant or Cash within the due date.', $ispCode) }}<br>
                    {{ isp_setting('invoice_footer', 'Keep this invoice receipt for future reference. Thank you for being with us!', $ispCode) }}
                </div>
                <div class="signature-block">
                    <div class="signature-line">
                        {{ isp_setting('signatory_title', 'Authorized Signatory', $ispCode) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Automatically trigger print dialog if requested in query parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            setTimeout(function () {
                window.print();
            }, 500);
        }
    });
</script>
@endsection
