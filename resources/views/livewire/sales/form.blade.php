<div class="p-6 max-w-3xl">
    <h1 class="text-2xl font-bold mb-4">Transaksi Penjualan</h1>

    <div class="mb-4">
        <label class="block font-medium">Buyer</label>
        <select wire:model="buyer_id" class="w-full border rounded p-2">
            <option value="">-- Pilih Buyer --</option>
            @foreach ($buyers as $buyer)
                <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block font-medium">Tanggal</label>
        <input type="date" wire:model="date" class="w-full border rounded p-2">
    </div>

    <h2 class="text-lg font-bold mb-2">Item</h2>
    <table class="w-full border-collapse mb-4">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">Grade</th>
                <th class="p-2">Qty</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Subtotal</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr class="border-b">
                    <td class="p-2">
                        <select wire:model.live="items.{{ $index }}.grade" class="border rounded p-1">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="jumbo">Jumbo</option>
                            <option value="cracked">Cracked</option>
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="number" wire:model.live="items.{{ $index }}.quantity" class="border rounded p-1 w-20">
                    </td>
                    <td class="p-2">
                        <input type="number" wire:model.live="items.{{ $index }}.unit_price" class="border rounded p-1 w-28">
                    </td>
                    <td class="p-2">
                        Rp {{ number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') }}
                    </td>
                    <td class="p-2">
                        <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" wire:click="addItem" class="bg-gray-200 px-3 py-1 rounded mb-4">
        + Tambah Item
    </button>
    <div class="text-right font-bold text-lg mb-4">
        Total: Rp {{ number_format($this->subtotal, 0, ',', '.') }}
    </div>
</div>