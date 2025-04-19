<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%course}}`.
 */
class m240611_125625_create_course_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'course';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('course');
        }

        $this->createTable('{{%course}}', [
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
        ], $tableOptions);

        for ($i = 1; $i < 7; $i++) {
            $this->insert('{{%course}}', [
                'name_uz' => $i.' - bosqich',
                'name_ru' => $i.'-й этап',
                'name_en' => $i.'st stage',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%course}}');
    }
}
