<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mh_transcard".
 *
 * @property integer $AutoID
 * @property integer $PersonnelID
 * @property string $EVTime
 */
class Transcard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_transcard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AutoID'], 'required'],
            [['AutoID', 'PersonnelID'], 'integer'],
            [['EVTime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AutoID' => Yii::t('app', 'Auto ID'),
            'PersonnelID' => Yii::t('app', 'Personnel ID'),
            'EVTime' => Yii::t('app', 'Evtime'),
        ];
    }
}
