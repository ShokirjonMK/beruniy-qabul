<?php

use yii\db\Migration;

/**
 * Class m250210_063420_add_is_confirm_to_user_table
 */
class m250210_063420_add_is_confirm_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user' , 'is_confirm' , $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250210_063420_add_is_confirm_to_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250210_063420_add_is_confirm_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
