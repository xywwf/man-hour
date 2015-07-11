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
    const STATE_NORMAL   = 0;
    const STATE_FINISHED = 1;
    const STATE_CLOSED   = 2;
    
    public static $state_map = [
        '0' => '正常',
        '1' => '已完成',
        '2' => '关闭',
    ];
    
    private static $_id_name_map; // id<->name map of all project models
    
    public static function getIdNameMap()
    {
        if( !isset(self::$_id_name_map) ){
            
            $all = Project::find()->select(['id','name'])->andWhere(['state' => self::STATE_NORMAL])->orderBy('id')->asArray()->all();
            if( count($all) )
            {
                self::$_id_name_map[''] = Yii::t('app','Please choose...');
            }
            
            foreach ( $all as $pro ){
                self::$_id_name_map[$pro['id']] = $pro['name'];
            }
        }
        return self::$_id_name_map;
    }
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_project_info';
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
            'id' => Yii::t('app', 'Project ID'),
            'state' => Yii::t('app', 'State'),
            'name' => Yii::t('app', 'Project name'),
            'start_time' => Yii::t('app', 'Start time'),
            'target_time' => Yii::t('app', 'Target time'),
            'end_time' => Yii::t('app', 'Finish time'),
            'description' => Yii::t('app', 'Project description'),
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
