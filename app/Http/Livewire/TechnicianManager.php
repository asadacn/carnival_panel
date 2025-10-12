<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Technician;

class TechnicianManager extends Component
{
    public $technicians, $name, $phone, $telegram_id, $whatsapp_number, $status = 'active', $technician_id;
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:100',
        'phone' => 'required|unique:technicians,phone',
        'telegram_id' => 'nullable|string|max:100',
        'whatsapp_number' => 'nullable|string|max:100',
        'status' => 'required|in:active,inactive'
    ];

    public function render()
    {
        $this->technicians = Technician::orderBy('id', 'desc')->get();
        return view('livewire.technician-manager');
    }

    public function resetForm()
    {
        $this->reset(['name', 'phone', 'telegram_id', 'whatsapp_number', 'status', 'isEdit', 'technician_id']);
        $this->status = 'active';
    }

    public function store()
    {
        $this->validate();
        Technician::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'telegram_id' => $this->telegram_id,
            'whatsapp_number' => $this->whatsapp_number,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Technician added successfully!');
        $this->resetForm();
    }

    public function edit($id)
    {
        $tech = Technician::findOrFail($id);
        $this->technician_id = $tech->id;
        $this->name = $tech->name;
        $this->phone = $tech->phone;
        $this->telegram_id = $tech->telegram_id;
        $this->whatsapp_number = $tech->whatsapp_number;
        $this->status = $tech->status;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|unique:technicians,phone,' . $this->technician_id,
            'status' => 'required|in:active,inactive',
        ]);

        $tech = Technician::findOrFail($this->technician_id);
        $tech->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'telegram_id' => $this->telegram_id,
            'whatsapp_number' => $this->whatsapp_number,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Technician updated successfully!');
        $this->resetForm();
    }

    public function delete($id)
    {
        Technician::findOrFail($id)->delete();
        session()->flash('success', 'Technician deleted!');
    }
}
