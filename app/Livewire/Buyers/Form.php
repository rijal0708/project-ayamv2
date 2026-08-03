<?php

namespace App\Livewire\Buyers;

use App\Models\Buyer;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class Form extends Component
{
    public ?Buyer $buyer = null;

    public $name = '';
    public $type = 'retail';
    public $phone = '';
    public $address = '';
    public $credit_limit = 0;

    public function mount($id = null)
    {
        if ($id) {
            $this->buyer = Buyer::findOrFail($id);
            $this->name = $this->buyer->name;
            $this->type = $this->buyer->type;
            $this->phone = $this->buyer->phone;
            $this->address = $this->buyer->address;
            $this->credit_limit = $this->buyer->credit_limit;

        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:200',
            'type' => 'required|in:collector,market,retail,individual',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        if ($this->buyer) {
            $this->buyer->update([
                'name' => $this->name,
                'type' => $this->type,
                'phone' => $this->phone,
                'address' => $this->address,
                'credit_limit' => $this->credit_limit,
            ]);
        } else {
            Buyer::create([
                'name' => $this->name,
                'type' => $this->type,
                'phone' => $this->phone,
                'address' => $this->address,
                'credit_limit' => $this->credit_limit,
            ]);
        }

        return Redirect()->route('buyers.index');

    }

    public function render()
    {
        return view('livewire.buyers.form');
    }
}
