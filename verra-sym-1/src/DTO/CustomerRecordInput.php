<?php
declare(strict_types=1);

namespace App\DTO;

final class CustomerRecordInput
{
    public string $customerId = '';
    public string $email = '';
    public string $language = '';
    public bool $marketingOptIn = true;
    /** @var string[] */
    public array $preferredCategories = [];
}
