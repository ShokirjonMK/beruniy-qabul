<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%options}}`.
 */
class m240524_050916_create_options_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'options';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('options');
        }

        $this->createTable('{{%options}}', [
            'id' => $this->primaryKey(),

            'question_id' => $this->integer()->notNull(),
            'subject_id' => $this->integer()->notNull(),

            'text' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext'),
            'image' => $this->string(255)->null(),
            'is_correct' => $this->tinyInteger()->defaultValue(0)->notNull(),

            'status' => $this->tinyInteger(1)->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_options_table_questions_table', 'options', 'question_id', 'questions', 'id');
        $this->addForeignKey('ik_options_table_subjects_table', 'options', 'subject_id', 'subjects', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%options}}');
    }
}
