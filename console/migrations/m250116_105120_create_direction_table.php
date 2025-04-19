<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%direction}}`.
 */
class m250116_105120_create_direction_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'direction';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('direction');
        }

        $this->createTable('{{%direction}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255)->null(),
            'name_ru' => $this->string(255)->null(),
            'name_en' => $this->string(255)->null(),
            'code' => $this->string(255)->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%direction}}');
    }
}
