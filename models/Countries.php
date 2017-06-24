<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Countries".
 *
 * @property integer $id
 * @property string $country_title
 * @property string $code
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'country_title'], 'string', 'max' => 255],
            [[ 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_title' => 'Country Title',
            'code' => 'Code',
        ];
    }
}
