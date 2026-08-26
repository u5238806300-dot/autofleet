<?php

declare(strict_types=1);

namespace tests\unit\services;

use PHPUnit\Framework\TestCase;
use app\services\DiscountService;
use app\models\enums\UserRole;

final class DiscountServiceTest extends TestCase
{
    private DiscountService $service;

    protected function setUp(): void
    {
        $this->service = new DiscountService();
    }

    public function testCalculateDiscountedPriceForPremiumClient(): void
    {
        $price = $this->service->calculateDiscountedPrice(100.0, UserRole::B2B_PREMIUM);
        $this->assertEquals(75.0, $price); // 25% zniżki
    }

    public function testCalculateDiscountedPriceForStandardClient(): void
    {
        $price = $this->service->calculateDiscountedPrice(100.0, UserRole::B2B_CLIENT);
        $this->assertEquals(90.0, $price); // 10% zniżki
    }

    public function testAdminGetsNoDiscount(): void
    {
        $price = $this->service->calculateDiscountedPrice(100.0, UserRole::ADMIN);
        $this->assertEquals(100.0, $price); // 0% zniżki
    }
}
