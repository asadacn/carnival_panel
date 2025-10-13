<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\Technician;
use App\Models\ComplainType;
use Illuminate\Support\Facades\Http;

class TicketManager extends Component
{
    public $clients = [], $complain_types, $technicians;
    public $selectedClient, $complain_type_id, $description, $priority = 'low', $technician_id;
    public $search = '';

    // Message control
    public $send_sms = true;
    public $send_telegram = true;

    public function mount()
    {
        $this->complain_types = ComplainType::all();
        $this->technicians = Technician::where('status', 'active')->get();
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
            'description' => 'required|string|min:5',
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

        if ($this->send_sms) {
            $this->sendSMS($client->contact, "আপনার অভিযোগ (#{$ticket->id}) গ্রহণ করা হয়েছে। শীঘ্রই সমাধান করা হবে।");
        }

        $this->reset(['complain_type_id', 'description', 'priority', 'selectedClient', 'search']);
        session()->flash('success', 'Ticket created successfully!');
    }

    // Assign technician
    public function assignTechnician($ticketId)
    {
        if (!$this->technician_id) {
            session()->flash('error', 'Please select a technician first.');
            return;
        }

        $ticket = Ticket::find($ticketId);
        if (!$ticket) {
            session()->flash('error', 'Ticket not found.');
            return;
        }

        $technician = Technician::find($this->technician_id);
        $ticket->technician_id = $technician->id;
        $ticket->status = 'in_progress';
        $ticket->save();

        // ETA using if-elseif-else (PHP 7.x compatible)
        if ($ticket->priority == 'high') {
            $eta = '১ ঘন্টার মধ্যে';
        } elseif ($ticket->priority == 'medium') {
            $eta = '১.৫ ঘন্টার মধ্যে';
        } else {
            $eta = '২ ঘন্টার মধ্যে';
        }

        // SMS
        if ($this->send_sms) {
            $client = $ticket->client;
            $msg = "আপনার টিকেট (#{$ticket->id}) {$eta} সমাধান করা হবে।\n👨‍🔧 টেকনিশিয়ান: {$technician->name}\n📞 {$technician->phone}";
            $this->sendSMS($client->contact, $msg);
        }

        // Telegram
        if ($this->send_telegram) {
            $client = $ticket->client;
            $text = "🛠 নতুন টিকেট অ্যাসাইন হয়েছে!\n\n📄 টিকেট ID: {$ticket->id}\n👤 ক্লায়েন্ট: {$client->name}\n📞 {$client->contact}\n🏠 ঠিকানা: {$client->address}\n⚙️ Priority: {$ticket->priority}\n👨‍🔧 Technician: {$technician->name}\n📞 {$technician->phone}";
            $this->sendTelegram($text);
        }

        $this->technician_id = null;
        session()->flash('success', 'Technician assigned successfully!');
    }

    // Update status
    public function updateStatus($ticketId, $status)
    {
        $ticket = Ticket::find($ticketId);
        if (!$ticket) return;

        $ticket->status = $status;
        $ticket->save();

        if ($status == 'closed' && $this->send_sms) {
            $this->sendSMS($ticket->client->contact, "আপনার টিকেট (#{$ticket->id}) সমাধান সম্পন্ন হয়েছে। ধন্যবাদ!");
        }

        session()->flash('success', 'Status updated successfully!');
    }


    function sendSMS($contacts, $message, $type = 'unicode')
{
    $api_key  = env('MRAM_API_KEY');
    $senderid = env('MRAM_SENDER_ID');

    // -----------------------------
    // Number normalization
    // -----------------------------
    if ($contacts instanceof \Illuminate\Support\Collection) {
        $contacts = $contacts->toArray();
    }

    if (is_string($contacts)) {
        $contacts = [$contacts];
    }

    // প্রতিটি নাম্বারের আগে 88 যোগ করা
    $contacts = array_map(function ($number) {
        $number = preg_replace('/\D/', '', $number); // শুধু digits রাখবে
        return str_starts_with($number, '88') ? $number : '88' . $number;
    }, $contacts);

    // একসাথে join করা MRAM API অনুযায়ী
    $contacts = implode('+', $contacts);

    // -----------------------------
    // API call
    // -----------------------------
    try {
        $response = Http::asForm()->post("https://sms.mram.com.bd/smsapi", [
            "api_key"  => $api_key,
            "type"     => $type,
            "contacts" => $contacts,
            "senderid" => $senderid,
            "msg"      => $message,
        ]);

        $body = $response->body();

        // যদি API তে কোনো Error থাকে
        if (strpos($body, 'Error') !== false) {
            return false;
        }

        return true;

    } catch (\Exception $e) {
        return false;
    }
}

    // Telegram API
    protected function sendTelegram($message)
    {
        try {
            $botToken = config('services.telegram.bot_token');
            $chatId = config('services.telegram.chat_id');

            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
            ]);
        } catch (\Exception $e) {}
    }

    public function render()
    {
        $tickets = Ticket::with(['client', 'technician'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.ticket-manager', compact('tickets'));
    }
}
