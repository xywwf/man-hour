<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project".
 *
 * @property string $id
 * @property integer $state
 * @property string $name
 * @property string $start_time
 * @property string $target_time
 * @property string $end_time
 * @property string $color
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
/*    
    private static $_id_name_map; // id<->name map of all project models
    
    public static function getIdNameMap()
    {
        if( !isset(self::$_id_name_map) ){
            self::$_id_name_map[] = Yii::t('app','Please choose...');
            
            $all = Project::find()->select(['id','name'])->andWhere(['state' => self::STATE_NORMAL])->orderBy('id')->asArray()->all();
            foreach ( $all as $pro ){
                self::$_id_name_map[$pro['id']] = $pro['name'];
            }
        }
        return self::$_id_name_map;
    }
*/
    public static function getIdNameMap($condition=null)
    {        
        $all = Project::find()->select(['id','name'])->andWhere(isset($condition)? $condition : '1=1')->orderBy('id')->asArray()->all();
        return ArrayHelper::map($all, 'id', 'name');
    }
    
    public static function getAllParentProjects($condition=null)
    {
        $query = Project::find()->select(['id','name'])->andWhere(['parent_id' => null]);
        if (isset($condition))
        {
            $query->andWhere($condition);
        }

        $all = $query->orderBy('name')->asArray()->all();
        //var_dump($all);
        //Yii::$app->end();

        $result = [];
        foreach ( $all as $pro ){
            $result[$pro['id']] = $pro['name'];
        }
        
        return $result;
        //return ArrayHelper::map($query->orderBy('name')->asArray()->all(), 'id', 'name');
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
            [['state', 'name', 'start_date'], 'required'],
            [['state', 'parent_id'], 'integer'],
            [['start_date', 'target_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['color'], 'string', 'max' => 7],
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
            'parent_id' => Yii::t('app', 'Parent Project ID'),
            'state' => Yii::t('app', 'State'),
            'name' => Yii::t('app', 'Project name'),
            'start_date' => Yii::t('app', 'Start date'),
            'target_date' => Yii::t('app', 'Target date'),
            'end_date' => Yii::t('app', 'Finish date'),
            'color' => Yii::t('app', 'Color'),
            'description' => Yii::t('app', 'Project description'),
        ];
    }

    public function __get($name)
    {
        $value = parent::__get($name);
        if( $name === 'color' ){
            if (!isset($value)){
                //mt_srand(time());
                $value = sprintf("#%06x", mt_rand(0, pow(2,24)-1));
            }
        }
        return $value;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['project_id' => 'id']);
    }
}
