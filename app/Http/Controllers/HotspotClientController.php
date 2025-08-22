<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHotspotClientRequest;
use App\Http\Requests\UpdateHotspotClientRequest;
use App\Repositories\HotspotClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Facades\Http;

class HotspotClientController extends AppBaseController
{
    private $hotspotClientRepository;

    public function __construct(HotspotClientRepository $hotspotClientRepo)
    {
        $this->hotspotClientRepository = $hotspotClientRepo;
    }

    // List + Search
    public function index(Request $request)
    {
        $query = $this->hotspotClientRepository->model()::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('cable', 'like', "%{$search}%")
                  ->orWhere('cable_owner', 'like', "%{$search}%")
                  ->orWhere('onu_mac', 'like', "%{$search}%")
                  ->orWhere('onu_owner', 'like', "%{$search}%")
                  ->orWhere('adrress', 'like', "%{$search}%");
            });
        }

        $hotspotClients = $query->orderBy('id','desc')->paginate(10);

        return view('hotspot_clients.index', compact('hotspotClients'))
               ->with('search', $request->search ?? '');
    }

    // Show single client
    public function show($id)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error('Client not found');
            return redirect(route('hotspotClients.index'));
        }

        return view('hotspot_clients.show')->with('hotspotClient', $hotspotClient);
    }

    // Create form
    public function create()
    {
        return view('hotspot_clients.create');
    }

    // Store new client
    public function store(CreateHotspotClientRequest $request)
    {
        $input = $request->all();

        // Default package fields
        $input['package_days'] = null;
        $input['activated_at'] = null;
        $input['expires_at'] = null;
        $input['status'] = 'inactive';

        $hotspotClient = $this->hotspotClientRepository->create($input);

        Flash::success('Hotspot Client saved successfully.');

        return redirect(route('hotspotClients.index'));
    }

    // Edit form
    public function edit($id)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error('Client not found');
            return redirect(route('hotspotClients.index'));
        }

        return view('hotspot_clients.edit')->with('hotspotClient', $hotspotClient);
    }

    // Update client
    public function update($id, UpdateHotspotClientRequest $request)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error('Client not found');
            return redirect(route('hotspotClients.index'));
        }

        $hotspotClient = $this->hotspotClientRepository->update($request->all(), $id);

        Flash::success('Hotspot Client updated successfully.');

        return redirect(route('hotspotClients.index'));
    }

    // Delete client
    public function destroy($id)
    {
        $hotspotClient = $this->hotspotClientRepository->find($id);

        if (empty($hotspotClient)) {
            Flash::error('Client not found');
            return redirect(route('hotspotClients.index'));
        }

        $this->hotspotClientRepository->delete($id);

        Flash::success('Hotspot Client deleted successfully.');

        return redirect(route('hotspotClients.index'));
    }

    // Activate package
    public function activatePackage(Request $request, $id)
    {
        $client = $this->hotspotClientRepository->find($id);
        if (!$client) {
            Flash::error('Client not found');
            return redirect()->back();
        }

        $days = (int) $request->days;
        $client->package_days = $days;
        $client->activated_at = now();
        $client->expires_at = now()->addDays($days);
        $client->status = 'active';
        $client->save();

        Flash::success("Package activated for {$days} days.");
        return redirect()->back();
    }

    // Send manual SMS
    public function sendSmsReminder($id)
    {
        $client = $this->hotspotClientRepository->find($id);
        if (!$client) {
            Flash::error('Client not found');
            return redirect()->back();
        }

        $message = "আপনার Hotspot প্যাকেজ " . ($client->expires_at ? $client->expires_at->format('d M, y') : 'N/A') . " তারিখে শেষ হবে। দয়া করে রিচার্জ করুন।";

        // MRAM SMS API Call
        sms($client->contact, $message);

        Flash::success("SMS sent to {$client->name} ({$client->contact})");
        return redirect()->back();
    }
}
