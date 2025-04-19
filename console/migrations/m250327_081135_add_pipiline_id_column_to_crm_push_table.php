<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%crm_push}}`.
 */
class m250327_081135_add_pipiline_id_column_to_crm_push_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('crm_push', 'pipeline_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
