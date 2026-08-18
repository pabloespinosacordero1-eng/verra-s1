<?php
declare(strict_types=1);

namespace App\Domain\Rule;

final class PatternRule implements RuleInterface
{
    private string $regex;

    public function __construct(string $pattern)
    {
        // Transforma el comodín '?' en un comodín regex de un solo carácter '.' respetando caracteres especiales
        $escaped = preg_quote(mb_strtolower($pattern), '/');
        $this->regex = '/^' . str_replace('\?', '.', $escaped) . '$/u';
    }

    public function matches(string $word): bool
    {
        return (bool) preg_match($this->regex, mb_strtolower($word));
    }
}
