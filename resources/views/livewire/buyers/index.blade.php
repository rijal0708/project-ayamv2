<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2x1 font-bold">Daftar Buyer</h1>
        <a href="{{ route('buyers.create')}}" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Tambah Buyer
        </a>
    </div>

    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message')}}
        </div>
    @endif

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">Nama</th>
                <th class="p-2">Tipe</th>
                <th class="p-2">Kontak</th>
                <th class="p-2">Plafon Piutang</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buyers as $buyer )
                <tr class="border-b">
                    <td class="p-2">{{ $buyer->name }}</td>
                    <td class="p-2">{{ $buyer->type }}</td>
                    <td class="p-2">{{ $buyer->phone }}</td>
                    <td class="p-2">Rp {{ number_format($buyer->credit_limit, 0, ',', '.') }}</td>
                    <td class="p-2">
                        <a href="{{ route('buyers.edit', $buyer->id)}}" class="text-blue-600">Edit</a>
                        <button wire:click="delete({{ $buyer->id}})" wire:confirm="Yakin hapus buyer ini?" class="text-red-600 ml-2">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $buyers->links()}}
    </div>
</div>
