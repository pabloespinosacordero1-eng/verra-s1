<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Rule\RuleInterface;
use App\Domain\Rule\LengthRule;
use App\Domain\Rule\PrefixRule;
use App\Domain\Rule\PatternRule;
use App\Domain\Rule\AndCompositeRule;
use InvalidArgumentException;

final class RuleFactory
{
    /**
     * @param array<string, mixed> $config
     */
    public static function create(array $config): RuleInterface
    {
        $type = (string) ($config['type'] ?? '');

        return match ($type) {
            'LengthRule' => new LengthRule((int)($config['length'] ?? 0)),
            'PrefixRule' => new PrefixRule((string)($config['prefix'] ?? '')),
            'PatternRule' => new PatternRule((string)($config['pattern'] ?? '')),
            'AND' => self::buildAndComposite($config['rules'] ?? []),
            default => throw new InvalidArgumentException(sprintf('Unknown rule type: "%s"', $type))
        };
    }

    /**
     * @param array<int, array<string, mixed>> $rulesConfig
     */
    private static function buildAndComposite(array $rulesConfig): AndCompositeRule
    {
        $composite = new AndCompositeRule();
        foreach ($rulesConfig as $ruleData) {
            $composite->addRule(self::create($ruleData));
        }
        return $composite;
    }
}
