<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Trips".
 *
 * @property integer $id
 * @property string $fn
 * @property string $tn
 * @property integer $fn_country_id
 * @property integer $tn_country_id
 * @property integer $min_price
 * @property integer $average_price
 * @property integer $max_price
 */
class Trips extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Trips';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fn_country_id', 'tn_country_id', 'min_price', 'average_price', 'max_price'], 'integer'],
            [['fn', 'tn'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fn' => 'Fn',
            'tn' => 'Tn',
            'fn_country_id' => 'Fn Country ID',
            'tn_country_id' => 'Tn Country ID',
            'min_price' => 'Min Price',
            'average_price' => 'Average Price',
            'max_price' => 'Max Price',
        ];
    }
}
