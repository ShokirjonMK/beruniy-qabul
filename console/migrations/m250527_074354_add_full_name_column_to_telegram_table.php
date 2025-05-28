<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram}}`.
 */
class m250527_074354_add_full_name_column_to_telegram_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('telegram', 'first_name', $this->string(255)->null());
        $this->addColumn('telegram', 'last_name', $this->string(255)->null());
        $this->addColumn('telegram', 'middle_name', $this->string(255)->null());
        $this->addColumn('telegram', 'gender', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
