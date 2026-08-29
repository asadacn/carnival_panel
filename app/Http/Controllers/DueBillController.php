<?php

namespace App\Http\Controllers;

use App\Models\DueBill;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class DueBillController extends Controller
{
    /**
     * Display a listing of due bills
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DueBill::with('client')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderByRaw('(amount - paid_amount) DESC');

            // Filter by client if provided
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            // Filter by status if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by month/year if provided
            if ($request->filled('month')) {
                $query->where('month', $request->month);
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->filterColumn('client_name', function($query, $keyword) {
                    $query->whereHas('client', function($q) use($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('contact', 'like', "%{$keyword}%")
                          ->orWhere('secondary_contact', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('customer_id', function($query, $keyword) {
                    $query->whereHas('client', function($q) use($keyword) {
                        $q->where('username', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('client_name', function ($row) {
                    $client = $row->client;
                    if (!$client) return '-';

                    $name     = e($client->name);
                    $contact  = e($client->contact ?? '');
                    $address  = e($client->address ?? '');
                    $package  = e($client->package ?? '');
                    $status   = $client->status ?? '';
                    $isActive = $status === 'Active';

                    // Avatar: first letter of name, colored by status
                    $initial     = mb_strtoupper(mb_substr($name, 0, 1));
                    $avatarBg    = $isActive ? '#dcfce7' : '#fee2e2';
                    $avatarColor = $isActive ? '#166534' : '#991b1b';

                    $statusDot = $isActive
                        ? "<span class='status-dot status-dot-active' title='Active'></span>"
                        : "<span class='status-dot status-dot-inactive' title='{$status}'></span>";

                    $html  = "<div class='client-info-cell'>";
                    $html .= "<div class='client-avatar' style='background:{$avatarBg};color:{$avatarColor};'>{$initial}</div>";
                    $html .= "<div class='client-info-body'>";

                    $html .= "<div class='client-name-row'>";
                    $html .= "<span class='client-main-name' title='{$name}'>{$name}</span>{$statusDot}";
                    $html .= "</div>";

                    $metaParts = [];
                    if ($contact) {
                        $metaParts[] = "<a href='tel:{$contact}' class='client-meta-item client-phone' title='Call {$contact}'>"
                            . "<i class='fa fa-phone'></i><span>{$contact}</span></a>";
                    }
                    if ($package) {
                        $metaParts[] = "<span class='client-meta-item'><i class='fa fa-box'></i><span>{$package}</span></span>";
                    }
                    if (!empty($metaParts)) {
                        $html .= "<div class='client-meta-row'>" . implode('', $metaParts) . "</div>";
                    }

                    if ($address) {
                        $short = \Illuminate\Support\Str::limit($address, 35);
                        $html .= "<div class='client-address' title='{$address}'>"
                            . "<i class='fa fa-map-marker-alt'></i><span>{$short}</span></div>";
                    }

                    $html .= "</div></div>";
                    return $html;
                })
                ->addColumn('customer_id', function ($row) {
                    return $row->client->username ?? '-';
                })
                ->addColumn('month_year', function ($row) {
                    return $row->month_year;
                })
                ->addColumn('remaining', function ($row) {
                    return $row->remaining_balance;
                })
                ->addColumn('percentage', function ($row) {
                    return $row->payment_percentage . '%';
                })
                ->addColumn('status_badge', function ($row) {
                    $colors = [
                        'unpaid' => 'danger',
                        'partially_paid' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'secondary'
                    ];
                    $color = $colors[$row->status] ?? 'secondary';
                    return "<span class='badge bg-{$color}'>" . ucfirst(str_replace('_', ' ', $row->status)) . "</span>";
                })
                ->addColumn('action', function ($row) {
                    return "
                        <button class='btn btn-sm btn-primary' onclick=\"viewBill({$row->id})\"><i class='fa fa-eye'></i></button>
                        <button class='btn btn-sm btn-warning' onclick=\"editBill({$row->id})\"><i class='fa fa-edit'></i></button>
                        <button class='btn btn-sm btn-danger' onclick=\"deleteBill({$row->id})\"><i class='fa fa-trash'></i></button>
                    ";
                })
                ->rawColumns(['client_name', 'status_badge', 'action'])
                ->make(true);
        }

        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        $statuses = ['unpaid', 'partially_paid', 'paid', 'overdue'];

        $years = DueBill::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
        }

        $totalDue = DueBill::where('status', '!=', 'paid')->sum('amount') -
                           DueBill::where('status', '!=', 'paid')->sum('paid_amount');

        $unpaidCount = DueBill::unpaid()->count();
        $overdueCount = DueBill::overdue()->count();
        $paidThisMonth = DueBill::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return view('due_bills.index', compact(
            'clients',
            'statuses',
            'years',
            'months',
            'totalDue',
            'unpaidCount',
            'overdueCount',
            'paidThisMonth'
        ));
    }

    /**
     * Show form for creating a new due bill
     */
    public function create()
    {
        $clients = Client::select('id', 'name', 'username', 'status')->orderBy('name')->get();
        return view('due_bills.create', compact('clients'));
    }

    /**
     * Store a newly created due bill
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        // Check if bill already exists for this client, month, year
        $existing = DueBill::where('client_id', $validated['client_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if ($existing) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bill already exists for this client in the specified month.',
                ], 422);
            }

            return back()->withErrors(['message' => 'Bill already exists for this client in the specified month']);
        }

        $bill = DueBill::create($validated);

        // Send SMS notification
        try {
            $client = Client::findOrFail($validated['client_id']);
            $monthName = \Carbon\Carbon::createFromDate($validated['year'], $validated['month'], 1)->format('F Y');
            $message = "প্রিয় {$client->name},\n"
                . "গ্রাহক আইডি: {$client->username}\n"
                . "{$monthName} মাসের নতুন বিল: ৳" . number_format($validated['amount'], 2) . "\n"
                . config('sms.payment_instruction') . "\n"
                . config('sms.payment_methods') . ': ' . config('sms.payment_number')
                . "\n- " . ucfirst($client->isp_code);

            if ($client->contact) {
                sms($client->contact, $message);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the bill creation
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Due bill created successfully.',
                'bill_id' => $bill->id,
            ]);
        }

        return redirect()->route('due-bills.index')
            ->with('success', 'Due bill created successfully');
    }

    /**
     * Display the specified due bill
     */
    public function show($id)
    {
        $bill = DueBill::with('client', 'payments')->findOrFail($id);
        return view('due_bills.show', compact('bill'));
    }

    /**
     * Show form for editing due bill
     */
    public function edit($id)
    {
        $bill = DueBill::findOrFail($id);
        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        return view('due_bills.edit', compact('bill', 'clients'));
    }

    /**
     * Update the specified due bill
     */
    public function update(Request $request, $id)
    {
        $bill = DueBill::findOrFail($id);

        $validated = $request->validate([
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:unpaid,partially_paid,paid,overdue',
            'notes' => 'nullable|string'
        ]);

        $bill->update($validated);

        return redirect()->route('due-bills.show', $bill->id)
            ->with('success', 'Due bill updated successfully');
    }

    /**
     * Delete the specified due bill
     */
    public function destroy($id)
    {
        $bill = DueBill::findOrFail($id);
        $bill->delete();

        return response()->json(['success' => true, 'message' => 'Due bill deleted successfully']);
    }

    /**
     * Get client's due bills
     */
    public function clientBills($clientId)
    {
        $client = Client::findOrFail($clientId);
        $bills = $client->dueBills()->orderBy('due_date', 'desc')->paginate(20);

        return view('due_bills.client_bills', compact('client', 'bills'));
    }

    /**
     * Mark bill as paid
     */
    public function markAsPaid($id)
    {
        $bill = DueBill::with('client')->findOrFail($id);

        if ($bill->remaining_balance <= 0) {
            return redirect()->route('due-bills.invoice', ['id' => $bill->id, 'print' => 1])
                ->with('info', 'This bill is already fully paid.');
        }

        $remaining = $bill->remaining_balance;
        if ($remaining > 0) {
            \App\Models\DueBillPayment::create([
                'due_bill_id' => $bill->id,
                'client_id' => $bill->client_id,
                'payment_date' => now()->toDateString(),
                'amount' => $remaining,
                'payment_method' => 'cash',
                'notes' => 'Marked as paid'
            ]);
        }

        $bill->update([
            'status' => 'paid',
            'paid_amount' => $bill->amount
        ]);

        if ($bill->client && $bill->client->contact) {
            try {
                $message = "প্রিয় {$bill->client->name},\n"
                    . "গ্রাহক আইডি: {$bill->client->username}\n"
                    . $bill->month_year . ' মাসের বিল: ৳' . number_format($bill->amount, 2)
                    . "\nবিলটি সম্পূর্ণ পরিশোধ হয়েছে। ধন্যবাদ।\n- " . ucfirst($bill->client->isp_code);
                sms($bill->client->contact, $message);
            } catch (\Exception $e) {
                // Log error but don't fail
            }
        }

        return redirect()->route('due-bills.invoice', ['id' => $bill->id, 'print' => 1])
            ->with('success', 'Bill marked as paid successfully!');
    }

    /**
     * Display printable invoice for a due bill
     */
    public function invoice($id)
    {
        $bill = DueBill::with(['client', 'payments' => function ($q) {
            $q->orderBy('payment_date', 'asc')->orderBy('id', 'asc');
        }])->findOrFail($id);

        return view('due_bills.invoice', compact('bill'));
    }

    /**
     * Send a manual SMS reminder for an unpaid bill.
     */
    public function sendReminder($id)
    {
        $bill = DueBill::with('client')->findOrFail($id);

        if ($bill->sendReminder()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Payment reminder sent successfully.']);
            }

            return redirect()->back()->with('success', 'Payment reminder sent successfully.');
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Reminder was not sent. It may already have been sent today, or the client has no contact number.'
            ], 422);
        }

        return redirect()->back()->with('error', 'Reminder was not sent. The bill may be paid, already reminded today, or have no contact number.');
    }

    /**
     * Send reminders for multiple selected bills.
     */
    public function sendBulkReminders(Request $request)
    {
        $validated = $request->validate([
            'bill_ids' => 'required|array|min:1',
            'bill_ids.*' => 'integer|distinct|exists:due_bills,id',
        ]);

        $sent = 0;
        $skipped = 0;

        DueBill::with('client')
            ->whereIn('id', $validated['bill_ids'])
            ->get()
            ->each(function (DueBill $bill) use (&$sent, &$skipped) {
                if ($bill->sendReminder()) {
                    $sent++;
                } else {
                    $skipped++;
                }
            });

        return response()->json([
            'success' => $sent > 0,
            'sent' => $sent,
            'skipped' => $skipped,
            'message' => "{$sent} reminder(s) sent. {$skipped} skipped."
        ], $sent > 0 ? 200 : 422);
    }



    /**
     * Get unpaid bills for a client (API endpoint)
     */
    public function getBillsForClient($clientId)
    {
        try {
            // Get all bills for client (including paid ones for reference)
            $bills = DueBill::where('client_id', $clientId)
                ->orderBy('due_date', 'desc')
                ->get()
                ->map(function ($bill) {
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
                });

            return response()->json([
                'success' => true,
                'bills' => $bills,
                'total_count' => count($bills)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'total_count' => 0
            ], 500);
        }
    }

    /**
     * Generate and download bill statement PDF for a client
     */
    public function billStatementPdf($clientId)
    {
        try {
            $client = Client::findOrFail($clientId);
            $bills = DueBill::where('client_id', $clientId)
                ->with('payments')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

            $totalAmount = $bills->sum('amount');
            $totalPaid = $bills->sum('paid_amount');
            $totalRemaining = $totalAmount - $totalPaid;

            // Generate HTML
            $html = view('due_bills.statement-pdf', compact(
                'client',
                'bills',
                'totalAmount',
                'totalPaid',
                'totalRemaining'
            ))->render();

            // Return as downloadable PDF using browser print
            return response($html, 200, [
                'Content-Type' => 'text/html; charset=utf-8',
                'Content-Disposition' => 'inline; filename="bill-statement-' . $client->username . '.html"'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating bill statement: ' . $e->getMessage());
        }
    }

    /**
     * Show report of due bills with advanced filtering
     */
    public function report(Request $request)
    {
        if ($request->ajax()) {
            $query = DueBill::with('client')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderByRaw('(amount - paid_amount) DESC');

            // Apply Filters
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('month')) {
                $query->where('month', $request->month);
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            // For summary calculations (independent of pagination)
            $summaryQuery = clone $query;
            $totalAmount = $summaryQuery->sum('amount');
            $totalPaid = $summaryQuery->sum('paid_amount');
            $totalDue = $totalAmount - $totalPaid;

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('client_name', function ($row) {
                    return $row->client->name ?? '-';
                })
                ->addColumn('customer_id', function ($row) {
                    return $row->client->username ?? '-';
                })
                ->addColumn('contact', function ($row) {
                    return $row->client->contact ?? '-';
                })
                ->addColumn('address', function ($row) {
                    return $row->client->address ?? '-';
                })
                ->addColumn('month_year', function ($row) {
                    return $row->month_year;
                })
                ->addColumn('remaining', function ($row) {
                    return $row->remaining_balance;
                })
                ->addColumn('status_badge', function ($row) {
                    $colors = [
                        'unpaid' => 'danger',
                        'partially_paid' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'secondary'
                    ];
                    $color = $colors[$row->status] ?? 'secondary';
                    return "<span class='badge bg-{$color}'>" . ucfirst(str_replace('_', ' ', $row->status)) . "</span>";
                })
                ->with([
                    'totalAmount' => number_format($totalAmount, 2),
                    'totalPaid' => number_format($totalPaid, 2),
                    'totalDue' => number_format($totalDue, 2),
                ])
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        $years = DueBill::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
        }

        return view('due_bills.report', compact('clients', 'years', 'months'));
    }

    /**
     * Send bill details to Telegram
     */
    public function sendTelegramNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        try {
            // Replace <br> or HTML tags with appropriate newlines or tags for Telegram
            $msg = $request->message;
            $msg = str_replace(['<br>', '<br/>', '<br />'], "\n", $msg);

            // Strip any unsupported HTML tags but leave bold/code/italic/a tags supported by Telegram HTML parse_mode
            $msg = strip_tags($msg, '<b><strong><i><em><u><ins><s><strike><del><code><pre><a>');

            // Use dedicated billing channel ID if configured in env, else fallback to standard chat ID
            $channelId = env('TELEGRAM_BILLING_CHANNEL_ID') ?? env('TELEGRAM_CHAT_ID');

            $status = sendTelegram($msg, $channelId);
            if ($status) {
                return response()->json(['success' => true, 'message' => 'Message sent to Telegram Channel!']);
            }
            return response()->json(['success' => false, 'message' => 'Failed to send message.'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}