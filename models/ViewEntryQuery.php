<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ViewEntry]].
 *
 * @see ViewEntry
 */
class ViewEntryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    public function statisticsByProjectAndDate($uid)
    {
        $this->andWhere(['user_id'=>$uid]);
        return $this;
    }
    
    /**
     * @inheritdoc
     * @return ViewEntry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ViewEntry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}