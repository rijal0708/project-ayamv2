# AGENT.md — Sistem Transaksi Jual Beli Telur

Dokumen ini berisi konteks project untuk AI coding agent (Claude Code, dll) yang membantu melanjutkan development. Baca file ini dulu sebelum mengerjakan apa pun di project ini.

## Ringkasan Project

Aplikasi web internal untuk mencatat transaksi jual-beli telur pada sistem manajemen ayam petelur — mencakup stok gudang per grade, penjualan ke buyer, pembelian dari supplier, pembayaran/piutang, dan laporan. Sumber kebutuhan lengkap ada di PRD asli (`PRD - Fitur Transaksi Jual Beli Telur v1.0`).

## Tech Stack

| Komponen | Pilihan |
|---|---|
| Backend | Laravel 13 (PHP 8.5) |
| Frontend | Livewire 4 (class-based/multi-file, **bukan** Single File Component) |
| Styling | Tailwind CSS (bawaan starter kit) |
| Database | MariaDB (nama DB: `telur_app`, user: `telur_user`) |
| Autentikasi | Laravel built-in auth (starter kit Livewire), fitur aktif: Registration + Password confirmation |
| OS Dev | CachyOS (Arch Linux), shell: **fish** (bukan bash/zsh — perhatikan syntax env var beda) |

## Keputusan Teknis Penting

- **Livewire component dibuat dengan flag `--class`** (`php artisan make:livewire NamaFolder/NamaFile --class`) supaya menghasilkan struktur lama: file class terpisah di `app/Livewire/...` + file Blade di `resources/views/livewire/...`. Livewire 4 defaultnya SFC (Single File Component, pakai emoji ⚡) — kita sengaja tidak pakai itu.
- **Kolom turunan (subtotal, balance) TIDAK pakai generated column di database** (beda dari skema PostgreSQL asli di PRD yang pakai `GENERATED ALWAYS AS ... STORED`). Semua dihitung di layer aplikasi (Controller/Livewire component) supaya lebih mudah dikontrol & di-debug.
- **Skema asli PRD ditulis untuk PostgreSQL**, project ini pakai MariaDB — semua tabel sudah ditranslate ke Laravel Migration (portable antar database), jadi rujuk ke migration file sebagai source of truth, bukan SQL mentah di PRD.
- Setiap Model wajib punya `protected $fillable = [...]` (mass assignment protection Laravel default aktif).

## Struktur Database (Sudah Dibuat)

| Tabel | Migration | Status |
|---|---|---|
| `buyers` | `2026_08_01_022634_create_buyers_table` | ✅ Ran |
| `egg_inventories` | `2026_08_01_023527_create_egg_inventories_table` | ✅ Ran |
| `egg_price_rules` | `2026_08_01_030346_create_egg_price_rules_table` | ✅ Ran |
| `egg_sales` | `2026_08_01_032317_create_egg_sales_table` | ✅ Ran |
| `egg_sale_items` | `2026_08_01_032347_create_egg_sale_items_table` | ✅ Ran |
| `payments` | `2026_08_02_164237_create_payments_table` | ✅ Ran |

Tabel yang **belum dibuat** (masih di PRD, belum ada migration): `egg_purchases`, `egg_purchase_items`, `egg_returns`.

## Progress Fitur (mengikuti Sprint di PRD)

### ✅ Sprint 1 — Selesai
- [x] Setup environment lengkap (PHP 8.5, MariaDB, Node, Laravel 13, Livewire 4, autentikasi dasar)
- [x] Migration + Model untuk 6 tabel inti (buyers, egg_inventories, egg_price_rules, egg_sales, egg_sale_items, payments)
- [x] CRUD Buyer lengkap: `Index` (list + hapus) dan `Form` (tambah/edit) — route `buyers.index`, `buyers.create`, `buyers.edit`
- [x] Halaman Stok Gudang (`egg_inventories`): tampilan saldo per grade, kartu stok/riwayat mutasi per tanggal

### ✅ Sprint 2 (Tahap 1-2) — Selesai
- [x] Form Transaksi Penjualan struktur dasar: pilih buyer → tambah/hapus item per grade → Qty & Harga
- [x] Auto-isi harga dari `egg_price_rules` saat grade dipilih (query prioritas: harga khusus buyer → harga default)
- [x] Hitung subtotal per item & total keseluruhan otomatis (pakai Livewire `#[Computed]`)
- [x] Responsive (pakai `wire:model.live` untuk update langsung tanpa klik tombol)
- [x] Handle edge case: field kosong tidak error (pakai conditional render di Blade)

