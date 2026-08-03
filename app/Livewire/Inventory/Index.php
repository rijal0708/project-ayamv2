<?php

namespace App\Livewire\Inventory;

use App\Models\EggInventory;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $grades = ['A', 'B', 'C', 'Jumbo', 'cracked'];
        $stockByGrade = [];

        foreach ($grades as $grade) {
            $qtyIn = EggInventory::where('grade', $grade)->sum('qty_in');
            $qtyOut = EggInventory::where('grade', $grade)->sum('qty_out');
            $stockByGrade[$grade] = $qtyIn - $qtyOut;
        }

        $mutations = EggInventory::latest('date')->latest('id')->paginate(15);


        return view('livewire.inventory.index', [
            'stockByGrade' => $stockByGrade,
            'mutations' => $mutations,
        ]);
    }
}
