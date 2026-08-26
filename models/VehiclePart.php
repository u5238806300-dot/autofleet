<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicle_parts".
 *
 * @property int $vehicle_id
 * @property int $part_id
 *
 * @property Part $part
 * @property Vehicle $vehicle
 */
class VehiclePart extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehicle_parts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'part_id'], 'required'],
            [['vehicle_id', 'part_id'], 'integer'],
            [['vehicle_id', 'part_id'], 'unique', 'targetAttribute' => ['vehicle_id', 'part_id']],
            [['part_id'], 'exist', 'skipOnError' => true, 'targetClass' => Part::class, 'targetAttribute' => ['part_id' => 'id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::class, 'targetAttribute' => ['vehicle_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vehicle_id' => 'Vehicle ID',
            'part_id' => 'Part ID',
        ];
    }

    /**
     * Gets query for [[Part]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPart()
    {
        return $this->hasOne(Part::class, ['id' => 'part_id']);
    }

    /**
     * Gets query for [[Vehicle]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::class, ['id' => 'vehicle_id']);
    }

}
