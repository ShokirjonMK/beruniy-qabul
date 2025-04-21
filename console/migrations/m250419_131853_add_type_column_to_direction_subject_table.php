<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%direction_subject}}`.
 */
class m250419_131853_add_type_column_to_direction_subject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('direction_subject', 'type', $this->tinyInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
