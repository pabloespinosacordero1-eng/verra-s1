<?php
declare(strict_types=1);

namespace App\Domain\Rule;

final class PrefixRule implements RuleInterface
{
    public function __construct(private string $prefix) {}

    public function matches(string $word): bool
    {
        // Case-insensitive nativo
        return str_starts_with(mb_strtolower($word), mb_strtolower($this->prefix));
    }
}
