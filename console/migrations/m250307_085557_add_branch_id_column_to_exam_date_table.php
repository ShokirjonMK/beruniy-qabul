<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%exam_date}}`.
 */
class m250307_085557_add_branch_id_column_to_exam_date_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('exam_date', 'branch_id' , $this->integer()->null());
        $this->addForeignKey('ik_exam_date_table_branch_table', 'exam_date', 'branch_id', 'branch', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
