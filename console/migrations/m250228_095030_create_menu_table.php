<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu}}`.
 */
class m250228_095030_create_menu_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'menu';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('menu');
        }

        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string('255')->null(),
            'action_id' => $this->integer()->null(),
            'icon' => $this->string('255')->null(),
            'parent_id' => $this->integer()->null(),
            'order' => $this->integer()->defaultValue(1),
            'status' => $this->integer()->defaultValue(1),
        ], $tableOptions);
        $this->addForeignKey('ik_menu_table_actions_table', 'menu', 'action_id', 'actions', 'id');
        $this->addForeignKey('ik_menu_table_parent_table', 'menu', 'parent_id', 'menu', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu}}');
    }
}
