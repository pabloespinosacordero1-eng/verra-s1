<?php
declare(strict_types=1);

namespace App\Tests\Domain\Rule;

use App\Domain\Factory\RuleFactory;
use App\Domain\Model\Word;
use App\Domain\Model\WordCollection;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

final class ClassificationEngineTest extends TestCase
{
    public function test_length_rule_matches_exact_word_lengths(): void
    {
        $config = ["type" => "LengthRule", "length" => 4];
        $rule = RuleFactory::create($config);

        $this->assertTrue($rule->matches('read'));
        $this->assertFalse($rule->matches('replay'));
    }

    public function test_prefix_rule_matches_case_insensitive_prefixes(): void
    {
        $config = ["type" => "PrefixRule", "prefix" => "re"];
        $rule = RuleFactory::create($config);

        $this->assertTrue($rule->matches('replay'));
        $this->assertTrue($rule->matches('REMARK'));
        $this->assertFalse($rule->matches('order'));
    }

    public function test_pattern_rule_evaluates_wildcard_correctly(): void
    {
        $config = ["type" => "PatternRule", "pattern" => "c?t"];
        $rule = RuleFactory::create($config);

        $this->assertTrue($rule->matches('cat'));
        $this->assertTrue($rule->matches('cot'));
        $this->assertFalse($rule->matches('coat'));
    }

    public function test_and_composite_rule_requires_all_strategies_to_pass(): void
    {
        $config = [
            "type" => "AND",
            "rules" => [
                [ "type" => "LengthRule", "length" => 6 ],
                [ "type" => "PrefixRule", "prefix" => "re" ]
            ]
        ];

        $rule = RuleFactory::create($config);

        $this->assertTrue($rule->matches('replay'));
        $this->assertFalse($rule->matches('read'));   // Falla longitud
    }

    public function test_word_collection_throws_exception_on_invalid_items(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new WordCollection([new \stdClass()]);
    }
}
