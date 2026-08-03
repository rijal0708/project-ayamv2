<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Stok Gudang Telur</h1>

    <div class="mb-6 space-y-2">
        @foreach ($stockByGrade as $grade => $qty)
            <div class="flex justify-between border-b pb-1">
                <span>Grade {{ strtoupper($grade) }}</span>
                <span>{{ number_format($qty, 0, ',', '.') }} butir</span>
            </div>
        @endforeach
        <div class="flex justify-between pt-2 font-bold">
            <span>Total</span>
            <span>{{ number_format(array_sum($stockByGrade), 0, ',', '.') }} butir</span>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-2">Kartu Stok (Riwayat Mutasi)</h2>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">Tanggal</th>
                <th class="p-2">Grade</th>
                <th class="p-2">Masuk</th>
                <th class="p-2">Keluar</th>
                <th class="p-2">Sumber</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutations as $m)
                <tr class="border-b">
                    <td class="p-2">{{ $m->date }}</td>
                    <td class="p-2">{{ strtoupper($m->grade) }}</td>
                    <td class="p-2">{{ $m->qty_in }}</td>
                    <td class="p-2">{{ $m->qty_out }}</td>
                    <td class="p-2">{{ $m->source }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $mutations->links() }}
    </div>
</div>
