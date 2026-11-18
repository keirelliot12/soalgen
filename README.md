# SoalGen

Exam package generator for teachers. Write questions once in a simple Markdown
format, then print shuffled, reproducible exam packages (Paket A, B, C...) with
a separate answer key.

Built for a real workflow: many teachers still shuffle exam questions by hand in
Word. SoalGen turns one source file into print-ready packages in seconds.

## What it is

- **Input:** a Markdown file — questions, `[x]` marks the correct option.
- **Output:** two self-contained HTML files per package — the exam sheet
  (print-ready, with school letterhead slot and student identity block) and the
  answer key (kept separate, marked confidential).
- **Reproducible:** shuffling is seeded. Same file + same seed + same PHP
  version = byte-identical packages, so Paket A can be re-printed any time.

## Design notes

- No network calls, no AI/LLM inside the product, no personal data:
  the only input is the teacher's own Markdown file.
- Malformed questions are skipped with a warning instead of aborting the run.
- Plain PHP 8.1+, zero runtime dependencies. PHPUnit for tests.

## Quick start

```bash
composer install
bin/soalgen build contoh/ujian-contoh.md --seed=7 --paket=A --sekolah="MTs Contoh" --out=hasil
# -> hasil/soal-paket-A.html  (lembar soal, siap cetak)
# -> hasil/kunci-paket-A.html (kunci jawaban, terpisah & rahasia)
```

Open the HTML files in a browser and print to PDF.

Run the tests:

```bash
vendor/bin/phpunit tests
```

## Input format

```markdown
# Ujian Tengah Semester — Fiqih

## Pilihan Ganda

1. Rukun wudu yang pertama adalah ...
   - [x] Niat
   - [ ] Membasuh muka
   - [ ] Membasuh kedua tangan

2. Jumlah rakaat salat Subuh adalah ...
   - [ ] 3
   - [x] 2
   - [ ] 4
```

Rules: `#` sets the title, `##` starts a section, `N.` starts a question,
`- [x]` / `- [ ]` are options with exactly one `[x]` per question.

## Roadmap

- **v0.2** — seeded Paket A/B/C in one run; true/false and short-answer
  question types; per-section shuffling control.
- **v0.3** — styled PDF export; school letterhead templates; question bank
  (pick N random questions from a larger pool, still seeded).

## License

MIT — see [LICENSE](LICENSE).
