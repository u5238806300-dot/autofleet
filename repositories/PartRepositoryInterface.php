<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Part;

interface PartRepositoryInterface
{
    /**
     * @param string $sku
     * @return Part|null
     */
    public function findBySku(string $sku): ?Part;

    /**
     * @param string $vin
     * @return Part[]
     */
    public function findCompatiblePartsByVin(string $vin): array;

    /**
     * @param Part $part
     * @return bool
     */
    public function save(Part $part): bool;
}
