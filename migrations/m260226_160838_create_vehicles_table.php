<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vehicles}}`.
 */
class m260226_160838_create_vehicles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vehicles}}', [
            'id' => $this->primaryKey(),
            'vin' => $this->string(17)->notNull()->unique(),
            'make' => $this->string(64)->notNull(),
            'model' => $this->string(64)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-vehicles-vin', '{{%vehicles}}', 'vin');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vehicles}}');
    }
}
