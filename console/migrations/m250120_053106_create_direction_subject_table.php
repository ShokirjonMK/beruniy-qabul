<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%direction_subject}}`.
 */
class m250120_053106_create_direction_subject_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'direction_subject';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('direction_subject');
        }

        $this->createTable('{{%direction_subject}}', [
            'id' => $this->primaryKey(),
            'subject_id' => $this->integer()->null(),
            'edu_direction_id' => $this->integer()->null(),
            'ball' => $this->float()->null(),
            'count' => $this->integer()->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_direction_subject_table_subjects_table', 'direction_subject', 'subject_id', 'subjects', 'id');
        $this->addForeignKey('ik_direction_subject_table_edu_direction_table', 'direction_subject', 'edu_direction_id', 'edu_direction', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%direction_subject}}');
    }
}
