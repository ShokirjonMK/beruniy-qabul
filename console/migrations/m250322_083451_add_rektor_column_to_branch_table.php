<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%branch}}`.
 */
class m250322_083451_add_rektor_column_to_branch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('branch', 'rector_uz' , $this->string()->null());
        $this->addColumn('branch', 'rector_ru' , $this->string()->null());
        $this->addColumn('branch', 'rector_en' , $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
