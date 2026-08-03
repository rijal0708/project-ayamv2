<?php

namespace App\Livewire\Sales;

use App\Models\Buyer;
use App\Models\EggPriceRule;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Form extends Component
{
    public $buyer_id = '';
    public $date;
    public $items = [];

    public function mount()
    {
        $this->date = today()->format('Y-m-d');
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'grade' => 'A',
            'quantity' => 1,
            'unit_price' => $this->getPriceFor('A'),
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // Dipanggil otomatis oleh Livewire saat wire:model "items.X.grade" berubah
    public function updated($name, $value)
    {
        if (preg_match('/^items\.(\d+)\.grade$/', $name, $matches)) {
            $index = $matches[1];
            $this->items[$index]['unit_price'] = $this->getPriceFor($value);
        }
    }

    protected function getPriceFor($grade)
    {
        // Cari harga khusus buyer dulu (kalau buyer sudah dipilih), fallback ke harga default
        $query = EggPriceRule::where('grade', $grade)
            ->where('effective_date', '<=', today())
            ->orderByDesc('effective_date');

        if ($this->buyer_id) {
            $specific = (clone $query)->where('buyer_id', $this->buyer_id)->first();
            if ($specific) {
                return $specific->price;
            }
        }

        $default = (clone $query)->whereNull('buyer_id')->first();

        return $default ? $default->price : 0;
    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->items)->sum(fn ($item) => (float) $item['quantity'] * (float) $item['unit_price']);
    }

    public function render()
    {
        return view('livewire.sales.form', [
            'buyers' => Buyer::orderBy('name')->get(),
        ]);
    }
}