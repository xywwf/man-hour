<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type', 'ext', 'company_id', 'department_id'], 'integer'],
            [['username', 'personal_name', 'employe_id', 'mobile', 'email', 'password', 'created_time', 'ext2'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();
        $user = Yii::$app->user->identity;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query->andWhere(['>', 'type', $user->type]),//->orderBy('department_id, company_id'),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'uid' => $this->uid,
            'type' => $this->type,
            'created_time' => $this->created_time,
            'ext' => $this->ext,
            'employe_id' => $this->employe_id,
        ]);

        $query->andFilterWhere(['in', 'company_id', $this->company_id])
        ->andFilterWhere(['in', 'department_id', $this->department_id]);
        
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'personal_name', $this->personal_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'ext2', $this->ext2]);

        return $dataProvider;
    }
}
