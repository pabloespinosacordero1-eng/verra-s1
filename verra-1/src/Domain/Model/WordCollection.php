<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Rule\RuleInterface;
use InvalidArgumentException;

final class WordCollection
{
    /** @var Word[] */
    private array $words = [];

    /**
     * @param array<int, mixed> $words
     */
    public function __construct(array $words = [])
    {
        foreach ($words as $word) {
            if (!$word instanceof Word) {
                throw new InvalidArgumentException('WordCollection only accepts instances of Word.');
            }
            $this->words[] = $word;
        }
    }

    public function add(Word $word): void
    {
        $this->words[] = $word;
    }

    /**
     * Aplica la estrategia/composición y devuelve una nueva colección (Inmutabilidad)
     */
    public function filterBy(RuleInterface $rule): self
    {
        $filtered = [];
        foreach ($this->words as $word) {
            if ($rule->matches($word->getValue())) {
                $filtered[] = $word;
            }
        }
        return new self($filtered);
    }

    /**
     * @return Word[]
     */
    public function all(): array
    {
        return $this->words;
    }
}
