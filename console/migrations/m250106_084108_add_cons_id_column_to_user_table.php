<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m250106_084108_add_cons_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */

    public function safeUp()
    {
        $this->addColumn('user' , 'cons_id' , $this->integer()->defaultValue(1));
        $this->addForeignKey('ik_user_table_consulting_table', 'user', 'cons_id', 'consulting', 'id');
    }

    /**
     * {@inheritdoc}
     */

    public function safeDown()
    {
    }
}
