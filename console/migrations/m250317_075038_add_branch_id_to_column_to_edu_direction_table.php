<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%edu_direction}}`.
 */
class m250317_075038_add_branch_id_to_column_to_edu_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('edu_direction', 'branch_id' , $this->integer()->null());
        $this->addForeignKey('ik_edu_direction_table_branch_table', 'edu_direction', 'branch_id', 'branch', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
