<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%edu_direction}}`.
 */
class m250118_062817_create_edu_direction_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'edu_direction';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('edu_direction');
        }

        $this->createTable('{{%edu_direction}}', [
            'id' => $this->primaryKey(),
            'direction_id' => $this->integer()->null(),
            'lang_id' => $this->integer()->null(),
            'duration' => $this->string(10)->null(),
            'price' => $this->string(255)->null(),
            'edu_type_id' => $this->integer()->null(),
            'edu_form_id' => $this->integer()->null(),
            'is_oferta' => $this->tinyInteger(1)->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_edu_direction_table_direction_table', 'edu_direction', 'direction_id', 'direction', 'id');
        $this->addForeignKey('ik_edu_direction_table_lang_table', 'edu_direction', 'lang_id', 'lang', 'id');
        $this->addForeignKey('ik_edu_direction_table_edu_type_table', 'edu_direction', 'edu_type_id', 'edu_type', 'id');
        $this->addForeignKey('ik_edu_direction_table_edu_form_table', 'edu_direction', 'edu_form_id', 'edu_form', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%edu_direction}}');
    }
}
