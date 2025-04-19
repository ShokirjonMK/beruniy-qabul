<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m250128_051542_add_user_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user' , 'lang_id', $this->integer()->null());
        $this->addColumn('user' , 'telegram_id', $this->string()->null());
        $this->addColumn('user' , 'telegram_username', $this->string()->null());

        $this->addForeignKey('ik_user_table_lang_table', 'user', 'lang_id', 'lang', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
