<?php

use yii\db\Migration;

class m170624_165735_addCodes extends Migration
{
    public function safeUp()
    {
        $this->addColumn('Countries', 'code', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('Countries', 'code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170624_165735_addCodes cannot be reverted.\n";

        return false;
    }
    */
}
