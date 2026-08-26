<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%parts}}`.
 */
class m260226_160944_create_parts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%parts}}', [
            'id' => $this->primaryKey(),
            'sku' => $this->string(64)->notNull()->unique(),
            'name' => $this->string(255)->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'stock' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%parts}}');
    }
}
