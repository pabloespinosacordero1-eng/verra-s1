<?php
declare(strict_types=1);

namespace App\Domain\Model;

final class CustomerPreferenceAggregate
{
    /** @var Email[] */
    private array $emails = [];

    /** @var Language[] */
    private array $languages = [];

    private bool $marketingOptIn = true;

    /** @var CategoryCollection[] */
    private array $categoryCollections = [];

    public function __construct(private string $customerId) {}

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * Registra y consolida un nuevo set de preferencias usando RIGOR TOTAL de tipos del Dominio.
     */
    public function accumulate(
        Email $email, 
        Language $language, 
        bool $marketingOptIn, 
        CategoryCollection $categoryCollection // <-- Recibe el Value Object, NADA de arrays crudos
    ): void {
        $this->emails[] = $email;
        $this->languages[] = $language;
        $this->categoryCollections[] = $categoryCollection;

        if (!$marketingOptIn) {
            $this->marketingOptIn = false;
        }
    }

    /**
     * Devuelve la Entidad de Salida consolidada resolviendo los conflictos deterministas.
     */
    public function compileProfile(): CustomerProfile
    {
        $finalEmail = !empty($this->emails) ? $this->emails[0]->getValue() : '';
        $finalLanguage = $this->resolveLanguageConflict();

        // Recopilamos todas las categorías internas de las colecciones acumuladas
        $allRawCategories = [];
        foreach ($this->categoryCollections as $collection) {
            foreach ($collection->toArray() as $category) {
                $allRawCategories[] = $category;
            }
        }

        // El Value Object final se encarga de unificar, limpiar y ordenar alfabéticamente
        $finalCategoryCollection = new CategoryCollection($allRawCategories);

        return new CustomerProfile(
            $this->customerId,
            $finalEmail,
            $finalLanguage,
            $this->marketingOptIn,
            $finalCategoryCollection->toArray()
        );
    }

    private function resolveLanguageConflict(): string
    {
        if (empty($this->languages)) {
            return 'en';
        }

        $codes = array_map(fn(Language $lang) => $lang->getCode(), $this->languages);
        $frequencies = array_count_values($codes);

        uksort($frequencies, function($a, $b) use ($frequencies) {
            if ($frequencies[$a] === $frequencies[$b]) {
                return $a <=> $b;
            }
            return $frequencies[$b] <=> $frequencies[$a];
        });

        return (string) array_key_first($frequencies);
    }
}
