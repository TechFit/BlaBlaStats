<?php

use yii\db\Migration;

class m170621_182017_addCities extends Migration
{
    public function safeUp()
    {
        $this->createTable('Cities', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(),
            'city_title' => $this->string()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('Cities');
    }
}
