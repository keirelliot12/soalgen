<?php

declare(strict_types=1);

namespace SoalGen;

/**
 * Deterministic shuffling for exam packages.
 *
 * Same input + same seed + same PHP version => byte-identical output,
 * so "Paket A" printed today matches "Paket A" re-printed next week.
 */
final class Shuffler
{
    /**
     * Shuffle question order AND option order within each question.
     * The input array is not modified; new Question objects are returned.
     *
     * @param Question[] $questions
     * @return Question[]
     */
    public static function shuffle(array $questions, int $seed): array
    {
        mt_srand($seed);

        $questions = array_values($questions);
        for ($i = count($questions) - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            $tmp = $questions[$i];
            $questions[$i] = $questions[$j];
            $questions[$j] = $tmp;
        }

        $out = [];
        foreach ($questions as $question) {
            $options = $question->options;
            for ($i = count($options) - 1; $i > 0; $i--) {
                $j = mt_rand(0, $i);
                $tmp = $options[$i];
                $options[$i] = $options[$j];
                $options[$j] = $tmp;
            }
            $out[] = new Question($question->text, $options, $question->section);
        }

        return $out;
    }

    /**
     * Answer letters (A, B, C, ...) pointing at the correct option
     * of each question, in the given (already shuffled) order.
     *
     * @param Question[] $questions
     * @return string[]
     */
    public static function answerLetters(array $questions): array
    {
        $letters = [];
        foreach ($questions as $question) {
            $index = $question->correctIndex();
            $letters[] = $index === null ? '?' : chr(65 + $index);
        }

        return $letters;
    }
}
