<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%branch}}`.
 */
class m250110_073151_create_branch_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'branch';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('branch');
        }

        $this->createTable('{{%branch}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string()->null(),
            'name_ru' => $this->string()->null(),
            'name_en' => $this->string()->null(),

            'telegram' => $this->string()->null(),
            'instagram' => $this->string()->null(),
            'facebook' => $this->string()->null(),

            'address_uz' => $this->string()->null(),
            'address_ru' => $this->string()->null(),
            'address_en' => $this->string()->null(),

            'location' => $this->text()->null(),
            'tel1' => $this->string()->null(),
            'tel2' => $this->string()->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);

        $this->insert('{{%branch}}', [
            'name_uz' => 'UNIVERSITET',
            'name_ru' => 'UNIVERSITET',
            'name_en' => 'UNIVERSITET',

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
        $this->dropTable('{{%branch}}');
    }
}
