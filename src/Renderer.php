<?php

declare(strict_types=1);

namespace SoalGen;

/**
 * Renders an exam (and its answer key) as self-contained, print-ready HTML.
 * No external assets, no JavaScript — open in a browser and print to PDF.
 */
final class Renderer
{
    private function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * @param Question[] $questions already shuffled, in print order
     */
    public function renderExam(string $title, string $paket, string $sekolah, array $questions): string
    {
        $body = '';
        $number = 0;
        $lastSection = "\0"; // sentinel: never equals a real section name

        foreach ($questions as $question) {
            $number++;
            if ($question->section !== '' && $question->section !== $lastSection) {
                $body .= '<h2>' . $this->e($question->section) . "</h2>\n";
                $lastSection = $question->section;
            }
            $options = '';
            foreach ($question->options as $i => $option) {
                $options .= '      <li><span class="huruf">' . chr(65 + $i) . '.</span> '
                    . $this->e($option->text) . "</li>\n";
            }
            $body .= "<div class=\"soal\">\n"
                . '  <p class="pertanyaan"><strong>' . $number . '.</strong> '
                . $this->e($question->text) . "</p>\n"
                . "  <ul class=\"pilihan\">\n{$options}  </ul>\n</div>\n";
        }

        $kop = $sekolah !== ''
            ? '<p class="kop">' . $this->e($sekolah) . "</p>\n"
            : '';

        return <<<HTML
            <!DOCTYPE html>
            <html lang="id">
            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>{$this->e($title)} — Paket {$this->e($paket)}</title>
            <style>
              body { font-family: Georgia, 'Times New Roman', serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; color: #111; }
              .kop { text-align: center; font-weight: bold; font-size: 1.1rem; margin-bottom: 0; }
              h1 { text-align: center; font-size: 1.4rem; margin: 0.2rem 0 0.1rem; }
              .paket { text-align: center; color: #444; margin-bottom: 1.5rem; }
              h2 { font-size: 1.1rem; border-bottom: 1px solid #999; padding-bottom: 0.2rem; margin-top: 1.8rem; }
              .soal { margin: 1rem 0; page-break-inside: avoid; }
              .pertanyaan { margin: 0 0 0.4rem; }
              .pilihan { list-style: none; padding-left: 1.6rem; margin: 0; }
              .pilihan li { margin: 0.25rem 0; }
              .huruf { display: inline-block; width: 1.4rem; font-weight: bold; }
              .identitas { margin-top: 2rem; }
              .identitas td { padding: 0.15rem 0.5rem 0.15rem 0; }
              @media print { body { margin: 0; max-width: none; } }
            </style>
            </head>
            <body>
            {$kop}<h1>{$this->e($title)}</h1>
            <p class="paket">Paket {$this->e($paket)}</p>
            <table class="identitas">
              <tr><td>Nama</td><td>: ........................................</td></tr>
              <tr><td>Kelas</td><td>: ........................................</td></tr>
            </table>
            {$body}
            </body>
            </html>

            HTML;
    }

    /**
     * @param Question[] $questions already shuffled, in the same order as renderExam()
     */
    public function renderAnswerKey(string $title, string $paket, array $questions): string
    {
        $rows = '';
        $letters = Shuffler::answerLetters($questions);
        foreach ($letters as $i => $letter) {
            $rows .= '    <tr><td>' . ($i + 1) . '</td><td><strong>' . $this->e($letter) . "</strong></td></tr>\n";
        }

        return <<<HTML
            <!DOCTYPE html>
            <html lang="id">
            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Kunci Jawaban — {$this->e($title)} — Paket {$this->e($paket)}</title>
            <style>
              body { font-family: Georgia, 'Times New Roman', serif; max-width: 480px; margin: 2rem auto; padding: 0 1rem; color: #111; }
              h1 { font-size: 1.3rem; text-align: center; }
              .paket { text-align: center; color: #444; }
              table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
              th, td { border: 1px solid #666; padding: 0.4rem; text-align: center; }
              .rahasia { text-align: center; color: #a00; font-weight: bold; margin-top: 1.5rem; }
            </style>
            </head>
            <body>
            <h1>Kunci Jawaban</h1>
            <p class="paket">{$this->e($title)} — Paket {$this->e($paket)}</p>
            <table>
              <tr><th>No</th><th>Kunci</th></tr>
            {$rows}</table>
            <p class="rahasia">RAHASIA — hanya untuk guru.</p>
            </body>
            </html>

            HTML;
    }
}
