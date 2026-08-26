<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "parts".
 *
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property float $price
 * @property int $stock
 * @property int $created_at
 * @property int $updated_at
 *
 * @property VehicleParts[] $vehicleParts
 * @property Vehicles[] $vehicles
 */
class Part extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%parts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock'], 'default', 'value' => 0],
            [['sku', 'name', 'price', 'created_at', 'updated_at'], 'required'],
            [['price'], 'number'],
            [['stock', 'created_at', 'updated_at'], 'integer'],
            [['sku'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Sku',
            'name' => 'Name',
            'price' => 'Price',
            'stock' => 'Stock',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[VehicleParts]].
     *
     * @return ActiveQuery
     */
    public function getVehicleParts()
    {
        return $this->hasMany(VehicleParts::class, ['part_id' => 'id']);
    }

    /**
     * @return \class-string[]
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getVehicles(): ActiveQuery
    {
        return $this->hasMany(Vehicle::class, ['id' => 'vehicle_id'])
            ->viaTable('{{%vehicle_parts}}', ['part_id' => 'id']);
    }

}
