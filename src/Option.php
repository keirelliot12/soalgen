<?php

declare(strict_types=1);

namespace SoalGen;

final class Option
{
    public function __construct(
        public readonly string $text,
        public readonly bool $isCorrect,
    ) {}
}
