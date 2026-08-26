<?php

declare(strict_types=1);

namespace app\models\enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case B2B_CLIENT = 'b2b_client';
    case B2B_PREMIUM = 'b2b_premium';
}
