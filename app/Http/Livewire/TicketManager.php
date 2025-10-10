<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\Technician;
use App\Models\ComplainType;
use App\Models\TicketTimeline;

class TicketManager extends Component
{
    public $tickets = [];
    public $clients = [];
    public $technicians = [];
    public $complainTypes = [];

    public $client_id, $technician_id, $complain_type_id, $description, $priority = 'medium';
    public $searchClient = '';
    public $status;

    protected $rules = [
        'client_id' => 'required',
        'complain_type_id' => 'required',
        'description' => 'required|min:5',
        'priority' => 'required',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedSearchClient()
    {
        if ($this->searchClient) {
            $this->clients = Client::where('username', 'like', "%{$this->searchClient}%")
                ->orWhere('contact', 'like', "%{$this->searchClient}%")
                ->get();
        } else {
            $this->clients = Client::all();
        }
    }

    public function loadData()
    {
        $this->tickets = Ticket::with(['client', 'technician', 'complainType', 'timeline'])->latest()->get();
        $this->technicians = Technician::where('status', 'active')->get();
        $this->complainTypes = ComplainType::all();
    }

    public function submitTicket()
    {
        $data = $this->validate();

        $ticket = Ticket::create([
            'client_id' => $this->client_id,
            'complain_type_id' => $this->complain_type_id,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => 'pending',
        ]);

        $this->addTimeline($ticket->id, 'created', auth()->user()->name, 'Ticket created');

        $this->resetInput();
        $this->loadData();

        session()->flash('success', 'Ticket created successfully!');
    }

    public function assignTechnician($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        if (!$this->technician_id) {
            session()->flash('error', 'Select technician first!');
            return;
        }

        $ticket->update([
            'technician_id' => $this->technician_id,
            'status' => 'in_progress'
        ]);

        $this->addTimeline($ticket->id, 'technician_assigned', auth()->user()->name, "Assigned to technician");

        $this->loadData();
        session()->flash('success', 'Technician assigned successfully!');
    }

    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['status' => $newStatus]);

        $this->addTimeline($ticket->id, 'status_updated', auth()->user()->name, "Status changed to $newStatus");

        $this->loadData();
        session()->flash('success', "Ticket #$ticketId status updated to $newStatus.");
    }

    private function addTimeline($ticketId, $action, $performed_by, $note)
    {
        TicketTimeline::create([
            'ticket_id' => $ticketId,
            'action' => $action,
            'performed_by' => $performed_by,
            'note' => $note,
        ]);
    }

    private function resetInput()
    {
        $this->client_id = $this->complain_type_id = $this->description = $this->technician_id = null;
        $this->priority = 'medium';
        $this->searchClient = '';
    }

    public function render()
    {
        return view('livewire.ticket-manager');
    }
}
