<?php

use yii\db\Migration;

/**
 * Class m250215_054542_add_contract_columns_tables
 */
class m250215_054542_add_contract_columns_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('exam', 'contract_price' , $this->float()->null());
        $this->addColumn('exam', 'invois' , $this->float()->null());
        $this->addColumn('exam', 'down_time' , $this->integer()->null());

        $this->addColumn('student_perevot', 'contract_price' , $this->float()->null());
        $this->addColumn('student_perevot', 'invois' , $this->float()->null());
        $this->addColumn('student_perevot', 'down_time' , $this->integer()->null());

        $this->addColumn('student_dtm', 'contract_price' , $this->float()->null());
        $this->addColumn('student_dtm', 'invois' , $this->float()->null());
        $this->addColumn('student_dtm', 'down_time' , $this->integer()->null());

        $this->addColumn('student_master', 'contract_price' , $this->float()->null());
        $this->addColumn('student_master', 'invois' , $this->float()->null());
        $this->addColumn('student_master', 'down_time' , $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250215_054542_add_contract_columns_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250215_054542_add_contract_columns_tables cannot be reverted.\n";

        return false;
    }
    */
}
