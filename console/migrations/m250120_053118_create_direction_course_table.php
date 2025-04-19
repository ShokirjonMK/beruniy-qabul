<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%direction_course}}`.
 */
class m250120_053118_create_direction_course_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'direction_course';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('direction_course');
        }

        $this->createTable('{{%direction_course}}', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->null(),
            'edu_direction_id' => $this->integer()->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_direction_course_table_edu_direction_table', 'direction_course', 'edu_direction_id', 'edu_direction', 'id');
        $this->addForeignKey('ik_direction_course_table_course_table', 'direction_course', 'course_id', 'course', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%direction_course}}');
    }
}
