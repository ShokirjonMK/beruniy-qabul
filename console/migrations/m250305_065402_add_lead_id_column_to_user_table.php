<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m250305_065402_add_lead_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'lead_id', $this->json()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
