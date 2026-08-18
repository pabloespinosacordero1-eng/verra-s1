<?php
declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\DTO\CustomerRecordInput;
use App\Domain\Model\RecordCollection;
use App\Domain\Service\PreferenceAggregator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

final class PreferenceAggregatorTest extends TestCase
{
    private PreferenceAggregator $aggregator;

    protected function setUp(): void
    {
        $this->aggregator = new PreferenceAggregator();
    }

    /**
     * Regla: El cortocircuito de MarketingOptIn (Si alguno es false, el resultado es false).
     */
    public function test_it_forces_marketing_opt_in_to_false_if_any_record_is_false(): void
    {
        $record1 = $this->createBaseRecord('A12', 'EN', true);
        $record2 = $this->createBaseRecord('A12', 'EN', false);
        $record3 = $this->createBaseRecord('A12', 'EN', true);

        $collection = new RecordCollection([$record1, $record2, $record3]);
        $result = $this->aggregator->aggregate($collection);

        $profiles = $result->all();
        $this->assertCount(1, $profiles);
        $this->assertFalse($profiles[0]->marketingOptIn);
    }

    /**
     * Regla: Idioma más frecuente.
     */
    public function test_it_picks_the_most_frequent_language_among_records(): void
    {
        $record1 = $this->createBaseRecord('B55', 'ES', true);
        $record2 = $this->createBaseRecord('B55', 'EN', true);
        $record3 = $this->createBaseRecord('B55', 'ES', true);

        $collection = new RecordCollection([$record1, $record2, $record3]);
        $result = $this->aggregator->aggregate($collection);

        $profiles = $result->all();
        $this->assertEquals('es', $profiles[0]->language);
    }

    /**
     * Regla: Empate de idioma resolviéndose por orden alfabético.
     */
    public function test_it_resolves_language_ties_alphabetically(): void
    {
        $record1 = $this->createBaseRecord('C99', 'FR', true);
        $record2 = $this->createBaseRecord('C99', 'DE', true);

        $collection = new RecordCollection([$record1, $record2]);
        $result = $this->aggregator->aggregate($collection);

        $profiles = $result->all();
        $this->assertEquals('de', $profiles[0]->language);
    }

    /**
     * Regla: Categorías (Unión, limpieza de duplicados y ordenación alfabética).
     */
    public function test_it_normalizes_unifies_and_alphabetically_sorts_preferred_categories(): void
    {
        $record1 = $this->createBaseRecord('A12', 'EN', true, ['Sports', 'tech']);
        $record2 = $this->createBaseRecord('A12', 'EN', true, ['TECH', 'books']);

        $collection = new RecordCollection([$record1, $record2]);
        $result = $this->aggregator->aggregate($collection);

        $profiles = $result->all();
        $expectedCategories = ['books', 'sports', 'tech'];
        $this->assertEquals($expectedCategories, $profiles[0]->preferredCategories);
    }

    /**
     * Regla: Email (Eliminación de puntos, sufijos con '+' y conversión a minúsculas).
     */
    public function test_it_perfectly_normalizes_complex_email_structures(): void
    {
        $record = $this->createBaseRecord('A12', 'EN', true);
        $record->email = 'Test.User+alexandria@://example.com';

        $collection = new RecordCollection([$record]);
        $result = $this->aggregator->aggregate($collection);

        $profiles = $result->all();
        $this->assertEquals('testuser@://example.com', $profiles[0]->email);
    }

    /**
     * Regla de protección de tipo: RecordCollection debe rechazar datos primitivos o erróneos.
     */
    public function test_it_throws_exception_if_invalid_type_is_passed_to_record_collection(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RecordCollection([new \stdClass()]);
    }

    /**
     * Helper para construir DTOs rápidamente en los escenarios de pruebas
     * @param string[] $categories
     */
    private function createBaseRecord(string $id, string $lang, bool $marketing, array $categories = []): CustomerRecordInput
    {
        $record = new CustomerRecordInput();
        $record->customerId = $id;
        $record->email = 'test@example.com';
        $record->language = $lang;
        $record->marketingOptIn = $marketing;
        $record->preferredCategories = $categories;
        return $record;
    }
}
