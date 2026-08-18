<?php
declare(strict_types=1);

namespace App\Domain\Model;

final class Word
{
    public function __construct(private string $value) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLength(): int
    {
        return mb_strlen($this->value);
    }
}
