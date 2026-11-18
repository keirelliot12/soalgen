<?php

declare(strict_types=1);

namespace SoalGen\Tests;

use PHPUnit\Framework\TestCase;
use SoalGen\Parser;
use SoalGen\Renderer;
use SoalGen\Shuffler;

final class RendererTest extends TestCase
{
    public function testExamHtmlEscapesContentAndShowsPackage(): void
    {
        $markdown = <<<'MD'
            # Ujian <script>alert(1)</script>

            1. Mana yang benar?
               - [x] Ini
               - [ ] Itu
            MD;

        [$exam] = (new Parser())->parse($markdown);
        $questions = Shuffler::shuffle($exam->questions, 5);
        $html = (new Renderer())->renderExam($exam->title, 'A', 'MTs Contoh', $questions);

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('Paket A', $html);
        $this->assertStringContainsString('MTs Contoh', $html);
        $this->assertStringContainsString('Mana yang benar?', $html);
    }

    public function testAnswerKeyMatchesShuffledOrder(): void
    {
        $markdown = <<<'MD'
            # Kuis

            1. Satu tambah satu?
               - [ ] Satu
               - [x] Dua
               - [ ] Tiga

            2. Ibu kota Perancis?
               - [x] Paris
               - [ ] Lyon
               - [ ] Nice
            MD;

        [$exam] = (new Parser())->parse($markdown);
        $questions = Shuffler::shuffle($exam->questions, 11);
        $keyHtml = (new Renderer())->renderAnswerKey($exam->title, 'B', $questions);

        $letters = Shuffler::answerLetters($questions);
        foreach ($letters as $i => $letter) {
            // Row "No" => correct letter appears in the key table.
            $this->assertStringContainsString('<td>' . ($i + 1) . '</td><td><strong>' . $letter . '</strong></td>', $keyHtml);
        }
        $this->assertStringContainsString('RAHASIA', $keyHtml);
    }
}
