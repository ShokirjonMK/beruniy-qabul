<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%consulting}}`.
 */
class m250106_082610_create_consulting_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'consulting';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('consulting');
        }

        $this->createTable('{{%consulting}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string(255)->null(),
            'hr' => $this->string(255)->null(),

            'bank_name_uz' => $this->string(255)->null(),
            'bank_name_ru' => $this->string(255)->null(),
            'bank_name_en' => $this->string(255)->null(),

            'bank_adress_uz' => $this->string(255)->null(),
            'bank_adress_ru' => $this->string(255)->null(),
            'bank_adress_en' => $this->string(255)->null(),

            'mfo' => $this->string(255)->null(),
            'inn' => $this->string(255)->null(),
            'tel1' => $this->string(255)->null(),
            'tel2' => $this->string(255)->null(),

            'domen' => $this->string(255)->null(),

            'code' => $this->string(255)->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);

        $this->insert('{{%consulting}}', [
            'name' => 'UNIVERSITET',

            'hr' => '0000 000 0000 0000',

            'bank_name_uz' => 'ik',
            'bank_name_ru' => 'ik',
            'bank_name_en' => 'ik',

            'bank_adress_uz' => 'ik',
            'bank_adress_ru' => 'ik',
            'bank_adress_en' => 'ik',

            'mfo' => '12121',
            'inn' => '12121212',
            'tel1' => '+998 94 505 52 50',
            'tel2' => '',
            'domen' => '.uz',

            'code' => 'ik',

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
        $this->dropTable('{{%consulting}}');
    }
}
