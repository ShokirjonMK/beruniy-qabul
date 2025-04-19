<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subjects}}`.
 */
class m240524_050816_create_subjects_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'subjects';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('subjects');
        }

        $this->createTable('{{%subjects}}', [
            'id' => $this->primaryKey(),

            'language_id' => $this->integer()->notNull(),

            'name_uz' => $this->string(255)->notNull(),
            'name_en' => $this->string(255)->notNull(),
            'name_ru' => $this->string(255)->notNull(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_subjects_table_lang_table', 'subjects', 'language_id', 'lang', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subjects}}');
    }
}
