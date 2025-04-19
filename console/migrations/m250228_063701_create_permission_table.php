<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%permission}}`.
 */
class m250228_063701_create_permission_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'permission';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('permission');
        }

        $this->createTable('{{%permission}}', [
            'id' => $this->primaryKey(),
            'role_name' => $this->string(255)->notNull(),
            'action_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(1),
        ], $tableOptions);
        $this->addForeignKey('ik_permission_table_auth_item_table', 'permission', 'role_name', 'auth_item', 'name');
        $this->addForeignKey('ik_permission_table_actions_table', 'permission', 'action_id', 'actions', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%permission}}');
    }
}
