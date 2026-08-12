<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Repositories\ClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Imports\ClientsImport;
use App\Models\Client;
use App\Models\Package;
use App\Models\SMS_TEMPALTE;
use Maatwebsite\Excel\Facades\Excel;

use Yajra\DataTables\DataTables;

class ClientController extends AppBaseController
{
    /** @var ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Client::query()
                ->withCount('comments')
                ->with('latestComment');

            if ($request->filled('status_filter')) {
                $status = $request->status_filter;
                if ($status === 'Expired') {
                    $data->where(function($q) {
                        $q->where('status', 'Expired')
                          ->orWhere(function($sub) {
                              $sub->whereNotNull('expiration')->where('expiration', '<', now());
                          });
                    });
                } else {
                    $data->where('status', $status);
                }
            }

            if ($request->filled('isp_filter')) {
                $data->where('isp_code', strtolower($request->isp_filter));
            }

            if ($request->filled('due_filter')) {
                if ($request->due_filter === 'has_due') {
                    $data->whereIn('id', function($q) {
                        $q->select('client_id')
                          ->from('due_bills')
                          ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
                          ->whereRaw('(amount - paid_amount) > 0');
                    });
                } elseif ($request->due_filter === 'no_due') {
                    $data->whereNotIn('id', function($q) {
                        $q->select('client_id')
                          ->from('due_bills')
                          ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
                          ->whereRaw('(amount - paid_amount) > 0');
                    });
                }
            }

            if ($request->filled('onu_filter') && $request->onu_filter === 'free') {
                $data->where('onu_free', 1);
            }

            if ($request->filled('cable_filter') && $request->cable_filter === 'returned') {
                $data->where('cable_returned', 1);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('total_due', function ($client) {
                    try {
                        $due = DB::table('due_bills')
                            ->where('client_id', $client->id)
                            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
                            ->selectRaw('SUM(amount - paid_amount) as total')
                            ->value('total') ?? 0;

                        if ($due > 0) {
                            return '<span class="badge bg-danger" style="font-size: 0.9rem;">৳ ' . number_format($due, 0) . '</span>';
                        }
                        return '<span class="text-success">✓ Paid</span>';
                    } catch (\Exception $e) {
                        return '-';
                    }
                })
                ->addColumn('action', function ($client) {
                    $name    = addslashes($client->name);
                    $contact = addslashes($client->contact);
                    $address = addslashes($client->address ?? '');
                    $commentCount = $client->comments_count ?? 0;

                    $viewUrl       = route('clients.show', $client->id);
                    $editUrl       = route('clients.edit', $client->id);
                    $billsUrl      = route('clients.due-bills', $client->id);
                    $addPaymentUrl = route('due-bill-payments.create', ['client_id' => $client->id]);

                    $commentBadge = $commentCount > 0
                        ? "<span class=\"badge rounded-pill bg-primary\" style=\"font-size:0.68rem; padding: 2px 6px;\">$commentCount</span>"
                        : '';

                    $btn = <<<EOT
                    <div class="btn-group btn-group-sm" role="group" style="position: relative;">
                        <a href="{$editUrl}" class="btn btn-sm border-0 px-2" title="Edit Client" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; background:#fffbebf0; color:#d97706; border:1px solid #cbd5e1 !important; border-end-0 !important;" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='#fffbebf0'">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm border-0 px-2 d-inline-flex align-items-center gap-1" title="Comments & Notes" onclick="openCommentModal({$client->id}, '{$name}')" style="border-radius:0; background:#f0f9fff0; color:#0284c7; border:1px solid #cbd5e1 !important; border-end-0 !important;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9fff0'">
                            <i class="fa fa-comment"></i> {$commentBadge}
                        </button>
                        <button type="button" class="btn btn-sm border-0 px-2 dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; background:#f8fafcf0; color:#64748b; border:1px solid #cbd5e1 !important;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f8fafcf0'" title="More Options">
                            <span class="visually-hidden">Toggle Options</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius:10px; font-size:0.85rem; min-width: 175px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item py-2" href="{$viewUrl}">
                                    <i class="fa fa-eye text-primary me-2"></i> View Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" onclick="showQuickBillModal({$client->id}, '{$name}', '{$contact}', '{$address}')">
                                    <i class="fas fa-receipt text-warning me-2"></i> Quick Bill
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{$addPaymentUrl}">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i> Add Payment
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{$billsUrl}">
                                    <i class="fas fa-file-invoice text-info me-2"></i> View Due Bills
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2" href="#" onclick="showQr({$client->id}, '{$name}', '{$contact}')">
                                    <i class="fas fa-qrcode text-dark me-2"></i> Show QR Code
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" data-bs-toggle="modal" onclick="setSmsId({$client->id})" data-bs-target="#smsModal">
                                    <i class="fa fa-paper-plane me-2" style="color:#6366f1;"></i> Send SMS
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" onclick="openCommentModal({$client->id}, '{$name}')">
                                    <i class="fas fa-comments me-2" style="color:#06b6d4;"></i> Client Notes {$commentBadge}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger fw-semibold" href="#" onclick="deleteClient({$client->id})" data-id="{$client->id}">
                                    <i class="fa fa-trash me-2"></i> Delete Client
                                </a>
                            </li>
                        </ul>
                    </div>
EOT;
                    return $btn;
                })
                ->addColumn('latest_comment', function ($client) {
                    $latest = $client->latestComment;
                    if (!$latest) return '<span class="text-muted" style="font-size:0.78rem;">—</span>';

                    $typeColors = [
                        'note'    => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '📝'],
                        'info'    => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'icon' => 'ℹ️'],
                        'success' => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '✅'],
                        'alert'   => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '⚠️'],
                    ];
                    $tc       = $typeColors[$latest->type] ?? $typeColors['note'];
                    $body     = e(mb_strimwidth($latest->body, 0, 55, '…'));
                    $author   = e($latest->author_name);
                    $timeAgo  = e($latest->time_ago);
                    $clientId = $client->id;
                    $clientName = addslashes($client->name);

                    return "
                        <div class='lc-cell' onclick=\"openCommentModal({$clientId}, '{$clientName}')\" title=\"Click to view all comments\">
                            <div class='lc-badge' style='background:{$tc['bg']};color:{$tc['color']};'>{$tc['icon']}</div>
                            <div class='lc-content'>
                                <span class='lc-body'>{$body}</span>
                                <span class='lc-meta'>{$author} · {$timeAgo}</span>
                            </div>
                        </div>";
                })
                ->rawColumns(['action', 'total_due', 'latest_comment', 'expiration'])
                ->editColumn('expiration', function ($row) {
                    if (empty($row->expiration)) {
                        return '<span class="badge bg-secondary text-white px-2 py-1" style="font-weight:500; font-size:0.75rem;">N/A</span>';
                    }

                    try {
                        // Ensure it's a Carbon instance
                        $dt = $row->expiration instanceof \Carbon\Carbon
                            ? $row->expiration
                            : \Carbon\Carbon::parse($row->expiration);

                        // Convert to Asia/Dhaka timezone
                        $dt = $dt->copy()->setTimezone('Asia/Dhaka');
                        $dateStr = $dt->format('d-m-Y');
                        $human   = $dt->diffForHumans();

                        return '<span class="fw-semibold text-dark">' . $dateStr . '</span> <small class="text-muted text-nowrap">/ ' . $human . '</small>';
                    } catch (\Exception $e) {
                        return '<span class="badge bg-secondary text-white px-2 py-1" style="font-weight:500; font-size:0.75rem;">N/A</span>';
                    }
                })
                ->editColumn('isp_code', function ($row) {
                    return ucfirst($row->isp_code);
                })
                ->make(true);
        }

        $templates = SMS_TEMPALTE::all();

        $ActiveClientsCount        = Client::where('status', 'Active')->count();
        $expiredClientsCount       = Client::whereNotNull('expiration')->where('expiration', '<', now())->count();
        $freeOnuClientsCount       = Client::where('onu_free', 1)->count();
        $cableReturnedClientsCount = Client::where('cable_returned', 1)->count();

        // Billing statistics
        $totalDueAmount = DB::table('due_bills')
            ->where('status', '!=', 'paid')
            ->selectRaw('SUM(amount - paid_amount) as total')
            ->value('total') ?? 0;

        $unpaidBillsCount = DB::table('due_bills')
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->count();

        $overdueBillsCount = DB::table('due_bills')
            ->where('due_date', '<', now()->toDateString())
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->count();

        // ISP-wise active clients count
        $ispWiseActiveClients = Client::where('status', 'Active')
            ->selectRaw('isp_code, COUNT(*) as count')
            ->groupBy('isp_code')
            ->get()
            ->pluck('count', 'isp_code');

        return view('clients.index', compact(
            'templates',
            'ActiveClientsCount',
            'expiredClientsCount',
            'freeOnuClientsCount',
            'cableReturnedClientsCount',
            'totalDueAmount',
            'unpaidBillsCount',
            'overdueBillsCount',
            'ispWiseActiveClients'
        ));
    }

    /**
     * Show the form for creating a new Client.
     */
    public function create()
    {
        $packages = Package::all();
        return view('clients.create', compact('packages'));
    }

