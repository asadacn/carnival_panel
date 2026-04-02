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
                ->addColumn('action', function ($client) {
                    $name    = addslashes($client->name);
                    $contact = addslashes($client->contact);

                    $viewUrl = route('clients.show', $client->id);
                    $editUrl = route('clients.edit', $client->id);

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
                                <a class="dropdown-item text-danger" href="#" onclick="deleteClient({$client->id})" data-id="{$client->id}">
                                    <i class="fa fa-trash me-2"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
EOT;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->editColumn('expiration', function ($row) {
                    if (!$row->expiration) return '-';
                    $dt = optional($row->expiration)->copy()->setTimezone('Asia/Dhaka');
                    return $dt ? $dt->format('d-m-Y') . ' / ' . $dt->diffForHumans() : '-';
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

        return view('clients.index', compact(
            'templates',
            'ActiveClientsCount',
            'expiredClientsCount',
            'freeOnuClientsCount',
            'cableReturnedClientsCount'
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
