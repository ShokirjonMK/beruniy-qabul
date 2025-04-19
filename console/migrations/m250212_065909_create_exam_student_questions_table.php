<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%exam_student_questions}}`.
 */
class m250212_065909_create_exam_student_questions_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'exam_student_questions';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('exam_student_questions');
        }

        $this->createTable('{{%exam_student_questions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'exam_id' => $this->integer()->notNull(),
            'exam_subject_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'option_id' => $this->integer()->null(),
            'is_correct' => $this->tinyInteger(1)->defaultValue(0),

            'option' => $this->string(255)->notNull(),
            'subject_id' => $this->integer()->null(),

            'order' => $this->integer()->defaultValue(1),
            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_exam_student_questions_table_user_table', 'exam_student_questions', 'user_id', 'user', 'id');
        $this->addForeignKey('ik_exam_student_questions_table_exam_table', 'exam_student_questions', 'exam_id', 'exam', 'id');
        $this->addForeignKey('ik_exam_student_questions_table_exam_subject_table', 'exam_student_questions', 'exam_subject_id', 'exam_subject', 'id');
        $this->addForeignKey('ik_exam_student_questions_table_question_table', 'exam_student_questions', 'question_id', 'questions', 'id');
        $this->addForeignKey('ik_exam_student_questions_table_option_table', 'exam_student_questions', 'option_id', 'options', 'id');
        $this->addForeignKey('ik_exam_student_questions_table_subject_table', 'exam_student_questions', 'subject_id', 'subjects', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%exam_student_questions}}');
    }
}
