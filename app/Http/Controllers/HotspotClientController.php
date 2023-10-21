<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHotspotClientRequest;
use App\Http\Requests\UpdateHotspotClientRequest;
use App\Repositories\HotspotClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class HotspotClientController extends AppBaseController
{
    /** @var  HotspotClientRepository */
    private $hotspotClientRepository;

    public function __construct(HotspotClientRepository $hotspotClientRepo)
    {
        $this->hotspotClientRepository = $hotspotClientRepo;
    }

    /**
     * Display a listing of the HotspotClient.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $hotspotClients = $this->hotspotClientRepository->all();

        return view('hotspot_clients.index')
            ->with('hotspotClients', $hotspotClients);
    }

    /**
     * Show the form for creating a new HotspotClient.
     *
     * @return Response
     */
    public function create()
    {
        return view('hotspot_clients.create');
    }

    /**
     * Store a newly created HotspotClient in storage.
     *
     * @param CreateHotspotClientRequest $request
     *
     * @return Response
     */
    public function store(CreateHotspotClientRequest $request)
    {
        $input = $request->all();

        $hotspotClient = $this->hotspotClientRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/hotspotClients.singular')]));

        return redirect(route('hotspotClients.index'));
    }

    /**
     * Display the specified HotspotClient.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error(__('messages.not_found', ['model' => __('models/hotspotClients.singular')]));

            return redirect(route('hotspotClients.index'));
        }

        return view('hotspot_clients.show')->with('hotspotClient', $hotspotClient);
    }

    /**
     * Show the form for editing the specified HotspotClient.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error(__('messages.not_found', ['model' => __('models/hotspotClients.singular')]));

            return redirect(route('hotspotClients.index'));
        }

        return view('hotspot_clients.edit')->with('hotspotClient', $hotspotClient);
    }

    /**
     * Update the specified HotspotClient in storage.
     *
     * @param int $id
     * @param UpdateHotspotClientRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHotspotClientRequest $request)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error(__('messages.not_found', ['model' => __('models/hotspotClients.singular')]));

            return redirect(route('hotspotClients.index'));
        }

        $hotspotClient = $this->hotspotClientRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/hotspotClients.singular')]));

        return redirect(route('hotspotClients.index'));
    }

    /**
     * Remove the specified HotspotClient from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error(__('messages.not_found', ['model' => __('models/hotspotClients.singular')]));

            return redirect(route('hotspotClients.index'));
        }

        $this->hotspotClientRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/hotspotClients.singular')]));

        return redirect(route('hotspotClients.index'));
    }
}
