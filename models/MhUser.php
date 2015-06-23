<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property integer $type
 * @property integer $state
 * @property string $username
 * @property string $mobile
 * @property string $email
 * @property string $password
 * @property string $create_time
 * @property integer $ext
 * @property string $ext2
 */
class MhUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'state', 'ext'], 'integer'],
            [['username', 'password'], 'required'],
            [['create_time'], 'safe'],
            [['username', 'mobile'], 'string', 'max' => 24],
            [['email'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 160],
            [['ext2'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'type' => '类型',
            'state' => '状态',
            'username' => '用户名',
            'mobile' => '手机号',
            'email' => '邮箱帐号',
            'password' => '密码',
            'create_time' => '创建时间',
            'ext' => '扩展',
            'ext2' => '扩展2',
        ];
    }
}
