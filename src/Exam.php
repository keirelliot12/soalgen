<?php

declare(strict_types=1);

namespace SoalGen;

final class Exam
{
    /** @var Question[] */
    public readonly array $questions;

    /**
     * @param Question[] $questions
     */
    public function __construct(
        public readonly string $title,
        array $questions,
    ) {
        $this->questions = array_values($questions);
    }
}
