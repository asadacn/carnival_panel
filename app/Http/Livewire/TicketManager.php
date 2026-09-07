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
use Livewire\WithPagination;

class TicketManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $clients = [], $complain_types, $technicians;
    public $selectedClient, $complain_type_id, $description, $priority = 'low', $technician_id;
    public $search = '';

    // Technician assignment mapping for the ticket list and comment storage
    public $technician_map = [];

    // Message control
    public $send_sms = true;
    public $send_telegram = true;

    // --- ADDED FOR DASHBOARD COUNTS AND FILTERING ---
    public $ticketCounts = [
        'pending' => 0,
        'progress' => 0,
        'closed' => 0,
    ];
    public $filterTicketId = '';
    public $filterStatus = 'all'; // Default to 'all'
    // ------------------------------------------------

    // --- CHART DATA ---
    public $complainTypeStats = [];
    // ------------------

    public $autoOpenModal = false;

    public function mount()
    {
        $this->complain_types = ComplainType::all();
        $this->technicians = Technician::where('status', 'active')->get(['id', 'name', 'phone', 'telegram_id']);

        // Pre-fill client if arriving from clients list "Open Ticket" shortcut
        $clientId   = request()->query('client_id');
        $clientName = request()->query('client_name');
        if ($clientId) {
            $client = Client::find($clientId);
            if ($client) {
                $this->selectedClient = $client->id;
                $this->search         = $client->name;
                $this->autoOpenModal  = true;
            }
        }
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

    // --- ADDED: Reset pagination when filters change ---
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterTicketId()
    {
        $this->resetPage();
    }

    // Resets both filters when called from the Blade button
    public function resetFilters()
    {
        $this->reset(['filterStatus', 'filterTicketId']);
        $this->resetPage();
    }
    // ----------------------------------------------------

    // Ticket submit
    public function submitTicket()
    {
        $this->validate([
            'selectedClient' => 'required',
            'complain_type_id' => 'required',
            'description' => 'nullable|string',
        ]);

        $client = Client::find($this->selectedClient);

        if (!$client) {
            session()->flash('error', 'Client not found.');
            return;
        }

        $ticket = Ticket::create([
            'client_id' => $client->id,
            'complain_type_id' => $this->complain_type_id,
            'description' => $this->description ?? '',
            'priority' => $this->priority,
            'status' => 'pending',
        ]);

        // 1. Create Timeline entry for ticket creation
        TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'action' => 'Ticket Created',
            'performed_by' => auth()->check() ? auth()->user()->name : 'Manager',
            'note' => $this->description ? Str::limit($this->description, 200) : 'No details provided',
        ]);

        // 2. Send SMS to client
        if ($this->send_sms) {
            $this->sendSMS($client->contact, "আপনার অভিযোগ (#{$ticket->id}) গ্রহণ করা হয়েছে। শীঘ্রই সমাধান করা হবে।");
        }

        // 3. Send Telegram notification to the main group
        if ($this->send_telegram) {
            $complainType = ComplainType::find($this->complain_type_id)->name ?? 'N/A';
            $text = "🔔 নতুন টিকেট তৈরি হয়েছে!\n\n📄 টিকেট ID: {$ticket->id}\n👤ক্লায়েন্ট আইডি: {$client->username}\n ক্লায়েন্ট: {$client->name}\n🏠ঠিকানাঃ {$client->address}\n📞 {$client->contact}\n⚙️ ধরন: {$complainType}\n🔥 Priority: " . ucfirst($ticket->priority) . "\n📝 বর্ণনা: " . Str::limit($ticket->description, 100);
            $this->sendTelegram($text, config('services.telegram.group_chat_id'));
        }

        $this->reset(['complain_type_id', 'description', 'priority', 'selectedClient', 'search']);
        session()->flash('success', 'Ticket created successfully!');
        $this->resetPage();

        // Dispatch event with ticket details for the share popup (Livewire v2: emit with named args)
        $complainType = ComplainType::find($ticket->complain_type_id)->name ?? 'N/A';
        $this->emit('ticket-created',
            $ticket->id,
            $client->name,
            $client->username,
            $client->contact,
            $client->address,
            $complainType,
            ucfirst($ticket->priority),
            Str::limit($ticket->description, 120)
        );
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
            'note' => "Technician assigned: {$technician->name}. Status set to assigned. ETA: {$eta}. (Previously: {$oldTechnicianName})",
        ]);

        // 2. Send SMS to client
        if ($this->send_sms) {
            $msg = "আপনার টিকেট (#{$ticket->id}) {$eta} সমাধান করা হবে।\n👨‍🔧 টেকনিশিয়ান: {$technician->name}\n📞 {$technician->phone}";
            $this->sendSMS($client->contact, $msg);
        }

        // 3. Send Telegram to Tech (if telegram_id exists)
        if ($this->send_telegram && $technician->telegram_id) {
            $text_to_tech = "🛠 আপনাকে একটি নতুন টিকেট অ্যাসাইন করা হয়েছে!\n\n📄টিকেট ID: {$ticket->id}\n👤ক্লায়েন্ট আইডি: {$client->username}\n ক্লায়েন্ট: {$client->name}\n🏠ঠিকানাঃ {$client->address}\n📞 {$client->contact}\n⚙️ Priority: " . ucfirst($ticket->priority) . "\n📝 বর্ণনা: " . Str::limit($ticket->description, 100) . "\n⏱ ETA: {$eta}";
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
            $botToken = config('services.telegram.bot_token');
            $chatId = $recipientChatId ?? config('services.telegram.group_chat_id') ?? config('services.telegram.chat_id');

            if (!$botToken || !$chatId) {
                Log::warning('Telegram send skipped: missing bot token or chat ID');
                return false;
            }

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['ok'] ?? false)) {
                Log::info('Telegram message sent successfully', [
                    'chat_id' => $chatId,
                    'message_length' => strlen($message),
                ]);
                return true;
            }

            Log::warning('Telegram send failed', [
                'chat_id' => $chatId,
                'response' => $result,
                'status' => $response->status(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::warning("Telegram send failed: " . $e->getMessage());
            return false;
        }
    }

    public function render()
    {
        // 1. Calculate Counts (For Dashboard Cards)
        $this->ticketCounts['pending'] = Ticket::where('status', 'pending')->count();
        $this->ticketCounts['closed'] = Ticket::where('status', 'closed')->count();
        // Progress includes 'open', 'assigned', and 'in_progress'
        $this->ticketCounts['progress'] = Ticket::whereIn('status', ['open', 'assigned', 'in_progress'])->count();

        // --- CHART DATA ---
        // Tickets grouped by Complain Type (Category)
        $this->complainTypeStats = ComplainType::withCount('tickets')
            ->orderByDesc('tickets_count')
            ->get()
            ->map(fn($t) => ['label' => $t->name, 'count' => $t->tickets_count])
            ->toArray();
        // -------------------

        // 2. Start Query
        $query = Ticket::with(['client', 'technician', 'complainType']);

        // --- APPLY FILTERS ---

        // Filter by Status (MODIFIED LOGIC for 'progress')
        if (!empty($this->filterStatus) && $this->filterStatus !== 'all') {
            if ($this->filterStatus === 'progress') {
                // Map the 'progress' filter value to multiple actual statuses in the database
                $query->whereIn('status', ['open', 'assigned', 'in_progress']);
            } else {
                // For 'pending' and 'closed', use the value directly
                $query->where('status', $this->filterStatus);
            }
        }

        // Filter by Ticket ID
        if (!empty($this->filterTicketId) && is_numeric($this->filterTicketId)) {
            $query->where('id', (int)$this->filterTicketId);
        }

        // --- END FILTERS ---

        // 3. Fetch Paginated Tickets
        $tickets = $query->latest()->paginate(5);

        // 4. Pass to the view
        return view('livewire.ticket-manager', [
            'tickets' => $tickets,
        ]);
    }
}
