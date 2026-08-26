<?php

namespace app\models;

use Yii;

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
 * @property Parts[] $parts
 * @property VehicleParts[] $vehicleParts
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vin', 'make', 'model', 'year', 'created_at', 'updated_at'], 'required'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getParts()
    {
        return $this->hasMany(Parts::class, ['id' => 'part_id'])->viaTable('vehicle_parts', ['vehicle_id' => 'id']);
    }

    /**
     * Gets query for [[VehicleParts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleParts()
    {
        return $this->hasMany(VehicleParts::class, ['vehicle_id' => 'id']);
    }

}
