<?php

declare(strict_types=1);

namespace app\models\forms;

use yii\base\Model;
use app\models\Part;
use yii\validators\Validator;

final class CreatePartForm extends Model
{
    public ?string $sku = null;
    public ?string $name = null;
    public ?float $price = null;
    public int $stock = 0;

    /**
     * @return array|\mixed[][]|Validator[]
     */
    public function rules(): array
    {
        return [
            [['sku', 'name', 'price'], 'required'],
            [['price'], 'number', 'min' => 0.01],
            [['stock'], 'integer', 'min' => 0],
            [['sku', 'name'], 'string', 'max' => 255],
            [['sku'], 'unique', 'targetClass' => Part::class, 'targetAttribute' => 'sku'],
        ];
    }
}
