<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MhUser]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    public function normal()
    {
        $this->andWhere(['type' => User::TYPE_NORMAL]);
        return $this;        
    }
    
    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}