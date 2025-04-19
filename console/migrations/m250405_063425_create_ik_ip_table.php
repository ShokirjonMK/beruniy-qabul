<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ik_ip}}`.
 */
class m250405_063425_create_ik_ip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ik_ip}}', [
            'id' => $this->primaryKey(),
            'ip_address' => $this->string(255)->null(),
            'branch_id' => $this->integer()->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ]);
        $this->addForeignKey('ik_ik_ip_table_branch_table', 'ik_ip', 'branch_id', 'branch', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ik_ip}}');
    }
}
