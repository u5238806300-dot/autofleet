<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Part;
use app\models\Vehicle;
use yii\base\InvalidConfigException;
use yii\db\Exception;

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
     * @throws InvalidConfigException
     */
    public function findCompatiblePartsByVin(string $vin): array
    {
        $vehicle = Vehicle::findOne(['vin' => $vin]);
        if (!$vehicle) {
            return [];
        }

        /** @var Part[] $parts */
        $parts = $vehicle->getParts()->all();

        return $parts;
    }

    /**
     * @param Part $part
     * @return bool
     * @throws Exception
     */
    public function save(Part $part): bool
    {
        return $part->save();
    }
}
