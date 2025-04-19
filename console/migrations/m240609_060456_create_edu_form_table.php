<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%edu_form}}`.
 */
class m240609_060456_create_edu_form_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'edu_form';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('edu_form');
        }

        $this->createTable('{{%edu_form}}', [
            'id' => $this->primaryKey(),

            'name_uz' => $this->string(255)->notNull(),
            'name_ru' => $this->string(255)->notNull(),
            'name_en' => $this->string(255)->notNull(),

            'status' => $this->tinyInteger(1)->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);

        $this->insert('{{%edu_form}}', [
            'name_uz' => 'Kunduzgi',
            'name_ru' => 'Очное',
            'name_en' => 'Daytime',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%edu_form}}', [
            'name_uz' => 'Sirtqi',
            'name_ru' => 'Заочная',
            'name_en' => 'Part-time',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%edu_form}}', [
            'name_uz' => 'Kechki',
            'name_ru' => 'Вечерняя',
            'name_en' => 'Evening',
            'status' => 0,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%edu_form}}');
    }
}
