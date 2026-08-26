<?php

declare(strict_types=1);

namespace app\modules\api;

use yii\base\Module as BaseModule;

final class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\api\controllers';

    public function init(): void
    {
        parent::init();
        // Konfiguracja specyficzna dla API
    }
}
