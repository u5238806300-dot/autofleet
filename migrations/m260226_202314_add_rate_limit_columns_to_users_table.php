<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m260226_202314_add_rate_limit_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Dodajemy kolumny wymagane przez RateLimitInterface
        $this->addColumn('{{%users}}', 'allowance', $this->integer()->notNull()->defaultValue(100));
        $this->addColumn('{{%users}}', 'allowance_updated_at', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'allowance');
        $this->dropColumn('{{%users}}', 'allowance_updated_at');
    }
}
