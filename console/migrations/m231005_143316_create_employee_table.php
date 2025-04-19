<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 */
class m231005_143316_create_employee_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'employee';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('employee');
        }

        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),

            'first_name' => $this->string(255)->null(),
            'last_name' => $this->string(255)->null(),
            'middle_name' => $this->string(255)->null(),
            'phone' => $this->string(255)->null(),

            'gender' => $this->tinyInteger(1)->null(),
            'brithday' => $this->string(50)->null(),
            'image' => $this->string(255)->null(),
            'adress' => $this->text()->null(),
            'password' => $this->string(255)->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey('ik_employee_table_user_table', 'employee', 'user_id', 'user', 'id');

        $this->insert('{{%employee}}', [
            'user_id' => 1,
            'first_name' => 'IKbol',
            'last_name' => 'Uraimov',
            'middle_name' => 'Anvarjon o\'g\'li',
            'phone' => '+998 (94) 505-52-50',
            'gender' => 1,
            'brithday' => date("16-10-2001"),
            'adress' => 'Namangan viloyati Norin tumani',
            'password' => 'ik10002',
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
        $this->dropTable('{{%employee}}');
    }
}
