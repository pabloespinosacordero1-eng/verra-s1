<?php
// src/Domain/Model/Email.php
declare(strict_types=1);

namespace App\Domain\Model;

use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $email)
    {
        $email = strtolower(trim($email));
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            throw new InvalidArgumentException(sprintf('The email "%s" is structurally invalid.', $email));
        }

        [$username, $domain] = $parts;
        $username = explode('+', $username)[0];
        $username = str_replace('.', '', $username);

        $this->value = $username . '@' . $domain;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->getValue();
    }
}
