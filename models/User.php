<?php

namespace app\models;

use Yii;
use yii\helpers\BaseHtml;

/**
 * This is the model class for table "user".
 *
 * @property string $uid
 * @property integer $type
 * @property string $username
 * @property string $personal_name
 * @property string $personal_id
 * @property string $employe_id
 * @property integer $department_id
 * @property integer $company_id
 * @property integer $price
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
    const TYPE_INIT         = 0;
    const TYPE_SUPERADMIN   = 1;
    const TYPE_ADMIN        = 2;
    const TYPE_NORMAL       = 3;
    const TYPE_CLOSED       = 4;
    
    private static $_type_map = [];

    public static function getTypeMap($type = null, $end = null)
    {        
        if( count( self::$_type_map ) <= 0 )
        {  
            self::$_type_map = [
                self::TYPE_INIT       => 'Uninitialized',
                self::TYPE_SUPERADMIN => 'Super admin',
                self::TYPE_ADMIN      => 'Administrator',
                self::TYPE_NORMAL     => 'Normal user',
                self::TYPE_CLOSED     => 'Closed user',
            ];
            array_walk( self::$_type_map, "\app\G::array_t" );
        }
        
        $type = ($type === null || $type < self::TYPE_INIT || $type > self::TYPE_NORMAL) ?  -1 : $type;
        $end  = ($end === null || ($end <= $type) || $end >= count(self::$_type_map)) ? count(self::$_type_map) : $end;
        
        $result = [];
        foreach( self::$_type_map as $key=>$value){
            if ($key > $type &&  $key <= $end) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_user_info';
    }

    public function isAdmin()
    {
        return $this->type == self::TYPE_SUPERADMIN || $this->type == self::TYPE_ADMIN;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_by', 'last_updated_by', 'ext', 'department_id', 'company_id', 'experience'], 'integer'],
            [['price'], 'number'],
            [['username', 'personal_name', 'type'], 'required'],
            [['employe_id','department_id', 'company_id'], 'required', 
                'when' => function($model){ return $model->type == self::TYPE_NORMAL; }, 
                'whenClient' => "function (attribute, value) { return $('#".BaseHtml::getInputId($this, 'type')."').val() == ". self::TYPE_NORMAL .";}"
            ],
            [['created_time', 'last_updated_time', 'password'], 'safe'],
            [['username', 'personal_name', 'personal_id', 'employe_id', 'mobile'], 'string', 'max' => 24],
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
            'uid' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'User type'),
            'username' => Yii::t('app', 'Username'),
            'personal_name' => Yii::t('app', 'Personal name'),
            'personal_id' => Yii::t('app', 'Identity card'),
            'employe_id' => Yii::t('app', 'Employe ID(To sync attendence DB)'),
            'department_id' => Yii::t('app', 'Geely department'),
            'company_id' => Yii::t('app', 'Supplier name'),
            'experience' => Yii::t('app', 'Experience(Years)'),
            'price' => Yii::t('app', 'Price/Hour'),
            'mobile' => Yii::t('app', 'Mobile'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'created_time' => Yii::t('app', 'Created time'),
            'created_by' => Yii::t('app', 'Created by'),
            'last_updated_time' => Yii::t('app', 'Last updated time'),
            'last_updated_by' => Yii::t('app', 'Last updated by'),
            'ext' => Yii::t('app', 'ext'),
            'ext2' => Yii::t('app', 'ext2'),
        ];
    }
    
    public function beforeValidate(){
        if (isset($this->price)) {
            $this->price = intval($this->price * 100) / 100;
        }
        return parent::beforeValidate();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['user_id' => 'uid']);
    }
    
    public function getEntriesCount()
    {
        return $this->hasMany(Entry::className(), ['user_id' => 'uid'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuth()
    {
        return $this->hasOne(UserAuth::className(), ['uid' => 'uid']);
    }
    
    public function saveUserAndAuth()
    {
        $userAuth = $this->userAuth;
        if ($userAuth === null) {
            $userAuth           = new UserAuth();
            
            $password = Yii::$app->params['defaultPassword'];
            $password = \app\G::getSettingByName('InitPass', $password);
            if( isset($this->password) && strlen($this->password) )
            {
                $password = $this->password;
            }
            $userAuth->password = Yii::$app->security->generatePasswordHash($password);
            
        } elseif (isset($this->password) && strlen($this->password)) {
            //update password
            $userAuth->password = Yii::$app->security->generatePasswordHash($this->password);
        }
        
        $thisModel = $this;
        $thisModel->password = '';
        return $this->getDb()->transaction( function() use (&$thisModel, &$userAuth) {
            if ( $thisModel->save() ) {
                $userAuth->uid = $thisModel->uid;
                if ($userAuth->save()) {
                    return true;
                }
            }
            return false;
            //throw new \Exception('Add user to DB failed!');
        });
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
