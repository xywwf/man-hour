<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $uid
 * @property integer $type
 * @property string $username
 * @property string $personal_name
 * @property string $personal_id
 * @property string $employe_id
 * @property string $department_id
 * @property string $department_name
 * @property string $mobile
 * @property string $email
 * @property string $password
 * @property string $created_time
 * @property integer $created_by
 * @property string $last_updated_time
 * @property integer $last_updated_by
 * @property integer $ext
 * @property string $ext2
 *
 * @property Entry[] $entries
 * @property Entry[] $entries0
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    //public $password;
    
    public static $type_map = [
        '0' => '超级管理员',
        '1' => '管理员',
        '2' => '普通员工',
        '3' => '已禁用员工',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_user_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_by', 'last_updated_by', 'ext'], 'integer'],
            [['username', 'personal_name'], 'required'],
            [['created_time', 'last_updated_time', 'password'], 'safe'],
            [['username', 'personal_name', 'personal_id', 'employe_id', 'department_id', 'department_name', 'mobile'], 'string', 'max' => 24],
            [['email'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 160],
            [['ext2'], 'string', 'max' => 128],
            [['employe_id', 'username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => Yii::t('app', '用户ID'),
            'type' => Yii::t('app', '类型'),
            'username' => Yii::t('app', '用户名'),
            'personal_name' => Yii::t('app', '姓名'),
            'personal_id' => Yii::t('app', '身份证'),
            'employe_id' => Yii::t('app', '员工号'),
            'department_id' => Yii::t('app', '部门号'),
            'department_name' => Yii::t('app', '部门名称'),
            'mobile' => Yii::t('app', '手机号'),
            'email' => Yii::t('app', '邮箱帐号'),
            'password' => Yii::t('app', '密码'),
            'created_time' => Yii::t('app', '创建时间'),
            'created_by' => Yii::t('app', '创建者'),
            'last_updated_time' => Yii::t('app', '更新时间'),
            'last_updated_by' => Yii::t('app', '更新者'),
            'ext' => Yii::t('app', '扩展'),
            'ext2' => Yii::t('app', '扩展2'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuth()
    {
        return $this->hasOne(UserAuth::className(), ['uid' => 'uid']);
    }
    
    public function saveUserAuth()
    {     
        $userAuth = $this->userAuth;
        if ($userAuth === null) {

            $password = Yii::$app->params['defaultPassword'];
            if( isset($this->password) && strlen($this->password) )
            {
                $password = $this->password;               
            }
            
            $userAuth           = new UserAuth();
            $userAuth->uid      = $this->uid;            
            $userAuth->password = Yii::$app->security->generatePasswordHash($password);

            return $userAuth->save();
            
        } else if( isset($this->password) && strlen($this->password) ) {
            $userAuth->password = Yii::$app->security->generatePasswordHash($password);
            return $userAuth->save();
        }
        return true;
    }
    
    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {   
        return $this->userAuth->access_token;
    }
    
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username ]);
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->uid;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->userAuth->auth_key;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->userAuth->auth_key === $authKey;
        //return true;
    }
    
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->userAuth->password);
        //return true;
    }
    
}
