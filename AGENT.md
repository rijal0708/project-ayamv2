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

### ✅ Sprint 1 (sebagian) — Selesai
- [x] Setup environment lengkap (PHP, MariaDB, Node, Laravel, Livewire, autentikasi dasar)
- [x] Migration + Model untuk 6 tabel inti (lihat tabel di atas)
- [x] CRUD Buyer lengkap: `Index` (list + hapus) dan `Form` (tambah/edit) — route `buyers.index`, `buyers.create`, `buyers.edit`, sudah tervalidasi jalan dengan data nyata

### ⏳ Sprint 1 (lanjutan) — Belum
- [ ] Halaman Stok Gudang (`egg_inventories`): tampilan saldo per grade (progress bar), kartu stok/riwayat mutasi
- [ ] Integrasi auto stok masuk dari modul Produksi (belum ada modul produksi di project ini — cek dulu apakah sudah ada di project existing)

### ⏳ Sprint 2 — Belum
- [ ] Form Transaksi Penjualan: pilih buyer → tambah item per grade → harga auto-fill dari `egg_price_rules` → hitung subtotal/total
- [ ] Auto-generate `invoice_no` format `INV-{YYYYMMDD}-{SEQ}`
- [ ] Validasi stok cukup sebelum transaksi bisa disimpan
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

- Kalau ada error `Undefined variable` padahal kode `render()` sudah benar → kemungkinan besar **route cache atau view cache basi**. Jalankan `php artisan optimize:clear` dan cek juga `storage/framework/views/*` perlu dibersihkan manual kalau masih bandel.
- Kalau `composer create-project` gagal karena `ext-iconv` → di Arch/CachyOS extension iconv sudah include di `php` core, tinggal uncomment `extension=iconv` di `/etc/php/php.ini`.
- Jika ada folder sisa dari percobaan gagal (misal folder SFC `resources/views/components/...` dari percobaan awal) → hapus manual, jangan biarkan nyangkut karena bisa bikin konflik nama komponen.
- Fish shell **tidak baca** `.bashrc`/`.zshrc`. Untuk menambah PATH permanen pakai `fish_add_path`, bukan `export`.

## TODO Langsung Berikutnya

1. Test CRUD Buyer secara menyeluruh (create, edit, delete) — pastikan validasi jalan
2. Bangun halaman **Stok Gudang** (list saldo per grade + kartu mutasi stok)
3. Bangun **Form Transaksi Penjualan** (bagian paling kompleks, kerjakan bertahap)
