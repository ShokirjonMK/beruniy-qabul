<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%exam_date}}`.
 */
class m250404_062902_add_limit_column_to_exam_date_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('exam_date', 'limit', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
