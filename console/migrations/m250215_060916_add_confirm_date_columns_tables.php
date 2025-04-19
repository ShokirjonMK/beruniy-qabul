<?php

use yii\db\Migration;

/**
 * Class m250215_060916_add_confirm_date_columns_tables
 */
class m250215_060916_add_confirm_date_columns_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('exam', 'confirm_date' , $this->integer()->null());
        $this->addColumn('student_perevot', 'confirm_date' , $this->integer()->null());
        $this->addColumn('student_dtm', 'confirm_date' , $this->integer()->null());
        $this->addColumn('student_master', 'confirm_date' , $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250215_060916_add_confirm_date_columns_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250215_060916_add_confirm_date_columns_tables cannot be reverted.\n";

        return false;
    }
    */
}
