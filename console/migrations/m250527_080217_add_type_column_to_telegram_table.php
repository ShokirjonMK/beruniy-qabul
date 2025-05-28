<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram}}`.
 */
class m250527_080217_add_type_column_to_telegram_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('telegram', 'type', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
