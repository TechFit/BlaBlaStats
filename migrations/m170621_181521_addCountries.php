<?php

use yii\db\Migration;

class m170621_181521_addCountries extends Migration
{
    public function safeUp()
    {
        $this->createTable('Countries', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(),
            'country_code' => $this->string(),
            'country_title' => $this->string()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('Countries');
    }
}
