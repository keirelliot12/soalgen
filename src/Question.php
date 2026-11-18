<?php

declare(strict_types=1);

namespace SoalGen;

final class Question
{
    /** @var Option[] */
    public readonly array $options;

    /**
     * @param Option[] $options
     */
    public function __construct(
        public readonly string $text,
        array $options,
        public readonly string $section = '',
    ) {
        $this->options = array_values($options);
    }

    public function correctIndex(): ?int
    {
        foreach ($this->options as $i => $option) {
            if ($option->isCorrect) {
                return $i;
            }
        }

        return null;
    }
}
