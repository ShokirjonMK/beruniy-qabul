<?php

use yii\db\Migration;

/**
 * Class m250107_083222_add_indexes_to_employee_and_related_table
 */
class m250107_083222_add_indexes_to_employee_and_related_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_employee_user_id', '{{%employee}}', 'user_id');
        $this->createIndex('idx_employee_status', '{{%employee}}', 'status');

        // User jadvalidagi indekslar
        $this->createIndex('idx_user_cons_id', '{{%user}}', 'cons_id');
        $this->createIndex('idx_user_user_role', '{{%user}}', 'user_role');

        // AuthItemChild jadvalidagi indekslar
        $this->createIndex('idx_auth_item_child_parent', '{{%auth_item_child}}', 'parent');
        $this->createIndex('idx_auth_item_child_child', '{{%auth_item_child}}', 'child');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250107_083222_add_indexes_to_employee_and_related_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250107_083222_add_indexes_to_employee_and_related_table cannot be reverted.\n";

        return false;
    }
    */
}
