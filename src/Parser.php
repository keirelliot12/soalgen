<?php

declare(strict_types=1);

namespace SoalGen;

/**
 * Parses the SoalGen Markdown format into an Exam.
 *
 * Format:
 *   # Exam title            (first H1 becomes the title)
 *   ## Section name         (optional grouping, e.g. "Pilihan Ganda")
 *   1. Question text...     (numbered question)
 *      - [x] Correct option (exactly one [x] per question)
 *      - [ ] Wrong option
 *
 * Returns [Exam, string[] $warnings]. Malformed questions are skipped
 * with a warning instead of aborting the whole file.
 */
final class Parser
{
    /**
     * @return array{Exam, string[]}
     */
    public function parse(string $markdown): array
    {
        $title = 'Ujian';
        $section = '';
        $questions = [];
        $warnings = [];
        $current = null; // ['text' => string, 'options' => Option[], 'section' => string]

        $flush = function () use (&$current, &$questions, &$warnings): void {
            if ($current === null) {
                return;
            }
            $optionCount = count($current['options']);
            $correctCount = 0;
            foreach ($current['options'] as $option) {
                if ($option->isCorrect) {
                    $correctCount++;
                }
            }
            if ($optionCount < 2) {
                $warnings[] = "Soal \"{$current['text']}\" hanya punya {$optionCount} pilihan — dilewati.";
            } elseif ($correctCount !== 1) {
                $warnings[] = "Soal \"{$current['text']}\" punya {$correctCount} jawaban benar (harus tepat 1) — dilewati.";
            } else {
                $questions[] = new Question($current['text'], $current['options'], $current['section']);
            }
            $current = null;
        };

        foreach (explode("\n", $markdown) as $rawLine) {
            $line = trim($rawLine);
            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, '# ')) {
                if ($title === 'Ujian') {
                    $title = trim(substr($line, 2));
                }
                continue;
            }

            if (str_starts_with($line, '## ')) {
                $flush();
                $section = trim(substr($line, 3));
                continue;
            }

            if (preg_match('/^(\d+)[.)]\s+(.+)$/u', $line, $m)) {
                $flush();
                $current = ['text' => trim($m[2]), 'options' => [], 'section' => $section];
                continue;
            }

            if ($current !== null && preg_match('/^[-*]\s*\[([ xX])\]\s*(.+)$/u', $line, $m)) {
                $current['options'][] = new Option(trim($m[2]), strtolower($m[1]) === 'x');
                continue;
            }

            // Anything else (notes, blank-ish lines) is ignored.
        }
        $flush();

        return [new Exam($title, $questions), $warnings];
    }
}
