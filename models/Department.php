<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mh_department_info".
 *
 * @property integer $id
 * @property string $name
 */
class Department extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_department_info';
    }

    private static $_id_name_map;
    public static function getIdNameMap($condition=null)
    {
        if (!isset(self::$_id_name_map)){
            $all = Department::find()->select(['id','name'])->andWhere(isset($condition)? $condition : '1=1')->orderBy('id')->asArray()->all();
            self::$_id_name_map = ArrayHelper::map($all, 'id', 'name');           
        }
        return self::$_id_name_map;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Department ID'),
            'name' => Yii::t('app', 'Geely department name'),
        ];
    }
}
