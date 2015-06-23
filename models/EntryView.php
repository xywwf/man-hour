<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_entry".
 *
 * @property string $id
 * @property string $user_id
 * @property string $username
 * @property string $project_id
 * @property string $project_name
 * @property string $start_time
 * @property string $end_time
 * @property string $description
 * @property string $update_time
 * @property string $update_user_id
 * @property string $update_username
 */
class EntryView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'project_id', 'update_user_id'], 'integer'],
            [['user_id', 'project_id', 'start_time', 'end_time', 'update_user_id'], 'required'],
            [['start_time', 'end_time', 'update_time'], 'safe'],
            [['username', 'update_username'], 'string', 'max' => 24],
            [['project_name'], 'string', 'max' => 45],
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
            'username' => '用户名',
            'project_id' => '项目ID',
            'project_name' => '项目名称',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'description' => '工作内容描述',
            'update_time' => '最后更新时间',
            'update_user_id' => '最后更新人员ID',
            'update_username' => '最后更新人员',
        ];
    }
}
