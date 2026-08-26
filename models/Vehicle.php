<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "vehicles".
 *
 * @property int $id
 * @property string $vin
 * @property string $make
 * @property string $model
 * @property int $year
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Part[] $parts
 * @property VehiclePart[] $vehicleParts
 */
class Vehicle extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehicles';
    }

    /**
     * @return \class-string[]
     */
    public function behaviors(): array
    {
        return [
            \yii\behaviors\TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vin', 'make', 'model', 'year'], 'required'],
            [['year', 'created_at', 'updated_at'], 'integer'],
            [['vin'], 'string', 'max' => 17],
            [['make', 'model'], 'string', 'max' => 64],
            [['vin'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vin' => 'Vin',
            'make' => 'Make',
            'model' => 'Model',
            'year' => 'Year',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Parts]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getParts(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Part::class, ['id' => 'part_id'])
            ->viaTable('{{%vehicle_parts}}', ['vehicle_id' => 'id']);
    }

    /**
     * Gets query for [[VehicleParts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleParts()
    {
        return $this->hasMany(VehiclePart::class, ['vehicle_id' => 'id']);
    }

}
