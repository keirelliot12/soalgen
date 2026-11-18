# CATATAN UNTUK GUS — SoalGen (proyek Alignerr)

Repo scaffold v0.1 sudah jadi di folder ini. Ini kandidat **paling bersih**
buat dibawa ke aplikasi Alignerr "Software Developer".

## Kenapa proyek ini lolos 3 tes

1. **Tidak ada AI di dalam produknya.** Murni parser + acak + render HTML.
   AI cuma muncul di *cara kamu kerja* pas sesi (assistant yang memang harus
   kamu nilai) — bukan di dalam software-nya.
2. **Datanya bukan data orang lain.** Inputnya file Markdown milik guru
   sendiri; contoh di `contoh/` isinya soal pengetahuan umum generik.
3. **Milikmu pribadi, open-source MIT.** Bukan produk perusahaan, bukan di
   bawah NDA, bukan tool keamanan.

## Verifikasi sebelum push (sudah dijalankan 18 Nov 2026)

Tes sudah dijalankan dan semuanya hijau:

```bash
cd soalgen
composer install
vendor/bin/phpunit tests   # OK (9 tests, 38 assertions)
bin/soalgen build contoh/ujian-contoh.md --seed=7 --paket=A --out=hasil
# OK: 6 soal -> hasil/soal-paket-A.html, hasil/kunci-paket-A.html (seed 7)
```

## Langkah ke GitHub + aplikasi Alignerr

1. Review dulu isi `src/` — pastikan kamu paham tiap file (pewawancara
   bisa nanya).
2. Buat repo **di akun pribadimu** (`keirelliot12`), BUKAN di organisasi.
   Nama saran: `soalgen`. Public, lisensi MIT (sudah ada file LICENSE).
3. Push folder ini apa adanya.
4. Di aplikasi Alignerr, deskripsikan sebagai:
   *"Open-source exam package generator for teachers (PHP CLI) — Markdown
   in, print-ready shuffled exam packages + separate answer key out."*
   Jangan tonjolkan sisi AI — ini memang bukan proyek AI.

## Yang TIDAK boleh dibawa

- **Santri360** — kena dua aturan sekaligus: produk company/startup, dan
  memproses data pribadi santri/wali.
- Repo yang isinya API key, token, atau data siapa pun.

## Aturan main selama sesi Alignerr

- **Rating dan alasan tertulis harus kamu tulis sendiri, tanpa bantuan AI.**
  Itu ketentuan mereka — aku nggak boleh nulisin pas sesi berjalan.
- Yang boleh: latihan nulis evaluasi bahasa Inggris *sebelum* sesi.
  (Paket latihan ngobrol kemarin juga ngelatih otot yang sama.)

## Isi v0.1

- `bin/soalgen` — CLI: `build` + `--seed` `--paket` `--sekolah` `--out`
- `src/Parser.php` — Markdown → struktur soal (soal rusak dilewati + warning)
- `src/Shuffler.php` — acak urutan soal & pilihan, deterministik per seed
- `src/Renderer.php` — HTML siap cetak + kunci jawaban terpisah
- `tests/` — 3 file test PHPUnit (parser, shuffler, renderer)
- `contoh/ujian-contoh.md` — contoh input generik

Roadmap v0.2/v0.3 ada di README (Paket A/B/C sekaligus, tipe soal
benar/salah & isian, bank soal, ekspor PDF). Bahan sesinya panjang —
parser, algoritma acak ber-seed, templating — pas buat format sesi mereka
yang banding-bandingin jawaban AI.

## Kalau Alignerr menolak proyek ini

Fallback yang juga 100% bersih: **library jadwal sholat & kalender Hijriah
untuk PHP** (matematika murni, tanpa AI). Bilang aja, strukturnya aku siapkan.
