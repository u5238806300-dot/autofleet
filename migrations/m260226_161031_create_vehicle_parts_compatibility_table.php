<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vehicle_parts_compatibility}}`.
 */
class m260226_161031_create_vehicle_parts_compatibility_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vehicle_parts}}', [
            'vehicle_id' => $this->integer()->notNull(),
            'part_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-vehicle_parts', '{{%vehicle_parts}}', ['vehicle_id', 'part_id']);

        $this->addForeignKey(
            'fk-vehicle_parts-vehicle_id',
            '{{%vehicle_parts}}', 'vehicle_id',
            '{{%vehicles}}', 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-vehicle_parts-part_id',
            '{{%vehicle_parts}}', 'part_id',
            '{{%parts}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vehicle_parts_compatibility}}');
    }
}
