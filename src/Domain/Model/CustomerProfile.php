<?php

// src/Domain/Model/CustomerProfile.php
namespace App\Domain\Model;

final class CustomerProfile
{
    public function __construct(
        public string $customerId,
        public string $email,
        public string $language,
        public bool $marketingOptIn,
        public array $preferredCategories
    ) {}
}
