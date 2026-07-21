<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Technician;
use App\Models\ComplainType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $validated['description'] = $validated['description'] ?? '';

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

    /**
     * Send a ticket share message to the Telegram GROUP (TELEGRAM_GROUP_CHAT_ID).
     */
    public function sendTelegramToGroup(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        try {
            $msg = $request->message;
            // Clean up HTML — keep only Telegram-supported tags
            $msg = str_replace(['<br>', '<br/>', '<br />'], "\n", $msg);
            $msg = strip_tags($msg, '<b><strong><i><em><u><ins><s><strike><del><code><pre><a>');

            $groupChatId = (string) env('TELEGRAM_GROUP_CHAT_ID');
            $botToken    = env('TELEGRAM_BOT_TOKEN');

            if (!$groupChatId || !$botToken) {
                return response()->json(['success' => false, 'message' => 'Telegram group config missing.'], 500);
            }

            Log::info('Sending to Telegram group', ['chat_id' => $groupChatId]);

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id'    => $groupChatId,
                'text'       => $msg,
                'parse_mode' => 'HTML',
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['ok'] ?? false)) {
                return response()->json(['success' => true, 'message' => 'Message sent to Telegram Group!']);
            }

            Log::warning('Telegram group send failed', ['response' => $result]);
            return response()->json(['success' => false, 'message' => 'Failed to send to Telegram group.'], 500);

        } catch (\Exception $e) {
            Log::error('Telegram group exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
