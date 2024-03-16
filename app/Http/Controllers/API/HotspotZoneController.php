<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateHotspotZoneRequest;
use App\Http\Requests\UpdateHotspotZoneRequest;
use App\Repositories\HotspotZoneRepository;
use App\Http\Controllers\AppBaseController;
use App\Imports\HotspotZoneImport;
use App\Models\HotspotZone;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Yajra\DataTables\DataTables;
class HotspotZoneController extends AppBaseController
{
    /** @var  HotspotZoneRepository */
    private $hotspotZoneRepository;

    public function __construct(HotspotZoneRepository $hotspotZoneRepo)
    {
        $this->hotspotZoneRepository = $hotspotZoneRepo;
    }


    // public function index(Request $request)
    // {
    //     $hotspotZones = $this->hotspotZoneRepository->all();

    //     return view('hotspot_zones.index')
    //         ->with('hotspotZones', $hotspotZones);
    // }

    public function index()
    {

            $data = HotspotZone::all();
            return response()->json($data);


    }

    /**
     * Show the form for creating a new HotspotZone.
     *
     * @return Response
     */
    public function create()
    {
        return view('hotspot_zones.create');
    }

    public function create_import()
    {
        return view('hotspot_zones.import');
    }


    /**
     * Store a newly created HotspotZone in storage.
     *
     * @param CreateHotspotZoneRequest $request
     *
     * @return Response
     */
    public function store(CreateHotspotZoneRequest $request)
    {
        $input = $request->all();

        $hotspotZone = $this->hotspotZoneRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/hotspotZones.singular')]));

        return redirect(route('hotspotZones.index'));
    }

    /**
     * Display the specified HotspotZone.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $hotspotZone = $this->hotspotZoneRepository->find($id);

        if (!empty($hotspotZone)) {


            return response()->json(  $hotspotZone);
        }

        return response()->json( [
            "message"=>"Zone not found"
        ],404);
    }

    /**
     * Show the form for editing the specified HotspotZone.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $hotspotZone = $this->hotspotZoneRepository->find($id);

        if (empty($hotspotZone)) {


            return response()->json(  $hotspotZone);
        }

        return response()->json( [
            "message"=>"Zone not found"
        ],404);
    }

    /**
     * Update the specified HotspotZone in storage.
     *
     * @param int $id
     * @param UpdateHotspotZoneRequest $request
     *
     * @return Response
     */
    // public function update($id, Request $request)
    // {
    //     $hotspotZone = HotspotZone::find($id);

    //     if (empty($hotspotZone)) {
    //         return response()->json( [
    //             "message"=>"Zone not found"
    //         ],404);
    //     }

    //     $hotspotZone->device_mac = $request->device_mac;
    //     $hotspotZone->save();
    //     return response()->json( [
    //         $hotspotZone
    //     ],200);
    // }


    public function update(Request $request, $id)
    {
        $hotspotZone = HotspotZone::find($id);

        // Check if the hotspot zone was found
        if (!$hotspotZone) {
            return response()->json(['message' => 'Hotspot Zone not found'], 404);
        }

        // Validate and update the new attributes
        $request->validate([
            'gps_location' => 'nullable|string', // Add validation rules as needed
            'gps_updated_at' => 'nullable|date', // Add validation rules as needed
        ]);

        $hotspotZone->update([
            'gps_location' => $request->input('gps_location'),
            'gps_updated_at' => now(),
            // ... other attributes ...
        ]);

        return response()->json(['message' => 'Hotspot Zone updated successfully', 'data' => $hotspotZone]);
    }
    /**
     * Remove the specified HotspotZone from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $hotspotZone = $this->hotspotZoneRepository->find($id);

        if (empty($hotspotZone)) {
            Flash::error(__('messages.not_found', ['model' => __('models/hotspotZones.singular')]));

            return redirect(route('hotspotZones.index'));
        }

        $this->hotspotZoneRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/hotspotZones.singular')]));

        return redirect(route('hotspotZones.index'));
    }

    public function export()
    {


        return Excel::download(new HotspotZoneImport, 'clients.xlsx');
    }

    public function import()
    {
        if (request()->file('hotspots_file')) {
            if (Excel::import(new HotspotZoneImport, request()->file('hotspots_file'))) {
                Flash::success(__('Import Successfull'));
                return back()->with('success', 'All good!');
            } else {
                Flash::error(__('Import Failed'));
                return back()->with('error', 'Import Failed');
            }
        } else {
            Flash::warning(__('Please select a file first !'));
            return back()->with('error', 'Please select a file first !');
        }
    }


    public function erase()
    {
        HotspotZone::query()->truncate();
        flash('Truncate Successfull')->success();
        //Flash::success(__('Truncate Successfull', ['model' => __('models/clients.singular')]));
        return back();
    }


}
