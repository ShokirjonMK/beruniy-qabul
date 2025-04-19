<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%student}}`.
 */
class m250128_051528_add_student_column_to_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('student' , 'edu_type_id', $this->integer()->null());
        $this->addColumn('student' , 'edu_form_id', $this->integer()->null());
        $this->addColumn('student' , 'direction_id', $this->integer()->null());
        $this->addColumn('student' , 'edu_direction_id', $this->integer()->null());
        $this->addColumn('student' , 'lang_id', $this->integer()->null());
        $this->addColumn('student' , 'direction_course_id', $this->integer()->null());
        $this->addColumn('student' , 'course_id', $this->integer()->null());
        $this->addColumn('student' , 'exam_type', $this->tinyInteger(1)->defaultValue(0));
        $this->addColumn('student' , 'edu_name', $this->string(255)->null());
        $this->addColumn('student' , 'edu_direction', $this->string(255)->null());

        $this->addForeignKey('ik_student_table_edu_type_table', 'student', 'edu_type_id', 'edu_type', 'id');
        $this->addForeignKey('ik_student_table_edu_form_table', 'student', 'edu_form_id', 'edu_form', 'id');
        $this->addForeignKey('ik_student_table_direction_table', 'student', 'direction_id', 'direction', 'id');
        $this->addForeignKey('ik_student_table_edu_direction_table', 'student', 'edu_direction_id', 'edu_direction', 'id');
        $this->addForeignKey('ik_student_table_lang_table', 'student', 'lang_id', 'lang', 'id');
        $this->addForeignKey('ik_student_table_direction_course_table', 'student', 'direction_course_id', 'direction_course', 'id');
        $this->addForeignKey('ik_student_table_course_table', 'student', 'course_id', 'course', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
