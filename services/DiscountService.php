<?php

declare(strict_types=1);

namespace app\services;

use app\models\enums\UserRole;

final readonly class DiscountService
{
    /**
     * @param float $basePrice
     * @param UserRole $role
     * @return float
     */
    public function calculateDiscountedPrice(float $basePrice, UserRole $role): float
    {
        $discountMultiplier = match ($role) {
            UserRole::B2B_PREMIUM => 0.75, // 25% zniżki
            UserRole::B2B_CLIENT => 0.90,  // 10% zniżki
            UserRole::ADMIN => 1.00,       // Brak zniżki dla admina
        };

        return round($basePrice * $discountMultiplier, 2);
    }
}
