<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOfficeExpenseRequest;
use App\Http\Requests\UpdateOfficeExpenseRequest;
use App\Models\OfficeExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class OfficeExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $query = $this->filteredQuery($request);
        $stats = $this->expenseStats($query);
        $categoryBreakdown = $this->categoryBreakdown($query);
        $recentExpenses = OfficeExpense::with('creator')
            ->orderBy('expense_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $currentYear = Carbon::today('Asia/Dhaka')->year;

        return view('expenses.index', [
            'expenses' => $query->orderBy('expense_date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString(),
            'filters' => $this->filterValues($request),
            'stats' => $stats,
            'categoryBreakdown' => $categoryBreakdown,
            'recentExpenses' => $recentExpenses,
            'categories' => OfficeExpense::categories(),
            'paymentMethods' => OfficeExpense::paymentMethods(),
        ]);
    }

    public function create()
    {
        return view('expenses.create', [
            'categories' => OfficeExpense::categories(),
            'paymentMethods' => OfficeExpense::paymentMethods(),
            'today' => Carbon::today()->format('Y-m-d'),
        ]);
    }

    public function store(CreateOfficeExpenseRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $data['receipt_path'] = $this->storeReceipt($request->file('receipt'));
        }

        unset($data['receipt']);
        OfficeExpense::create($data);

        return redirect()->route('office-expenses.index')
            ->with('success', 'Daily expense added successfully.');
    }

    public function show(OfficeExpense $officeExpense)
    {
        return view('expenses.show', ['expense' => $officeExpense]);
    }

    public function edit(OfficeExpense $officeExpense)
    {
        return view('expenses.edit', [
            'expense' => $officeExpense,
            'categories' => OfficeExpense::categories(),
            'paymentMethods' => OfficeExpense::paymentMethods(),
            'submitRoute' => route('office-expenses.update', $officeExpense),
        ]);
    }

    public function update(OfficeExpense $officeExpense, UpdateOfficeExpenseRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['remove_receipt'])) {
            $this->deleteReceipt($officeExpense->receipt_path);
            $officeExpense->receipt_path = null;
        }

        if ($request->hasFile('receipt')) {
            $this->deleteReceipt($officeExpense->receipt_path);
            $data['receipt_path'] = $this->storeReceipt($request->file('receipt'));
        }

        unset($data['receipt'], $data['remove_receipt']);
        $officeExpense->update($data);

        return redirect()->route('office-expenses.index')
            ->with('success', 'Daily expense updated successfully.');
    }

    public function destroy(OfficeExpense $officeExpense)
    {
        $this->deleteReceipt($officeExpense->receipt_path);
        $officeExpense->delete();

        return redirect()->route('office-expenses.index')
            ->with('success', 'Daily expense deleted successfully.');
    }

    public function report(Request $request)
    {
        $query = $this->filteredQuery($request);
        $expenses = $query->orderBy('expense_date', 'desc')->orderBy('id', 'desc')
            ->paginate(20)->withQueryString();
        $stats = $this->expenseStats($query);

        $currentYear = Carbon::today('Asia/Dhaka')->year;
        $monthlyExpenses = OfficeExpense::select(
            DB::raw('MONTH(expense_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->whereYear('expense_date', $currentYear)
            ->groupBy(DB::raw('MONTH(expense_date)'))
            ->orderBy('month')
            ->get();

        return view('expenses.report', [
            'expenses' => $expenses,
            'filters' => $this->filterValues($request),
            'stats' => $stats,
            'categoryBreakdown' => $this->categoryBreakdown($query),
            'paymentBreakdown' => $this->paymentBreakdown($query),
            'categories' => OfficeExpense::categories(),
            'paymentMethods' => OfficeExpense::paymentMethods(),
            'monthlyExpenses' => $monthlyExpenses,
        ]);
    }

    public function export(Request $request)
    {
        $expenses = $this->filteredQuery($request)
            ->orderBy('expense_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $filename = 'office-expenses-' . Carbon::today()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($expenses) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Date', 'Category', 'Title', 'Description', 'Amount', 'Payment Method', 'Reference', 'Notes', 'Added By']);
            foreach ($expenses as $expense) {
                fputcsv($output, [
                    $expense->expense_date->format('Y-m-d'),
                    $expense->category_label,
                    $expense->title,
                    $expense->description,
                    number_format($expense->amount, 2, '.', ''),
                    $expense->payment_method_label,
                    $expense->reference_number,
                    $expense->notes,
                    $expense->creator ? $expense->creator->name : '',
                ]);
            }
            fclose($output);
        }, $filename, $headers);
    }

    public function downloadReceipt(OfficeExpense $officeExpense)
    {
        if (!$officeExpense->receipt_path || !Storage::disk('public')->exists($officeExpense->receipt_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $officeExpense->receipt_path,
            'expense-receipt-' . $officeExpense->id . '.' . pathinfo($officeExpense->receipt_path, PATHINFO_EXTENSION)
        );
    }

    private function datatable(Request $request)
    {
        $query = $this->filteredQuery($request)
            ->orderBy('expense_date', 'desc')
            ->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('expense_date_formatted', function ($expense) {
                return $expense->expense_date->format('d M, Y');
            })
            ->addColumn('category_badge', function ($expense) {
                return '<span class="expense-badge category-' . e($expense->category) . '">' . e($expense->category_label) . '</span>';
            })
            ->addColumn('amount_formatted', function ($expense) {
                return '<span class="expense-amount">' . e(isp_setting('currency_symbol', '৳')) . ' ' . number_format($expense->amount, 2) . '</span>';
            })
            ->addColumn('payment_method_badge', function ($expense) {
                return '<span class="expense-badge method-' . e($expense->payment_method) . '">' . e($expense->payment_method_label) . '</span>';
            })
            ->addColumn('creator_name', function ($expense) {
                return $expense->creator ? e($expense->creator->name) : '<span class="text-muted">System</span>';
            })
            ->addColumn('receipt_indicator', function ($expense) {
                if (!$expense->receipt_path) {
                    return '<span class="text-muted">-</span>';
                }
                return '<a href="' . route('office-expenses.receipt', $expense) . '" class="expense-receipt-link" target="_blank" rel="noopener"><i class="fas fa-paperclip"></i> Receipt</a>';
            })
            ->addColumn('action', function ($expense) {
                return '<div class="expense-actions">' .
                    '<a href="' . route('office-expenses.show', $expense) . '" class="expense-action expense-action-view" title="View"><i class="fas fa-eye"></i></a>' .
                    '<a href="' . route('office-expenses.edit', $expense) . '" class="expense-action expense-action-edit" title="Edit"><i class="fas fa-edit"></i></a>' .
                    '<form action="' . route('office-expenses.destroy', $expense) . '" method="POST" class="expense-delete-form" onsubmit="return confirm(\'Are you sure you want to delete this expense?\')">' .
                    method_field('DELETE') . csrf_field() .
                    '<button type="submit" class="expense-action expense-action-delete" title="Delete"><i class="fas fa-trash"></i></button>' .
                    '</form></div>';
            })
            ->rawColumns(['category_badge', 'amount_formatted', 'payment_method_badge', 'creator_name', 'receipt_indicator', 'action'])
            ->make(true);
    }

    private function filteredQuery(Request $request)
    {
        $query = OfficeExpense::query()->with('creator');

        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $search = $request->input('search.value') ?: $request->input('search');
        if ($search) {
            $query->where(function ($nested) use ($search) {
                $nested->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('reference_number', 'like', '%' . $search . '%')
                    ->orWhere('notes', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    private function expenseStats($query)
    {
        $total = (float) $query->sum('amount');
        $count = (int) $query->count();
        $today = (float) (clone $query)->whereDate('expense_date', Carbon::today())->sum('amount');
        $month = (float) (clone $query)
            ->whereYear('expense_date', Carbon::today()->year)
            ->whereMonth('expense_date', Carbon::today()->month)
            ->sum('amount');

        return [
            'total' => $total,
            'count' => $count,
            'today' => $today,
            'month' => $month,
            'average' => $count > 0 ? $total / $count : 0,
        ];
    }

    private function categoryBreakdown($query)
    {
        return (clone $query)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();
    }

    private function paymentBreakdown($query)
    {
        return (clone $query)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();
    }

    private function filterValues(Request $request)
    {
        return [
            'from_date' => $request->input('from_date', ''),
            'to_date' => $request->input('to_date', ''),
            'category' => $request->input('category', ''),
            'payment_method' => $request->input('payment_method', ''),
            'search' => $request->input('search.value', $request->input('search', '')),
        ];
    }

    private function storeReceipt($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'receipt_' . Carbon::now()->format('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        return $file->storeAs('office-expenses/receipts', $filename, 'public');
    }

    private function deleteReceipt($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
