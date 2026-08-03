<?php

namespace App\Livewire\Buyers;

use App\Models\Buyer;
use Livewire\Component;

class Index extends Component
{
    public function delete($id)
    {
        Buyer::findOrFail($id)->delete();
        session()->flash('message', 'Buyer berhasil dihapus');
    }

    public function render()
    {
        $buyers = Buyer::latest()->paginate(10);

        return view('livewire.buyers.index', [
            'buyers' => $buyers,
        ]);
    }
}