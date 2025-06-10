<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_log}}`.
 */
class m250610_092952_create_student_log_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'student_log';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('student_log');
        }

        $this->createTable('{{%student_log}}', [
            'id' => $this->primaryKey(),
            // 'data' => $this->text()->null(),
            'data' => $this->json()->null(),
            'student_id' => $this->integer()->null(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex(
            'idx-student_log-student_id',
            'student_log',
            'student_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-student_log-student_id',
            'student_log'
        );
        $this->dropTable('{{%student_log}}');
    }
}
