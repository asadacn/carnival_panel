<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\Technician;
use App\Models\ComplainType;
use App\Models\TicketTimeline; // Added import
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str; // Added import for Str::limit

class TicketManager extends Component
{
    public $clients = [], $complain_types, $technicians;
    public $selectedClient, $complain_type_id, $description, $priority = 'low', $technician_id;
    public $search = '';

    // Technician assignment mapping for the ticket list and comment storage
    public $technician_map = [];

    // Message control
    public $send_sms = true;
    public $send_telegram = true;

    public function mount()
    {
        $this->complain_types = ComplainType::all();
        // Load only necessary fields for efficiency
        $this->technicians = Technician::where('status', 'active')->get(['id', 'name', 'phone', 'telegram_id']);
    }

    // Client search
    public function updatedSearch()
    {
        $this->clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('contact', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->limit(10)
            ->get();
    }

    // Ticket submit
    public function submitTicket()
    {
        $this->validate([
            'selectedClient' => 'required',
            'complain_type_id' => 'required',
            'description' => 'nullable|string|min:5',
        ]);

        $client = Client::find($this->selectedClient);

        if (!$client) {
            session()->flash('error', 'Client not found.');
            return;
        }

        $ticket = Ticket::create([
            'client_id' => $client->id,
            'complain_type_id' => $this->complain_type_id,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => 'pending',
        ]);

        // 1. Create Timeline entry for ticket creation
        TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'action' => 'Ticket Created',
            'performed_by' => auth()->check() ? auth()->user()->name : 'Manager',
            'note' => Str::limit($this->description, 200),
        ]);

        // 2. Send SMS to client
        if ($this->send_sms) {
            $this->sendSMS($client->contact, "আপনার অভিযোগ (#{$ticket->id}) গ্রহণ করা হয়েছে। শীঘ্রই সমাধান করা হবে।");
        }

        // 3. Send Telegram notification to the main group
        if ($this->send_telegram) {
            $complainType = ComplainType::find($this->complain_type_id)->name ?? 'N/A';
            $text = "🔔 নতুন টিকেট তৈরি হয়েছে!\n\n📄 টিকেট ID: {$ticket->id}\n👤 ক্লায়েন্ট: {$client->name}\n📞 {$client->contact}\n⚙️ ধরন: {$complainType}\n🔥 Priority: " . ucfirst($ticket->priority) . "\n📝 বর্ণনা: " . Str::limit($ticket->description, 100);
            $this->sendTelegram($text);
        }

