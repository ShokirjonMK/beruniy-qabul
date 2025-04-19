<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lang}}`.
 */
class m240524_050734_create_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lang}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255)->notNull(),
            'name_en' => $this->string(255)->notNull(),
            'name_ru' => $this->string(255)->notNull(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ]);
        $this->insert('{{%lang}}', [
            'name_uz' => 'O`zbek',
            'name_ru' => 'Узбекский',
            'name_en' => 'Uzbek',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%lang}}', [
            'name_uz' => 'Inglizcha',
            'name_ru' => 'Английский',
            'name_en' => 'English',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%lang}}', [
            'name_uz' => 'Ruscha',
            'name_ru' => 'Русский',
            'name_en' => 'Russian',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%lang}}');
    }
}
