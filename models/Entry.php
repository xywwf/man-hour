<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entry".
 *
 * @property string $id
 * @property string $user_id
 * @property string $project_id
 * @property string $start_time
 * @property string $end_time
 * @property string $description
 * @property string $update_time
 * @property string $update_user_id
 *
 * @property Project $project
 * @property User $updateUser
 * @property User $user
 */
class Entry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id', 'start_time', 'end_time', 'update_user_id'], 'required'],
            [['user_id', 'project_id', 'update_user_id'], 'integer'],
            [['start_time', 'end_time', 'update_time'], 'safe'],
            [['description'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'project_id' => '项目ID',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'description' => '工作内容描述',
            'update_time' => '最后更新时间',
            'update_user_id' => '最后更新人员',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return EntryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EntryQuery(get_called_class());
    }
}
