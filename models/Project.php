<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property string $id
 * @property integer $state
 * @property string $name
 * @property string $start_time
 * @property string $target_time
 * @property string $end_time
 * @property string $description
 *
 * @property Entry[] $entries
 */
class Project extends \yii\db\ActiveRecord
{
    public static $state_map = [
        '0' => '正常',
        '1' => '已完成',
        '2' => '关闭',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state', 'name', 'start_time'], 'required'],
            [['state'], 'integer'],
            [['start_time', 'target_time', 'end_time'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 400],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '项目号',
            'state' => '状态',
            'name' => '项目名称',
            'start_time' => '启动时间',
            'target_time' => '预计完成时间',
            'end_time' => '完成时间',
            'description' => '项目描述',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['project_id' => 'id']);
    }
}
