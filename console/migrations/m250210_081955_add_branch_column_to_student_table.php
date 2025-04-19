<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%student}}`.
 */
class m250210_081955_add_branch_column_to_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('student' , 'branch_id' , $this->integer()->null());
        $this->addForeignKey('ik_student_table_branch_table', 'student', 'branch_id', 'branch', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
