<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%auth_item}}`.
 */
class m250419_152754_add_status_column_to_auth_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_item', 'status', $this->tinyInteger(1)->defaultValue(1));
        $this->addColumn('auth_item', 'branch_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
