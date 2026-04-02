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
            $data = Client::query();

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

                    $viewUrl = route('clients.show', $client->id);
                    $editUrl = route('clients.edit', $client->id);
                    $billsUrl = route('clients.due-bills', $client->id);
                    $addPaymentUrl = route('due-bill-payments.create', ['client_id' => $client->id]);

                    $btn = <<<EOT
                    <div class="btn-group">
                        <a href="{$editUrl}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{$viewUrl}">
                                    <i class="fa fa-eye me-2"></i> View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="showQr({$client->id}, '{$name}', '{$contact}')">
                                    <i class="fas fa-qrcode me-2"></i> Show QR
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" onclick="setSmsId({$client->id})" data-bs-target="#smsModal">
                                    <i class="fa fa-envelope me-2"></i> Send SMS
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="showQuickBillModal({$client->id}, '{$name}')">
                                    <i class="fas fa-receipt me-2"></i> Quick Bill
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{$billsUrl}">
                                    <i class="fas fa-receipt me-2"></i> View Bills
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{$addPaymentUrl}">
                                    <i class="fas fa-money-bill-wave me-2"></i> Add Payment
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteClient({$client->id})" data-id="{$client->id}">
                                    <i class="fa fa-trash me-2"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
EOT;
                    return $btn;
                })
                ->rawColumns(['action', 'total_due'])
                ->editColumn('expiration', function ($row) {
                    if (empty($row->expiration)) return '-';

                    try {
                        // Ensure it's a Carbon instance
                        $dt = $row->expiration instanceof \Carbon\Carbon
                            ? $row->expiration
                            : \Carbon\Carbon::parse($row->expiration);

                        // Convert to Asia/Dhaka timezone
                        $dt = $dt->copy()->setTimezone('Asia/Dhaka');
                        return $dt->format('d-m-Y') . ' / ' . $dt->diffForHumans();
                    } catch (\Exception $e) {
                        return '-';
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
