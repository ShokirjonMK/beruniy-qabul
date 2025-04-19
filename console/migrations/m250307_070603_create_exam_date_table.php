<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%exam_date}}`.
 */
class m250307_070603_create_exam_date_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'exam_date';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('exam_date');
        }

        $this->createTable('{{%exam_date}}', [
            'id' => $this->primaryKey(),
            'date' => $this->string()->null(),

            'status' => $this->integer()->defaultValue(0),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);

        $this->addColumn('student' , 'exam_date_id' , $this->integer()->null());
        $this->addForeignKey('ik_student_table_exam_date_table', 'student', 'exam_date_id', 'exam_date', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%exam_date}}');
    }
}
