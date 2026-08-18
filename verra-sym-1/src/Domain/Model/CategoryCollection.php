<?php
// src/Domain/Model/CategoryCollection.php
declare(strict_types=1);

namespace App\Domain\Model;

final class CategoryCollection
{
    /** @var string[] */
    private array $categories;

    /**
     * @param string[] $categories
     */
    public function __construct(array $categories)
    {
        $normalized = array_map(fn($cat) => strtolower(trim($cat)), $categories);
        $unique = array_unique($normalized);
        sort($unique);
        $this->categories = array_values($unique);
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return $this->categories;
    }
}