    /**
     * Show the import form.
     */
    public function create_import()
    {
        return view('clients.import');
    }

    /**
     * Get client's package price via AJAX
     */
    public function getPackagePrice($clientId)
    {
        try {
            $client = Client::findOrFail($clientId);
            $package = Package::where('title', $client->package)->first();

            if ($package) {
                return response()->json([
                    'success' => true,
                    'price' => $package->price,
                    'package_name' => $package->title
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bulk bill info (package rates & current month bill status) for selected clients
     */
    public function getBulkBillInfo(Request $request)
    {
        try {
            $clientIds = $request->input('client_ids', []);
            if (empty($clientIds)) {
                return response()->json(['success' => false, 'message' => 'No clients selected'], 400);
            }

            $currentMonth = now()->month;
            $currentYear  = now()->year;

            $clients = Client::whereIn('id', $clientIds)->get();
            $packages = Package::all()->keyBy('title');

            $existingBillClientIds = DB::table('due_bills')
                ->whereIn('client_id', $clientIds)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->pluck('client_id')
                ->toArray();

            $result = [];
            foreach ($clients as $client) {
                $pkg = $packages->get($client->package);
                $result[] = [
                    'id' => $client->id,
                    'name' => $client->name,
                    'username' => $client->username,
                    'contact' => $client->contact ?? '',
                    'package_name' => $client->package ?? 'N/A',
                    'price' => $pkg ? (float)$pkg->price : 0,
                    'has_existing_bill' => in_array($client->id, $existingBillClientIds),
                ];
            }

            return response()->json([
                'success' => true,
                'clients' => $result,
                'current_month_name' => now()->format('F Y'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created Client in storage.
     */
    public function store(CreateClientRequest $request)
    {
        $input  = $request->all();
        $client = $this->clientRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    /**
     * Display the specified Client.
     */
    public function show($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));
            return redirect(route('clients.index'));
        }

        return view('clients.show')->with('client', $client);
    }

    /**
     * Show the form for editing the specified Client.
     */
    public function edit($id)
    {
        $client   = $this->clientRepository->find($id);
        $packages = Package::all();

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));
            return redirect(route('clients.index'));
        }

        return view('clients.edit', compact('client', 'packages'));
    }

    /**
     * Update the specified Client in storage.
     */
    public function update($id, UpdateClientRequest $request)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));
            return redirect(route('clients.index'));
        }

        $this->clientRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified Client from storage with password verification.
     */
    public function destroy($id)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $password = request('password');

            if (!$password) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Password is required for verification.'
                ], 400);
            }

            if (!Hash::check($password, Auth::user()->password)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Password verification failed.'
                ], 401);
            }
        }

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => __('messages.not_found', ['model' => __('models/clients.singular')])
                ], 404);
            }
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));
            return redirect(route('clients.index'));
        }

        $this->clientRepository->delete($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => __('messages.deleted', ['model' => __('models/clients.singular')])
            ]);
        }

        Flash::success(__('messages.deleted', ['model' => __('models/clients.singular')]));
        return redirect(route('clients.index'));
    }

    /**
     * Get ISP-wise statistics - API endpoint for AJAX requests
     */
    public function getIspStatistics(Request $request)
    {
        $ispCode = $request->input('isp_code');

        if (!$ispCode) {
            return response()->json(['error' => 'ISP code is required'], 400);
        }

        // Get all counts for the specific ISP
        $ispClientsActive = Client::where('isp_code', $ispCode)
            ->where('status', 'Active')
            ->count();

        $ispClientsTotal = Client::where('isp_code', $ispCode)
            ->count();

        $ispClientsExpired = Client::where('isp_code', $ispCode)
            ->where(function($q) {
                $q->where('status', '!=', 'Active')
                  ->orWhereNotNull('expiration')
                  ->where('expiration', '<', now());
            })
            ->count();

        $ispClientsFreeONU = Client::where('isp_code', $ispCode)
            ->where('onu_free', 1)
            ->count();

        $ispClientsCableReturned = Client::where('isp_code', $ispCode)
            ->where('cable_returned', 1)
            ->count();

        // Calculate market share
        $totalActive = Client::where('status', 'Active')->count();
        $percentage = $totalActive > 0 ? round(($ispClientsActive / $totalActive) * 100, 1) : 0;

        return response()->json([
            'isp_code' => $ispCode,
            'active_clients' => $ispClientsActive,
            'total_clients' => $ispClientsTotal,
            'expired_clients' => $ispClientsExpired,
            'free_onu_clients' => $ispClientsFreeONU,
            'cable_returned_clients' => $ispClientsCableReturned,
            'market_share_percentage' => $percentage
        ]);
    }

    /**
     * Export all clients to Excel.
     */
    public function export()
    {
        return Excel::download(new ClientExport, 'clients.xlsx');
    }

    /**
     * Import clients from Excel file.
     * Tracks: new inserts, changed fields, skipped rows.
     */
    public function import(Request $request)
    {
        // 1. Validate file and ISP
        $request->validate([
            'clients_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'isp_code'     => 'required|in:carnival,bijoy,icc',
        ]);

        $file     = $request->file('clients_file');
        $isp_code = strtolower(trim($request->input('isp_code')));

        // 2. Clear all previous session logs before import
        session()->forget([
            'debug_logs',
            'import_errors',
            'import_changes',
            'import_new',
        ]);

        try {
            Excel::import(new ClientsImport($isp_code), $file);

            // 3. Collect results from session (set by ClientsImport)
            $errors  = session('import_errors', []);
            $changes = session('import_changes', []);
            $newRows = session('import_new', []);

            // 4. Summary flash message
            Flash::success(sprintf(
                'Import done for %s — %d new, %d field(s) updated, %d skipped.',
                ucfirst($isp_code),
                count($newRows),
                count($changes),
                count($errors)
            ));

            // 5. Pass all result data back to the view
            return back()->with([
                'isp_code'       => $isp_code,
                'import_errors'  => $errors,
                'import_changes' => $changes,
                'import_new'     => $newRows,
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Row-level Excel validation failures
            $failures = collect($e->failures())->map(fn($f) =>
                "Row {$f->row()}: " . implode(', ', $f->errors())
            )->toArray();

            Flash::error('Import failed — validation errors found.');
            return back()->with('import_errors', $failures);

        } catch (\Exception $e) {
            // Fatal error — bad file, wrong format, DB error etc.
            Flash::error('Import failed: ' . $e->getMessage());
            return back()->with('import_error_fatal', $e->getMessage());
        }
    }

    /**
     * Truncate all clients.
     */
    public function erase()
    {
        Client::query()->truncate();
        Flash::success('Truncate Successful');
        return back();
    }
}
