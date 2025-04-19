<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%edu_direction}}`.
 */
class m250317_095143_add_exam_type_to_column_to_edu_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('edu_direction' , 'exam_type' , $this->json()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
