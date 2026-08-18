<?php
// src/Domain/Model/Language.php
declare(strict_types=1);

namespace App\Domain\Model;

final class Language
{
    private string $code;

    public function __construct(string $language)
    {
        $this->code = substr(strtolower(trim($language)), 0, 2);
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
