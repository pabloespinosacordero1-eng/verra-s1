<?php
declare(strict_types=1);

namespace App\Domain\Model;

use InvalidArgumentException;
use JsonSerializable;

final class ProfileCollection implements JsonSerializable
{
    /** @var CustomerProfile[] */
    private array $profiles = [];

    /**
     * @param array<int, mixed> $profiles
     */
    public function __construct(array $profiles = [])
    {
        foreach ($profiles as $profile) {
            if (!$profile instanceof CustomerProfile) {
                throw new InvalidArgumentException('ProfileCollection only accepts instances of CustomerProfile.');
            }
            $this->profiles[] = $profile;
        }
    }

    /**
     * @return CustomerProfile[]
     */
    public function all(): array
    {
        return $this->profiles;
    }

    /**
     * @return array<string, array<int, CustomerProfile>>
     */
    public function jsonSerialize(): array
    {
        return [
            'profiles' => $this->profiles
        ];
    }
}
