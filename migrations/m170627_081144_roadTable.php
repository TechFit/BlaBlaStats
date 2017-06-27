<?php

use yii\db\Migration;

class m170627_081144_roadTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('Trips', [
            'id' => $this->primaryKey(),
            'fn' => $this->string(),
            'tn' => $this->string(),
            'fn_country_id' => $this->integer(),
            'tn_country_id' => $this->integer(),
            'min_price' => $this->integer(),
            'average_price' => $this->integer(),
            'max_price' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('Trips');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170627_081144_roadTable cannot be reverted.\n";

        return false;
    }
    */
}