### ⏳ Sprint 2 (Tahap 3) — Belum
- [ ] Validasi stok cukup sebelum transaksi bisa disimpan
- [ ] Auto-generate `invoice_no` format `INV-{YYYYMMDD}-{SEQ}`
- [ ] Tombol Simpan transaksi
- [ ] Auto-kurangi stok `egg_inventories` saat transaksi disimpan (qty_out)

### ⏳ Sprint 3 — Belum
- [ ] Pencatatan pembayaran (cash/transfer/credit, cicilan)
- [ ] Auto-update `payment_status` (paid/partial/unpaid/overdue)
- [ ] Halaman piutang dengan pengelompokan aging (0-7, 8-14, 15-30, >30 hari)

### ⏳ Sprint 4 — Belum
- [ ] Cetak nota (thermal 58/80mm atau PDF via barryvdh/laravel-dompdf)
- [ ] Surat jalan untuk pengiriman ke pengepul/pasar
- [ ] Kirim nota via WhatsApp (PDF/gambar)

### ⏳ Sprint 5 — Belum
- [ ] Dashboard penjualan: omzet hari ini, stok per grade, piutang outstanding, grafik 7 hari (rencana pakai Chart.js)
- [ ] Laporan: penjualan harian, omzet mingguan/bulanan, top buyer, margin analysis, export PDF/Excel

### ⏳ Sprint 6 — Belum
- [ ] Modul pembelian dari luar (`egg_purchases`, `egg_purchase_items`) — mode jadi pengepul
- [ ] Modul retur (`egg_returns`) — dari buyer & ke supplier
- [ ] Harga dinamis: riwayat harga, harga regional multi-cabang

## Catatan Debugging yang Sudah Dialami (biar tidak terulang)

- **Livewire 4 computed properties**: gunakan `#[Computed]` atribut, BUKAN pola lama `getXProperty()`. Harus import `use Livewire\Attributes\Computed;` di atas class.
- **View cache yang sangat basi**: kalau `php artisan view:clear` tidak bekerja, coba `rm -rf storage/framework/views/*` manual. Kalau masih tidak hilang, gunakan `sudo rm -rf` dan pastikan permission folder `775` (`chmod -R 775 storage/framework/views`).
- **Livewire component blank tanpa error**: bisa disebabkan fatal error PHP di file model yang di-autoload (misal missing trait import). Cek `storage/logs/laravel.log` untuk error tersembunyi.
- **wire:model dynamic binding** (misal `wire:model="items.{{ $index }}.grade"`): syntax ini valid di Livewire 4. Gunakan `wire:model.live` untuk update langsung tanpa perlu trigger tombol lain.
- **String * string error saat field kosong**: handle dengan conditional render di Blade (`@if($item['quantity']) ... @else Rp 0 @endif`) atau cast eksplisit `(float)` di kedua tempat (Blade & PHP).
- **Fish shell** tidak baca `.bashrc`/`.zshrc`. Gunakan `fish_add_path` untuk tambah PATH permanen, atau `sudo pacman -S` untuk install.
- **OPcache di dev environment**: kalau masalah cache tetap terjadi meskipun sudah clear, coba matikan dengan `php -d opcache.enable=0 artisan serve`.

## TODO Langsung Berikutnya (Sprint 2 Tahap 3)

### Langkah yang akan dikerjakan di session berikutnya:

1. **Tambah method validasi & generate invoice di `Form.php`**:
   - Method `generateInvoiceNo()`: query invoice terakhir hari ini, generate SEQ berikutnya
   - Method `validateStok()`: pastikan setiap item cukup stok di `egg_inventories`
   - Lifecycle hook untuk trigger validasi saat user klik Simpan

2. **Tambah method `save()` di `Form.php`**:
   - DB transaction besar: insert `egg_sales` header, insert setiap item ke `egg_sale_items`, insert ke `egg_inventory` (qty_out), auto-update buyer piutang
   - Return redirect ke detail/list transaksi atau success message

3. **Update Blade dengan tombol Simpan & error handling**:
   - Tambah tombol "Simpan Transaksi" di bawah form
   - Tampilkan validation error kalau stok kurang atau ada field wajib yang kosong
   - Tampilkan success message setelah simpan

4. **Test end-to-end**: buat transaksi → cek invoice nomor auto-generate → cek stok berkurang → cek data muncul di database