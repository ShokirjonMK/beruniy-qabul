<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%direction}}`.
 */
class m250317_072555_add_branch_id_to_column_to_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('direction', 'branch_id' , $this->integer()->null());
        $this->addForeignKey('ik_direction_table_branch_table', 'direction', 'branch_id', 'branch', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
