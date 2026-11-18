<?php

declare(strict_types=1);

namespace SoalGen\Tests;

use PHPUnit\Framework\TestCase;
use SoalGen\Parser;

final class ParserTest extends TestCase
{
    public function testParsesTitleSectionsQuestionsAndCorrectOptions(): void
    {
        $markdown = <<<'MD'
            # Ujian Tengah Semester

            ## Pilihan Ganda

            1. Ibu kota Indonesia adalah ...
               - [ ] Surabaya
               - [x] Jakarta
               - [ ] Bandung

            ## Isian

            1. 2 + 2 = ...
               - [x] 4
               - [ ] 5
            MD;

        [$exam, $warnings] = (new Parser())->parse($markdown);

        $this->assertSame([], $warnings);
        $this->assertSame('Ujian Tengah Semester', $exam->title);
        $this->assertCount(2, $exam->questions);

        $this->assertSame('Pilihan Ganda', $exam->questions[0]->section);
        $this->assertSame(1, $exam->questions[0]->correctIndex());
        $this->assertSame('Jakarta', $exam->questions[0]->options[1]->text);

        $this->assertSame('Isian', $exam->questions[1]->section);
    }

    public function testSkipsMalformedQuestionsWithWarning(): void
    {
        $markdown = <<<'MD'
            # Ujian

            1. Soal tanpa pilihan sama sekali

            2. Soal dengan dua jawaban benar
               - [x] A
               - [x] B

            3. Soal yang benar
               - [x] Benar
               - [ ] Salah
            MD;

        [$exam, $warnings] = (new Parser())->parse($markdown);

        $this->assertCount(1, $exam->questions);
        $this->assertSame('Soal yang benar', $exam->questions[0]->text);
        $this->assertCount(2, $warnings);
    }

    public function testEmptyInputYieldsEmptyExam(): void
    {
        [$exam, $warnings] = (new Parser())->parse('');

        $this->assertSame('Ujian', $exam->title);
        $this->assertCount(0, $exam->questions);
        $this->assertSame([], $warnings);
    }
}
