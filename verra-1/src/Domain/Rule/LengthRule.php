<?php
declare(strict_types=1);

namespace App\Domain\Rule;

final class LengthRule implements RuleInterface
{
    public function __construct(private int $length) {}

    public function matches(string $word): bool
    {
        return mb_strlen($word) === $this->length;
    }
}
