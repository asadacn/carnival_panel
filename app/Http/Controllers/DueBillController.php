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
            $query = DueBill::query()
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
            if ($request->filled('month') && $request->filled('year')) {
                $query->where('month', $request->month)
                      ->where('year', $request->year);
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
                    return $row->client->name ?? '-';
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
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $clients = Client::select('id', 'name', 'username')->orderBy('name')->get();
        $statuses = ['unpaid', 'partially_paid', 'paid', 'overdue'];

        $totalDue = DueBill::where('status', '!=', 'paid')->sum('amount') -
                           DueBill::where('status', '!=', 'paid')->sum('paid_amount');

        $unpaidCount = DueBill::unpaid()->count();
        $overdueCount = DueBill::overdue()->count();
        $paidThisMonth = DueBill::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return view('due_bills.index', compact('clients', 'statuses', 'totalDue', 'unpaidCount', 'overdueCount', 'paidThisMonth'));
    }

    /**
     * Show form for creating a new due bill
     */
    public function create()
    {
        $clients = Client::where('status', 'Active')->select('id', 'name', 'username')->orderBy('name')->get();
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
            return back()->withErrors(['message' => 'Bill already exists for this client in the specified month']);
        }

        $bill = DueBill::create($validated);

        // Send SMS notification
        try {
            $client = Client::findOrFail($validated['client_id']);
            $monthName = \Carbon\Carbon::createFromDate($validated['year'], $validated['month'], 1)->format('F Y');
            $message = "Bill notification: A bill of ৳" . number_format($validated['amount']) . " has been created for {$monthName}. Due date: " . $validated['due_date'];

            if ($client->contact) {
                sms($client->contact, $message);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the bill creation
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
        $bill = DueBill::findOrFail($id);
        $bill->update([
            'status' => 'paid',
            'paid_amount' => $bill->amount
        ]);

        return redirect()->route('due-bills.show', $bill->id)
            ->with('success', 'Bill marked as paid successfully!');
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
}
