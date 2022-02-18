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
use App\Http\Controllers\Form;
use App\Exports\UsersExport;
use App\Imports\ClientsImport;
use App\Models\Client;

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
        // $clients = $this->clientRepository->all();

        // return view('clients.index')
        //     ->with('clients', $clients);

        if($request->ajax()){

            $data = Client::all();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('action', function($client){

                 $btn = '<div class="btn-group btn-group-toggle" data-toggle="buttons" >';
                 $btn = $btn.'<a href="#" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary action-btn"><i class="fa fa-envelope"></i></a>';
                 $btn = $btn.'<a href="'. route('clients.show', [$client->id]).'" class="btn btn-light action-btn"><i class="fa fa-eye"></i></a>';
                 $btn = $btn .'<a href="'.route('clients.edit', [$client->id]).'" class="btn btn-warning action-btn edit-btn"><i class="fa fa-edit"></i></a>';
                 $btn = $btn. ' <a href="'.route('clients.destroy', [$client->id]). '" onclick="return confirm(\'Are you sure?\')"   data-id="'.$client->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteClient"><i class="fa fa-trash"></i></a>';
                 $btn = $btn.'</div>';
                 return $btn;
         })
         ->rawColumns(['action'])
         ->make(true);
        }

        return view('clients.index');
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        return view('clients.create');
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

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        return view('clients.edit')->with('client', $client);
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
     * Remove the specified Client from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        $this->clientRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    public function export()
    {


        return Excel::download(new ClientExport, 'clients.xlsx');
    }

    public function import()
    {
        Excel::import(new ClientsImport, request()->file('clients_file'));
        Flash::success(__('Import Successfull'));
        return back()->with('success', 'All good!');
    }


    public function erase()
    {
      Client::query()->truncate();
      flash('Truncate Successfull')->success();
      //Flash::success(__('Truncate Successfull', ['model' => __('models/clients.singular')]));
      return back();
    }
}
