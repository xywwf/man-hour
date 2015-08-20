<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * PasswordForm is the model behind the password form.
 */
class PasswordForm extends Model
{
    public $id;
    public $passwordOld;
    public $passwordNew;
    public $passwordConfirm;

    private $_user = false;
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'passwordOld' => Yii::t('app', 'Old password'),
            'passwordNew' => Yii::t('app', 'New password'),
            'passwordConfirm' => Yii::t('app', 'Confirm password'),
        ];
    }
    
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['passwordOld', 'passwordNew', 'passwordConfirm'], 'required'],
            // password is validated by validatePassword()
            ['passwordOld', 'validatePassword'],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'passwordNew', 'message' => Yii::t('app', 'Is not same as New passowrd!')],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->passwordOld)) {
                $this->addError($attribute, Yii::t('app','Incorrect username or password!'));
            }
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $user = $this->getUser();           
            if ($user){
                $user->password = $this->passwordNew;
                return $user->saveUserAndAuth();
            }
        } 
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if (isset($this->id) && $this->_user === false) {
            $this->_user = User::findIdentity($this->id);
        }

        return $this->_user;
    }
}
