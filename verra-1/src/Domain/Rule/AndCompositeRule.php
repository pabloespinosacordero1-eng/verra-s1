<?php
declare(strict_types=1);

namespace App\Domain\Rule;

final class AndCompositeRule implements RuleInterface
{
    /** @var RuleInterface[] */
    private array $rules = [];

    public function addRule(RuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }

    public function matches(string $word): bool
    {
        foreach ($this->rules as $rule) {
            if (!$rule->matches($word)) {
                return false; // Cortocircuito lógico inmediato si una regla falla
            }
        }
        return true;
    }
}
