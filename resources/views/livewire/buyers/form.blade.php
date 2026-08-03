<div class="p-6 max-w-lg">
    <h1 class="text-2x1 font-bold mb-4">
        {{ $buyer ? 'Edit Buyer' : 'Tambah Buyer'}}
    </h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="block font-medium">Nama</label>
            <input type="text" wire:model="name" class="w-full border rounded p-2">
            @error('name')
                <span class="text-red-600 text-sm">{{ $message}}</span>
            @enderror 
        </div>

        <div>
            <label class="block font-medium">Tipe</label>
            <select wire:model="type" class="w-full border rounded p-2">
                <option value="collector">Pengepul</option>
                <option value="market">Pasar</option>
                <option value="retail">Retail</option>
                <option value="individual">Individual</option>
            </select>
        </div>

        <div>
            <label class="block font-medium">No HP/WA</label>
            <input type="text" wire:model="phone" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-medium">Alamat</label>
            <textarea wire:model="address" class="w-full border rounded p-2"></textarea>
        </div>

        <div>
            <label class="block font-medium">Plafon Piutang</label>
            <input type="number" wire:model="credit_limit" class="w-full border rounded p-2">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('buyers.index')}}" class="px-4 py-2 border rounded">Batal</a>
        </div>
    </form>
</div>
