<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%student}}`.
 */
class m250326_074400_add_is_down_column_to_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('student', 'is_down', $this->tinyInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
