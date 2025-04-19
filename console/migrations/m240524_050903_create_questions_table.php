<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%questions}}`.
 */
class m240524_050903_create_questions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $tableName = Yii::$app->db->tablePrefix . 'questions';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('questions');
        }

        $this->createTable('{{%questions}}', [
            'id' => $this->primaryKey(),
            'subject_id' => $this->integer()->notNull(),

            'text' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext'),
            'image' => $this->string(255)->null(),
            'level' => $this->tinyInteger(1)->defaultValue(1),

            'type' => $this->tinyInteger(1)->defaultValue(0),


            'status' => $this->integer()->defaultValue(0),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_questions_table_subject_table', 'questions', 'subject_id', 'subjects', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%questions}}');
    }
}
