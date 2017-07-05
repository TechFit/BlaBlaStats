<?php

namespace app\models;

use yii\base\Model;

class GenerateTripsForm extends Model
{
    /** @var $fromCity string */
    public $fromCity;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['fromCity', 'required'],
            ['fromCity', 'string'],
        ];
    }
}