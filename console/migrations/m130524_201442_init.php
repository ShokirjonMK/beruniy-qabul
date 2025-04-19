<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/51278467/mysql-collation-utf8mb4-unicode-ci-vs-utf8mb4-default-collation
            // https://www.eversql.com/mysql-utf8-vs-utf8mb4-whats-the-difference-between-utf8-and-utf8mb4/
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $tableName = Yii::$app->db->tablePrefix . 'user';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('user');
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'step' => $this->integer()->defaultValue(0),
            'user_role' => $this->string(255)->notNull(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'access_token' => $this->string(100)->defaultValue(null),
            'verification_token' => $this->string()->defaultValue(null),
            'access_token_time' => $this->integer()->null(),

            'sms_number' => $this->integer()->defaultValue(0),
            'sms_time' => $this->integer()->defaultValue(0),
            'new_password' => $this->string(255)->null(),
            'new_key' => $this->string(255)->null(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert('{{%user}}', [
            'username' => 'ShokirjonMK',
            'auth_key' => \Yii::$app->security->generateRandomString(20),
            'password_hash' => \Yii::$app->security->generatePasswordHash("12300123"),
            'password_reset_token' => null,
            'access_token' => \Yii::$app->security->generateRandomString(),
            'access_token_time' => time(),
            'user_role' => 'super_admin',
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%user}}', [
            'username' => 'IKbol',
            'auth_key' => \Yii::$app->security->generateRandomString(20),
            'password_hash' => \Yii::$app->security->generatePasswordHash("ik10002"),
            'password_reset_token' => null,
            'access_token' => \Yii::$app->security->generateRandomString(),
            'access_token_time' => time(),
            'user_role' => 'super_admin',
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable('{{%user}}');
        $this->execute('SET FOREIGN_KEY_CHECKS=1');
    }
}
