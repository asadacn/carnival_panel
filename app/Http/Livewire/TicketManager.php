<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\Technician;
use App\Models\ComplainType;
use App\Models\TicketTimeline;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination; // <--- ADDED: Pagination Trait

class TicketManager extends Component
{
    use WithPagination; // <--- ADDED: Use Trait
    protected $paginationTheme = 'bootstrap'; // <--- ADDED: Set Theme

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

    // Reset pagination when search term changes
    public function updatedSearchTerm()
    {
        $this->resetPage();
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
            $this->sendTelegram($text, env('TELEGRAM_GROUP_CHAT_ID'));
        }

        $this->reset(['complain_type_id', 'description', 'priority', 'selectedClient', 'search']);
        session()->flash('success', 'Ticket created successfully!');
        $this->resetPage(); // নতুন টিকেট যোগ হলে প্রথম পেজে যান
    }

    // Assign technician
    public function assignTechnician($ticketId)
    {
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
            $this->sendTelegram($text_to_tech, $technician->telegram_id);
        }


        unset($this->technician_map[$ticketId]);
        session()->flash('success', 'Technician assigned successfully!');
        $this->resetPage();
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
        $this->resetPage();
    }

    // Quick Comment
    public function quickComment($ticketId)
    {
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
        $this->resetPage();
    }

    /**
     * Delete Ticket
     * @param int $ticketId
     */
    public function deleteTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            session()->flash('error', 'টিকেটটি খুঁজে পাওয়া যায়নি।');
            return;
        }

        // Before deleting the ticket, log the action in the timeline
        TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'action' => 'Ticket Deleted',
            'performed_by' => auth()->check() ? auth()->user()->name : 'Manager',
            'note' => "টিকেট #{$ticket->id} (ক্লায়েন্ট: {$ticket->client->name}) ম্যানুয়ালি ডিলিট করা হয়েছে।",
        ]);

        // Delete the ticket
        $ticket->delete();

        session()->flash('success', "টিকেট #{$ticketId} সফলভাবে মুছে ফেলা হয়েছে। 🗑️");
        $this->resetPage();
    }


    function sendSMS($contacts, $message, $type = 'unicode')
    {
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
                Log::error("SMS send error for contacts: {$contacts}. Response: {$body}");
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error("SMS API Exception: " . $e->getMessage());
            return false;
        }
    }

    protected function sendTelegram($message, $recipientChatId = null)
    {
        try {
            $botToken = env('TELEGRAM_BOT_TOKEN');
            $chatId = $recipientChatId ?? env('TELEGRAM_CHAT_ID');

            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::warning("Telegram send failed: " . $e->getMessage());
        }
    }

    public function render()
    {
        $tickets = Ticket::with(['client', 'technician', 'complainType'])
            ->latest()
            ->paginate(5); // <--- CHANGED: Paginate 5 items per page

        return view('livewire.ticket-manager', compact('tickets'));
    }
}
