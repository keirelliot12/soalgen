# Gamifikasi SoalGen untuk Kelas Bawah (Kelas 1–2)

Aturan main untuk bank soal di `bank-soal-matematika-kelas-1-2.md`.
Satu bank soal, dua muka: guru mencetak paket soal dari file yang sama,
anak memainkan soalnya sebagai game. Tidak ada leaderboard.

## Sesi main

- Satu sesi = 5 soal (satu level). Maksimal 10 soal per sesi kalau anak
  mau lanjut — jangan lebih, anak kelas bawah cepat lelah.
- Soal dibacakan dengan suara (guru/orang tua membacakan, atau audio
  baca soal di aplikasi nanti). Soal sengaja pendek, jawaban satu angka.
- Anak menjawab dengan mengetuk jawaban, bukan mengetik.

## Poin, bintang, level

- Jawaban benar: **+10 poin**.
- Streak: 3 jawaban benar beruntun = **bonus +5 poin** (sekali per streak,
  lalu streak dihitung ulang dari 0).
- Jawaban salah: 0 poin, tidak ada pengurangan — dan langsung tunjukkan
  jawaban benarnya dengan kalimat pendek ("3 + 2 itu 5, ya").
- Bintang per sesi 5 soal: 5 benar = 3 bintang, 4 benar = 2 bintang,
  3 benar = 1 bintang, kurang dari 3 = "Coba lagi, yuk!" (bukan "gagal").
- Naik level: butuh minimal 1 bintang untuk membuka level berikutnya.
  Level 1 → 2 → 3 → 4, sesuai pembagian di bank soal.
- Lencana (sekali dapat, disimpan): "Pemburu Bintang" (3 bintang pertama),
  "Streak 3" (streak pertama), "Tamat Level 4" (selesai semua level).

## Yang sengaja TIDAK ada

- Tanpa leaderboard dan tanpa peringkat antar anak — anak kecil gampang
  minder kalau dibandingkan.
- Tanpa timer yang menekan. Kalau mau ada waktu, pakai timer lembut
  (misalnya 30 detik per soal) hanya sebagai pengingat, bukan pemotong poin.
- Tanpa hukuman: salah tidak mengurangi poin atau bintang yang sudah didapat.

## Cara guru menambah soal

- Tambah soal baru di file bank soal dengan format SoalGen yang sama:
  nomor soal, lalu `- [x]` untuk jawaban benar (tepat satu) dan
  `- [ ]` untuk pengecoh. SoalGen melewati soal yang formatnya salah,
  jadi satu soal rusak tidak merusak seluruh bank.
- Jaga soal tetap pendek, satu operasi per soal, dan pengecohnya
  jawaban yang "hampir benar" (selisih 1) — bukan jawaban asal.
- Satu topik = satu file bank soal. Berikutnya yang paling masuk akal:
  Bahasa Indonesia kelas 1–2, lalu PAI kelas 1–2.
