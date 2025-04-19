<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%edu_type}}`.
 */
class m240609_060506_create_edu_type_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'edu_type';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('edu_type');
        }

        $this->createTable('{{%edu_type}}', [
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

        $this->insert('{{%edu_type}}', [
            'name_uz' => 'Qabul',
            'name_ru' => 'Принятие',
            'name_en' => 'Acceptance',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%edu_type}}', [
            'name_uz' => 'O\'qishni ko\'chirish',
            'name_ru' => 'Трансферное исследование',
            'name_en' => 'Transfer study',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%edu_type}}', [
            'name_uz' => 'UZBMB natija',
            'name_ru' => 'Результат УЗБМБ',
            'name_en' => 'UZBMB result',
            'status' => 0,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%edu_type}}', [
            'name_uz' => 'Magistratura',
            'name_ru' => 'Магистратура',
            'name_en' => 'Master\'s',
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
        $this->dropTable('{{%edu_type}}');
    }
}
