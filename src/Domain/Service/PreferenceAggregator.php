<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\CustomerPreferenceAggregate;
use App\Domain\Model\Language;
use App\Domain\Model\Email;
use App\Domain\Model\CategoryCollection;
use App\Domain\Model\RecordCollection;   // <-- Colección rica de entrada
use App\Domain\Model\ProfileCollection;  // <-- Colección rica de salida

final class PreferenceAggregator
{
    public function aggregate(RecordCollection $collection): ProfileCollection
    {
        /** @var CustomerPreferenceAggregate[] $aggregates */
        $aggregates = [];

        foreach ($collection->all() as $record) {
            $id = trim($record->customerId);
            if ($id === '') {
                continue;
            }

            if (!isset($aggregates[$id])) {
                $aggregates[$id] = new CustomerPreferenceAggregate($id);
            }

            $aggregates[$id]->accumulate(
                new Email($record->email),
                new Language($record->language),
                $record->marketingOptIn,
                new CategoryCollection($record->preferredCategories)
            );
        }

        $profiles = [];
        foreach ($aggregates as $aggregate) {
            $profiles[] = $aggregate->compileProfile();
        }

        return new ProfileCollection($profiles);
    }
}
