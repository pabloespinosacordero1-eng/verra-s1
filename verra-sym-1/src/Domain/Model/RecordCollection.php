<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\DTO\CustomerRecordInput;
use InvalidArgumentException;

final class RecordCollection
{
    /** @var CustomerRecordInput[] */
    private array $records = [];

    /**
     * @param array<int, mixed> $records
     */
    public function __construct(array $records = [])
    {
        foreach ($records as $record) {
            if (!$record instanceof CustomerRecordInput) {
                throw new InvalidArgumentException('RecordCollection only accepts instances of CustomerRecordInput.');
            }
            $this->records[] = $record;
        }
    }

    /**
     * @return CustomerRecordInput[]
     */
    public function all(): array
    {
        return $this->records;
    }
}
