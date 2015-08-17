<?php

namespace app\models;

/**
 * This is the model class for table "mh_settings".
 *
 * @property string $lable
 * @property string $name
 * @property string $value
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_settings';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','lable'], 'required'],
            [['name'], 'string', 'max' => 24],
            [['lable', 'value'], 'string', 'max' => 64],
            [['name'], 'unique']
        ];
    }
}
