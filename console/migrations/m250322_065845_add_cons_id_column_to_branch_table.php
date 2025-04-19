<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%branch}}`.
 */
class m250322_065845_add_cons_id_column_to_branch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('branch', 'cons_id' , $this->integer()->null());
        $this->addForeignKey('ik_branch_table_consulting_table', 'branch', 'cons_id', 'consulting', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
