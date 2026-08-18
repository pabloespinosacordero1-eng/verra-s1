<?php
declare(strict_types=1);

namespace App\Domain\Rule;

interface RuleInterface
{
    public function matches(string $word): bool;
}
