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
    /** @var  ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // OPTIMIZATION: Use Client::query() to enable server-side processing
            $data = Client::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($client) {
                    $name = addslashes($client->name);
                    $contact = addslashes($client->contact);

                    // 🚀 UI/UX FIX: Generate URLs for cleaner dropdown
                    $viewUrl = route('clients.show', $client->id);
                    $editUrl = route('clients.edit', $client->id);

                    // 🚀 UI/UX FIX: Use a dropdown for less common actions to de-clutter
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
                                <a class="dropdown-item" href="{$viewUrl}" title="View">
                                    <i class="fa fa-eye me-2"></i> View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="showQr({$client->id}, '{$name}', '{$contact}')" title="QR Code & WhatsApp">
                                    <i class="fas fa-qrcode me-2"></i> Show QR
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" onclick="setSmsId({$client->id})" data-bs-target="#smsModal" title="Send SMS">
                                    <i class="fa fa-envelope me-2"></i> Send SMS
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteClient({$client->id})" data-id="{$client->id}" title="Delete">
                                    <i class="fa fa-trash me-2"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
EOT;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->editColumn('expiration', function($row) {
                    if (! $row->expiration) return '-';
                    // Using optional helper for safe access
                    $dt = optional($row->expiration)->copy()->setTimezone('Asia/Dhaka');
                    return $dt ? $dt->format('d-m-Y') . ' / ' . $dt->diffForHumans() : '-';
                })
                ->make(true);
        }

        $templates = SMS_TEMPALTE::all();

        return view('clients.index', compact('templates'));
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        $packages = Package::all();
        return view('clients.create', compact('packages'));
    }


    public function create_import()
    {
        return view('clients.import');
    }


    /**
     * Store a newly created Client in storage.
     *
     * @param CreateClientRequest $request
     *
     * @return Response
     */
    public function store(CreateClientRequest $request)
    {
        $input = $request->all();

        $client = $this->clientRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    /**
     * Display the specified Client.
     *
     * @param int $id
     *
     * @return Response
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
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $client = $this->clientRepository->find($id);
        $packages = Package::all();

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        return view('clients.edit', compact('client', 'packages'));
    }

    /**
     * Update the specified Client in storage.
     *
     * @param int $id
     * @param UpdateClientRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClientRequest $request)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        $client = $this->clientRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified Client from storage with password verification.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response | \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Check for AJAX request to perform password verification
        if (request()->ajax() || request()->wantsJson()) {
            $password = request('password');

            // 1. Password Presence Check
            if (!$password) {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Password is required for verification.'
                ], 400); // Bad Request
            }

            // 2. Password Match Check
            $user = Auth::user();
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password verification failed. The provided password is incorrect.'
                ], 401); // Unauthorized
            }
        }

        // 3. Find Client
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('messages.not_found', ['model' => __('models/clients.singular')])
                ], 404);
            }
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));
            return redirect(route('clients.index'));
        }

        // 4. Delete Client
        $this->clientRepository->delete($id);

        // 5. Return Response
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => __('messages.deleted', ['model' => __('models/clients.singular')])
            ]);
        }

        // Fallback for non-AJAX requests
        Flash::success(__('messages.deleted', ['model' => __('models/clients.singular')]));
        return redirect(route('clients.index'));
    }

    public function export()
    {
        return Excel::download(new ClientExport, 'clients.xlsx');
    }

    public function import()
    {
        if (request()->file('clients_file')) {
            if (Excel::import(new ClientsImport, request()->file('clients_file'))) {
                Flash::success(__('Import Successful'));
                return back()->with('success', 'All good!');
            } else {
                Flash::error(__('Import Failed'));
                return back()->with('error', 'Import Failed');
            }
        } else {
            Flash::warning(__('Please select a file first!'));
            return back()->with('error', 'Please select a file first!');
        }
    }


    public function erase()
    {
        Client::query()->truncate();
        flash('Truncate Successful')->success();
        return back();
    }
}
