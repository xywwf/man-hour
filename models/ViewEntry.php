<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mh_vw_entry".
 *
 * @property string $id
 * @property string $user_id
 * @property string $personal_name
 * @property string $project_id
 * @property string $project_name
 * @property string $start_date
 * @property string $start_time
 * @property string $end_time
 * @property string $duration
 * @property string $description
 * @property string $update_time
 * @property string $update_user_id
 * @property string $update_user_name
 * @property integer $type
 * @property integer $state
 * @property integer $ext
 * @property string $ext2
 */
class ViewEntry extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_vw_entry';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'project_id', 'update_user_id','type', 'state','ext'], 'integer'],
            [['user_id', 'project_id', 'start_date', 'duration', 'update_user_id'], 'required'],
            [['start_date', 'start_time', 'start_time', 'duration', 'update_time'], 'safe'],
            [['personal_name', 'update_user_name'], 'string', 'max' => 24],
            [['project_name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 500],
            [['ext2'], 'string', 'max' => 128]
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
        ];
    }

    /**
     * @inheritdoc
     * @return ViewEntryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ViewEntryQuery(get_called_class());
    }
}
