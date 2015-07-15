<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mh_entry".
 *
 * @property string $id
 * @property string $user_id
 * @property string $project_id
 * @property string $start_date
 * @property string $start_time
 * @property string $duration
 * @property string $description
 * @property string $update_time
 * @property string $update_user_id
 * @property integer $type
 * @property integer $state
 * @property integer $ext
 * @property string $ext2
 *
 * @property MhProjectInfo $project
 * @property MhUserInfo $updateUser
 * @property MhUserInfo $user
 */
class Entry extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id', 'start_date', 'start_time', 'duration', 'update_user_id'], 'required'],
            [['user_id', 'project_id', 'update_user_id', 'duration', 'type', 'state', 'ext'], 'integer'],
            [['start_date', 'start_time', 'update_time'], 'safe'],
            [['description'], 'string', 'max' => 500],
            [['ext2'], 'string', 'max' => 128],
            [['duration'], 'integer', 'min' => 60, 'max' => 86400 ],
            [['end_time'], 'compare', 'operator' => '>', 'compareAttribute' => 'start_time' ],
            //[['start_time'], 'compare', 'operator' => '<', 'compareAttribute' => 'end_time' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Manhour log ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'personal_name' => Yii::t('app', 'Personal name'),
            'project_id' => Yii::t('app', 'Project ID'),
            'project_name' => Yii::t('app', 'Project name'),
            'start_date' => Yii::t('app', 'Date'),
            'start_time' => Yii::t('app', 'Start'),
            'end_time' => Yii::t('app', 'End'),
            'duration' => Yii::t('app', 'Duration'),
            'description' => Yii::t('app', 'Working description'),
            'update_time' => Yii::t('app', 'Last updated time'),
            'update_user_id' => Yii::t('app', 'Last updated by'),
            'update_user_name' => Yii::t('app', 'Last updated by user name'),
            'type' => Yii::t('app', 'Type'),
            'state' => Yii::t('app', 'State'),
            'ext' => Yii::t('app', 'Ext'),
            'ext2' => Yii::t('app', 'Ext2'),
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
        return $this->hasOne(User::className(), ['uid' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['uid' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return EntryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EntryQuery(get_called_class());
    }
    
    public function beforeSave($insert)
    {
        $this->end_time = strftime("%X", ( strtotime($this->start_time) + $this->duration ));
        return parent::beforeSave($insert);
    }
}
