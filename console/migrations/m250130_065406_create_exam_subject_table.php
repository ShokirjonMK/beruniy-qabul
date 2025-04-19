<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%exam_subject}}`.
 */
class m250130_065406_create_exam_subject_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'exam_subject';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('exam_subject');
        }

        $this->createTable('{{%exam_subject}}', [
            'id' => $this->primaryKey(),
            'exam_id' => $this->integer()->null(),
            'user_id' => $this->integer()->null(),
            'student_id' => $this->integer()->null(),
            'edu_direction_id' => $this->integer()->null(),
            'direction_id' => $this->integer()->null(),
            'language_id' => $this->integer()->null(),
            'edu_type_id' => $this->integer()->null(),
            'edu_form_id' => $this->integer()->null(),
            'direction_subject_id' => $this->integer()->null(),
            'subject_id' => $this->integer()->null(),
            'ball' => $this->float()->null(),

            'file' => $this->string(255)->null(),
            'file_status' => $this->tinyInteger(1)->defaultValue(0),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_exam_subject_table_exam_table', 'exam_subject', 'exam_id', 'exam', 'id');
        $this->addForeignKey('ik_exam_subject_table_user_table', 'exam_subject', 'user_id', 'user', 'id');
        $this->addForeignKey('ik_exam_subject_table_student_table', 'exam_subject', 'student_id', 'student', 'id');
        $this->addForeignKey('ik_exam_subject_table_edu_direction_table', 'exam_subject', 'edu_direction_id', 'edu_direction', 'id');
        $this->addForeignKey('ik_exam_subject_table_direction_table', 'exam_subject', 'direction_id', 'direction', 'id');
        $this->addForeignKey('ik_exam_subject_table_direction_subject_table', 'exam_subject', 'direction_subject_id', 'direction_subject', 'id');
        $this->addForeignKey('ik_exam_subject_table_subject_table', 'exam_subject', 'subject_id', 'subjects', 'id');
        $this->addForeignKey('ik_exam_subject_table_language_table', 'exam_subject', 'language_id', 'lang', 'id');
        $this->addForeignKey('ik_exam_subject_table_edu_type_table', 'exam_subject', 'edu_type_id', 'edu_type', 'id');
        $this->addForeignKey('ik_exam_subject_table_edu_form_table', 'exam_subject', 'edu_form_id', 'edu_form', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%exam_subject}}');
    }
}
