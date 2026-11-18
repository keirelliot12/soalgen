<?php

declare(strict_types=1);

namespace SoalGen\Tests;

use PHPUnit\Framework\TestCase;
use SoalGen\Option;
use SoalGen\Question;
use SoalGen\Shuffler;

final class ShufflerTest extends TestCase
{
    /**
     * @return Question[]
     */
    private function sampleQuestions(int $count = 6): array
    {
        $questions = [];
        for ($i = 1; $i <= $count; $i++) {
            $questions[] = new Question(
                "Soal nomor {$i}",
                [
                    new Option('Salah satu', false),
                    new Option('Benar', true),
                    new Option('Salah dua', false),
                    new Option('Salah tiga', false),
                ]
            );
        }

        return $questions;
    }

    public function testSameSeedProducesIdenticalOutput(): void
    {
        $first = Shuffler::shuffle($this->sampleQuestions(), 42);
        $second = Shuffler::shuffle($this->sampleQuestions(), 42);

        $this->assertSame(
            array_map(fn ($q) => $q->text, $first),
            array_map(fn ($q) => $q->text, $second)
        );
        $this->assertSame(Shuffler::answerLetters($first), Shuffler::answerLetters($second));
    }

    public function testShuffleIsAPermutationThatKeepsAnswersValid(): void
    {
        $original = $this->sampleQuestions();
        $shuffled = Shuffler::shuffle($original, 7);

        // Same set of questions, possibly reordered.
        $before = array_map(fn ($q) => $q->text, $original);
        $after = array_map(fn ($q) => $q->text, $shuffled);
        sort($before);
        sort($after);
        $this->assertSame($before, $after);

        // Every question still has exactly one correct option after shuffling.
        foreach ($shuffled as $question) {
            $correct = 0;
            foreach ($question->options as $option) {
                if ($option->isCorrect) {
                    $correct++;
                }
            }
            $this->assertSame(1, $correct);
        }

        // Answer letters stay within A-D.
        foreach (Shuffler::answerLetters($shuffled) as $letter) {
            $this->assertMatchesRegularExpression('/^[A-D]$/', $letter);
        }
    }

    public function testDifferentSeedsVeryLikelyDiffer(): void
    {
        $a = Shuffler::shuffle($this->sampleQuestions(), 1);
        $b = Shuffler::shuffle($this->sampleQuestions(), 999);

        $orderA = array_map(fn ($q) => $q->text, $a);
        $orderB = array_map(fn ($q) => $q->text, $b);
        $lettersA = implode('', Shuffler::answerLetters($a));
        $lettersB = implode('', Shuffler::answerLetters($b));

        $this->assertTrue($orderA !== $orderB || $lettersA !== $lettersB);
    }

    public function testInputArrayIsNotModified(): void
    {
        $original = $this->sampleQuestions();
        $snapshot = array_map(fn ($q) => $q->text, $original);

        Shuffler::shuffle($original, 123);

        $this->assertSame($snapshot, array_map(fn ($q) => $q->text, $original));
    }
}
