<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Cities".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $city_title
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['city_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'city_title' => 'City Title',
        ];
    }
}
