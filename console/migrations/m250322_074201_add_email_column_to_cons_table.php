<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%cons}}`.
 */
class m250322_074201_add_email_column_to_cons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('consulting', 'pochta_address' , $this->string()->null());
        $this->addColumn('consulting', 'mail' , $this->string()->null());
        $this->addColumn('consulting', 'pochta_phone' , $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
