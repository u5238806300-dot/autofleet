<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Part;
use app\models\Vehicle;

final readonly class PartRepository implements PartRepositoryInterface
{
    /**
     * @param string $sku
     * @return Part|null
     */
    public function findBySku(string $sku): ?Part
    {
        return Part::findOne(['sku' => $sku]);
    }

    /**
     * @param string $vin
     * @return Part[]
     */
    public function findCompatiblePartsByVin(string $vin): array
    {
        $vehicle = Vehicle::findOne(['vin' => $vin]);
        if (!$vehicle) {
            return [];
        }

        return $vehicle->getParts()->all();
    }

    /**
     * @param Part $part
     * @return bool
     */
    public function save(Part $part): bool
    {
        return $part->save();
    }
}
