<?php

use yii\db\Migration;

class m170622_123850_alterTables extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('Countries', 'country_id');
        $this->dropColumn('Countries', 'country_code');
        $this->alterColumn('Countries', 'country_title', $this->string()->append('CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL'));
    }

    public function safeDown()
    {
        $this->addColumn('Countries', 'country_id', $this->integer());
        $this->addColumn('Countries', 'country_code', $this->string());
    }
}
