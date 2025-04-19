<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%actions}}`.
 */
class m250228_062655_create_actions_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'actions';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('actions');
        }

        $this->createTable('{{%actions}}', [
            'id' => $this->primaryKey(),
            'controller' => $this->string('255')->notNull(),
            'action' => $this->string('255')->notNull(),
            'description' => $this->string('255')->null(),
            'status' => $this->integer()->defaultValue(1),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%actions}}');
    }
}
