<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cunsulting_branch}}`.
 */
class m250113_072414_create_cunsulting_branch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/51278467/mysql-collation-utf8mb4-unicode-ci-vs-utf8mb4-default-collation
            // https://www.eversql.com/mysql-utf8-vs-utf8mb4-whats-the-difference-between-utf8-and-utf8mb4/
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $tableName = Yii::$app->db->tablePrefix . 'consulting_branch';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('consulting_branch');
        }

        $this->createTable('{{%consulting_branch}}', [
            'id' => $this->primaryKey(),

            'branch_id' => $this->integer()->null(),
            'consulting_id' => $this->integer()->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_consulting_branch_table_branch_table', 'consulting_branch', 'branch_id', 'branch', 'id');
        $this->addForeignKey('ik_consulting_branch_table_consulting_table', 'consulting_branch', 'consulting_id', 'consulting', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cunsulting_branch}}');
    }
}
