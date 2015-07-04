<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mh_user_auth".
 * 
 * Note!!! 
 * The hash key of default passowrd(admin) is: $2y$13$dlMHb64r8cYTzX/ZVTeALeg3B8N2Qcpt55ISb6iJAHIScLJsSjaNe
 * If you don't remember the password of supper admin, copy this into DB.
 *
 * @property string $uid
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 *
 * @property MhUserInfo $u
 */
class UserAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_user_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'password'], 'required'],
            [['uid'], 'integer'],
            [['password'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 64],
            [['access_token'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['uid' => 'uid']);
    }
    
    public function beforeSave($insert)
    {
        if ($this->isNewRecord && !isset( $this->auth_key )) {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }
        return parent::beforeSave($insert);
    }
}
