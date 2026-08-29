<?php

namespace App\Http\Controllers;

use App\Models\DueBill;
use App\Models\DueBillPayment;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class DueBillPaymentController extends Controller
{
    /**
     * Display all payments with filtering
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DueBillPayment::query();

            // Filter by client if provided
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            // Filter by payment method if provided
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            // Filter by date range if provided
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('payment_date', [
                    $request->from_date,
                    $request->to_date
                ]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('client_name', function ($row) {
                    return $row->client->name ?? '-';
                })
                ->addColumn('bill_month', function ($row) {
                    return $row->dueBill->month_year ?? '-';
                })
                ->addColumn('payment_date_formatted', function ($row) {
                    return $row->payment_date->format('d-m-Y');
                })
                ->addColumn('amount_formatted', function ($row) {
                    return '৳ ' . number_format($row->amount, 2);
                })
                ->addColumn('method_badge', function ($row) {
                    $colors = [
                        'cash' => 'success',
                        'bkash' => 'info',
                        'nagad' => 'primary',
                        'bank' => 'warning',
                        'other' => 'secondary'
                    ];
                    $color = $colors[$row->payment_method] ?? 'secondary';
                    return "<span class='badge bg-{$color}'>" . ucfirst($row->payment_method) . "</span>";
                })
                ->addColumn('action', function ($row) {
                    return "
                        <button class='btn btn-sm btn-primary' onclick=\"viewPayment({$row->id})\"><i class='fa fa-eye'></i></button>
                        <button class='btn btn-sm btn-warning' onclick=\"editPayment({$row->id})\"><i class='fa fa-edit'></i></button>
                        <button class='btn btn-sm btn-danger' onclick=\"deletePayment({$row->id})\"><i class='fa fa-trash'></i></button>
                    ";
                })
                ->rawColumns(['method_badge', 'action'])
                ->make(true);
        }

        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        $methods = ['cash', 'bkash', 'nagad', 'bank', 'other'];

        return view('due_bill_payments.index', compact('clients', 'methods'));
    }

    /**
     * Show form for recording a new payment
     */
    public function create(Request $request)
    {
        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        $bill_id = $request->query('bill_id');
        $bill = null;

        if ($bill_id) {
            $bill = DueBill::find($bill_id);
        }

        // Get all bills grouped by client for client-side filtering
        $allBills = DueBill::with('client')
            ->orderBy('client_id')
            ->orderBy('due_date', 'desc')
            ->get()
            ->groupBy('client_id')
            ->map(function ($bills) {
                return $bills->map(function ($bill) {
                    return [
                        'id' => $bill->id,
                        'client_id' => $bill->client_id,
                        'month' => $bill->month,
                        'year' => $bill->year,
                        'amount' => $bill->amount,
                        'paid_amount' => $bill->paid_amount,
                        'status' => $bill->status,
                        'due_date' => $bill->due_date->format('Y-m-d'),
                    ];
                })->toArray();
            })
            ->toArray();

        return view('due_bill_payments.create', compact('clients', 'bill', 'allBills'));
    }

    /**
     * Store a new payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'due_bill_id' => 'required|exists:due_bills,id',
            'client_id' => 'required|exists:clients,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bkash,nagad,bank,other',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $bill = DueBill::findOrFail($validated['due_bill_id']);

        if ((int) $bill->client_id !== (int) $validated['client_id']) {
            return back()->withErrors(['client_id' => 'The selected client does not own this bill.'])->withInput();
        }

        if ((float) $validated['amount'] > (float) $bill->remaining_balance) {
            return back()->withErrors(['amount' => 'Payment cannot exceed the remaining balance of ৳' . number_format($bill->remaining_balance, 2)])->withInput();
        }

        // Create payment
        $payment = DueBillPayment::create($validated);

        // Update bill's paid amount
        $totalPaid = DueBillPayment::where('due_bill_id', $bill->id)->sum('amount');
        $bill->paid_amount = $totalPaid;

        // Update bill status
        if ($totalPaid >= $bill->amount) {
            $bill->status = 'paid';
        } elseif ($totalPaid > 0) {
            $bill->status = 'partially_paid';
        }

        $bill->save();

        // Send SMS notification
        try {
            $client = Client::findOrFail($validated['client_id']);
            $monthName = \Carbon\Carbon::createFromDate($bill->year, $bill->month, 1)->format('F Y');
            $remainingAmount = $bill->amount - $bill->paid_amount;

            if ($remainingAmount <= 0) {
                $message = "প্রিয় {$client->name}, পেমেন্ট নিশ্চিত হয়েছে।\n"
                    . "কাস্টমার আইডি: {$client->username}\n"
                    . "{$monthName} মাসের বিল: ৳" . number_format($validated['amount'], 2)
                    . "\nবিলটি সম্পূর্ণ পরিশোধ হয়েছে। ধন্যবাদ।\n- " . ucfirst($client->isp_code);
            } else {
                $message = "প্রিয় {$client->name}, আংশিক পেমেন্ট নিশ্চিত হয়েছে।\n"
                    . "কাস্টমার আইডি: {$client->username}\n"
                    . "{$monthName} মাসে জমা: ৳" . number_format($validated['amount'], 2)
                    . "\nঅবশিষ্ট বকেয়া: ৳" . number_format($remainingAmount, 2)
                    . "\n" . config('sms.payment_instruction') . "\n"
                    . config('sms.payment_methods') . ': ' . config('sms.payment_number')
                    . "\n- " . ucfirst($client->isp_code);
            }

            if ($client->contact) {
                sms($client->contact, $message);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the payment creation
        }

        return redirect()->route('due-bills.invoice', ['id' => $bill->id, 'print' => 1])
            ->with('success', 'Payment recorded successfully');
    }

    /**
     * Display payment details
     */
    public function show($id)
    {
        $payment = DueBillPayment::with('dueBill', 'client')->findOrFail($id);
        return view('due_bill_payments.show', compact('payment'));
    }

    /**
     * Edit payment form
     */
    public function edit($id)
    {
        $payment = DueBillPayment::findOrFail($id);
        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        return view('due_bill_payments.edit', compact('payment', 'clients'));
    }

    /**
     * Update payment
     */
    public function update(Request $request, $id)
    {
        $payment = DueBillPayment::findOrFail($id);
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bkash,nagad,bank,other',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $remainingBeforePayment = (float) $payment->dueBill->amount
            - (float) $payment->dueBill->paid_amount
            + (float) $payment->amount;
        if ((float) $validated['amount'] > $remainingBeforePayment) {
            return back()->withErrors(['amount' => 'Payment cannot exceed the remaining balance of ৳' . number_format($remainingBeforePayment, 2)])->withInput();
        }

        $payment->update($validated);

        // Recalculate bill's paid amount
        $bill = $payment->dueBill;
        $totalPaid = DueBillPayment::where('due_bill_id', $bill->id)->sum('amount');
        $bill->paid_amount = $totalPaid;

        // Update bill status
        if ($totalPaid >= $bill->amount) {
            $bill->status = 'paid';
        } elseif ($totalPaid > 0) {
            $bill->status = 'partially_paid';
        } else {
            $bill->status = 'unpaid';
        }

        $bill->save();

        return redirect()->route('due-bills.invoice', ['id' => $bill->id, 'print' => 1])
            ->with('success', 'Payment updated successfully');
    }

    /**
     * Delete payment
     */
    public function destroy($id)
    {
        $payment = DueBillPayment::findOrFail($id);
        $billId = $payment->due_bill_id;

        $payment->delete();

        // Recalculate bill's paid amount
        $bill = DueBill::findOrFail($billId);
        $totalPaid = DueBillPayment::where('due_bill_id', $bill->id)->sum('amount');
        $bill->paid_amount = $totalPaid;

        // Update bill status
        if ($totalPaid >= $bill->amount) {
            $bill->status = 'paid';
        } elseif ($totalPaid > 0) {
            $bill->status = 'partially_paid';
        } else {
            $bill->status = 'unpaid';
        }

        $bill->save();

        return response()->json(['success' => true, 'message' => 'Payment deleted successfully']);
    }

    /**
     * Get client's payment history
     */
    public function clientPaymentHistory($clientId, Request $request)
    {
        $client = Client::findOrFail($clientId);

        $query = DueBillPayment::where('client_id', $clientId);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('payment_date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $payments = $query->with('dueBill')
            ->orderBy('payment_date', 'desc')
            ->paginate(20);

        $totalPaid = $client->dueBillPayments()->sum('amount');
        $totalDue = $client->dueBills()->sum('amount');

        return view('due_bill_payments.client_history', compact(
            'client',
            'payments',
            'totalPaid',
            'totalDue'
        ));
    }

    /**
     * Get payment report for date range
     */
    public function report(Request $request)
    {
        $query = DueBillPayment::query();

        if ($request->filled('from_date')) {
            $query->where('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('payment_date', '<=', $request->to_date);
        }

        $payments = $query->with('client', 'dueBill')
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalAmount = $payments->sum('amount');
        $byMethod = $payments->groupBy('payment_method')
            ->map(fn($items) => $items->sum('amount'));

        return view('due_bill_payments.report', compact(
            'payments',
            'totalAmount',
            'byMethod'
        ));
    }

    /**
     * Generate and download payment statement PDF for a client
     */
    public function paymentStatementPdf($clientId)
    {
        try {
            $client = Client::findOrFail($clientId);
            $payments = DueBillPayment::where('client_id', $clientId)
                ->with('dueBill')
                ->orderBy('payment_date', 'desc')
                ->get();

            $totalAmount = $payments->sum('amount');
            $byMethod = $payments->groupBy('payment_method')
                ->map(fn($items) => $items->sum('amount'));

            // Generate HTML
            $html = view('due_bill_payments.statement-pdf', compact(
                'client',
                'payments',
                'totalAmount',
                'byMethod'
            ))->render();

            // Return as downloadable HTML (print-to-PDF via browser)
            return response($html, 200, [
                'Content-Type' => 'text/html; charset=utf-8',
                'Content-Disposition' => 'inline; filename="payment-statement-' . $client->username . '.html"'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating payment statement: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to printable invoice for a payment
     */
    public function invoice($id)
    {
        $payment = DueBillPayment::findOrFail($id);
        return redirect()->route('due-bills.invoice', ['id' => $payment->due_bill_id, 'print' => 1]);
    }
}
