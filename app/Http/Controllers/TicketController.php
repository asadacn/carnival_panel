<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Technician;
use App\Models\ComplainType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['client','technician','complainType'])->latest()->paginate(20);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $clients = Client::pluck('name','id');
        $types = ComplainType::pluck('name','id');
        $technicians = Technician::where('status','active')->pluck('name','id');
        return view('tickets.create', compact('clients','types','technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'complain_type_id' => 'required|exists:complain_types,id',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = Ticket::create($validated);

        // Send notification to client
        $this->notifyClient($ticket, 'New ticket created');

        return redirect()->route('tickets.index')->with('success','Ticket created successfully');
    }

    public function edit(Ticket $ticket)
    {
        $clients = Client::pluck('name','id');
        $types = ComplainType::pluck('name','id');
        $technicians = Technician::where('status','active')->pluck('name','id');
        return view('tickets.edit', compact('ticket','clients','types','technicians'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'technician_id' => 'nullable|exists:technicians,id',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high',
            'description' => 'required',
        ]);

        $ticket->update($validated);

        if ($request->filled('technician_id')) {
            $this->notifyAssignment($ticket);
        }

        return redirect()->route('tickets.index')->with('success','Ticket updated');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    private function notifyClient(Ticket $ticket, $message)
    {
        $clientPhone = $ticket->client->contact ?? null;
        if ($clientPhone) {
            // example: integrate your SMS API here
            // text_sms($clientPhone, $message);
        }
    }

    private function notifyAssignment(Ticket $ticket)
    {
        $tech = $ticket->technician;
        $client = $ticket->client;

        $msg = "Ticket #{$ticket->id} assigned to {$tech->name}. Issue: {$ticket->complainType->name}";

        // SMS or Telegram
        // text_sms($tech->phone, $msg);
        // telegram_notify($tech->telegram_id, $msg);
        // text_sms($client->contact, "Technician {$tech->name} assigned for your complaint.");
    }
}