        $this->reset(['complain_type_id', 'description', 'priority', 'selectedClient', 'search']);
        session()->flash('success', 'Ticket created successfully!');
    }

    // Assign technician
    public function assignTechnician($ticketId)
    {
        // Get technician ID from the map
        $techId = $this->technician_map[$ticketId] ?? null;

        if (!$techId) {
            session()->flash('error', 'Please select a technician first.');
            return;
        }

        $ticket = Ticket::find($ticketId);
        if (!$ticket) {
            session()->flash('error', 'Ticket not found.');
            return;
        }

        $technician = Technician::find($techId);
        $client = $ticket->client;

        $oldTechnicianName = $ticket->technician ? $ticket->technician->name : 'None';

        $ticket->technician_id = $technician->id;
        $ticket->status = 'in_progress';
        $ticket->save();

        // ETA using if-elseif-else
        if ($ticket->priority == 'high') {
            $eta = '১ ঘন্টার মধ্যে';
        } elseif ($ticket->priority == 'medium') {
            $eta = '১.৫ ঘন্টার মধ্যে';
        } else {
            $eta = '২ ঘন্টার মধ্যে';
        }

        // 1. Create Timeline entry for assignment
        TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'action' => 'Assigned',
            'performed_by' => auth()->check() ? auth()->user()->name : 'Manager',
            'note' => "Technician assigned: {$technician->name}. Status set to in progress. ETA: {$eta}. (Previously: {$oldTechnicianName})",
        ]);

        // 2. Send SMS to client
        if ($this->send_sms) {
            $msg = "আপনার টিকেট (#{$ticket->id}) {$eta} সমাধান করা হবে।\n👨‍🔧 টেকনিশিয়ান: {$technician->name}\n📞 {$technician->phone}";
            $this->sendSMS($client->contact, $msg);
        }

        // 3. Send Telegram to Tech (if telegram_id exists)
        if ($this->send_telegram && $technician->telegram_id) {
            $text_to_tech = "🛠 আপনাকে একটি নতুন টিকেট অ্যাসাইন করা হয়েছে!\n\n📄 টিকেট ID: {$ticket->id}\n👤 ক্লায়েন্ট: {$client->name}\n📞 {$client->contact}\n🏠 ঠিকানা: {$client->address}\n⚙️ Priority: " . ucfirst($ticket->priority) . "\n📝 বর্ণনা: " . Str::limit($ticket->description, 100) . "\n⏱ ETA: {$eta}";
            // Send to tech's private chat
            $this->sendTelegram($text_to_tech, $technician->telegram_id);
        }


        // Clear the specific map entry after successful assignment
        unset($this->technician_map[$ticketId]);
        session()->flash('success', 'Technician assigned successfully!');
    }

    // Update status
    public function updateStatus($ticketId, $status)
    {
        $ticket = Ticket::find($ticketId);
        if (!$ticket) return;

        $oldStatus = $ticket->status;

        $ticket->status = $status;
        $ticket->save();

        // 1. Create Timeline entry for status update
        TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'action' => 'Status Change',
            'performed_by' => auth()->check() ? auth()->user()->name : 'Manager',
            'note' => "Status changed from {$oldStatus} to {$status}.",
        ]);

        // 2. Send SMS to client on closure
        if ($status == 'closed' && $this->send_sms) {
            $this->sendSMS($ticket->client->contact, "আপনার টিকেট (#{$ticket->id}) সমাধান সম্পন্ন হয়েছে। ধন্যবাদ!");
        }

        session()->flash('success', 'Status updated successfully!');
    }

    // Quick Comment (New Method)
    public function quickComment($ticketId)
    {
        // Key is 'comment-TICKET_ID'
        $comment = $this->technician_map['comment-' . $ticketId] ?? null;

        if (!$comment) {
            session()->flash('error', 'Please write a comment first.');
            return;
        }

        $ticket = Ticket::find($ticketId);
        if (!$ticket) return;

        // Create Timeline entry for comment
        TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'action' => 'Comment Added',
            'performed_by' => auth()->check() ? auth()->user()->name : 'Manager',
            'note' => $comment,
        ]);

        // Clear the comment text
        unset($this->technician_map['comment-' . $ticketId]);
        session()->flash('success', 'Comment added to timeline!');
    }


    function sendSMS($contacts, $message, $type = 'unicode')
    {
        // ... (SMS logic remains the same)
        $api_key = env('MRAM_API_KEY');
        $senderid = env('MRAM_SENDER_ID');

        if ($contacts instanceof \Illuminate\Support\Collection) {
            $contacts = $contacts->toArray();
        }

        if (is_string($contacts)) {
            $contacts = [$contacts];
        }

        $contacts = array_map(function ($number) {
            $number = preg_replace('/\D/', '', $number);
            return str_starts_with($number, '88') ? $number : '88' . $number;
        }, $contacts);

        $contacts = implode('+', $contacts);

        try {
            $response = Http::asForm()->post("https://sms.mram.com.bd/smsapi", [
                "api_key" => $api_key,
                "type" => $type,
                "contacts" => $contacts,
                "senderid" => $senderid,
                "msg" => $message,
            ]);

            $body = $response->body();

            if (strpos($body, 'Error') !== false) {
                 \Log::error("SMS send error for contacts: {$contacts}. Response: {$body}");
                return false;
            }

            return true;

        } catch (\Exception $e) {
             \Log::error("SMS API Exception: " . $e->getMessage());
            return false;
        }
    }

    // Telegram API - modified to accept an optional recipient chatId
    protected function sendTelegram($message, $recipientChatId = null)
    {
        try {
            $botToken = env('TELEGRAM_BOT_TOKEN');
            // Use provided chat ID or fall back to default group chat ID
            $chatId = $recipientChatId ?? env('TELEGRAM_CHAT_ID');

            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                // Using Markdown for better formatting in Telegram
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            // Fails silently, but can be logged for debugging
            \Log::warning("Telegram send failed: " . $e->getMessage());
        }
    }

    public function render()
    {
        $tickets = Ticket::with(['client', 'technician', 'complainType'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.ticket-manager', compact('tickets'));
    }
}
