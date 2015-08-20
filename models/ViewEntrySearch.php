<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ViewEntry;

/**
 * ViewEntrySearch represents the model behind the search form about `app\models\ViewEntry`.
 */
class ViewEntrySearch extends ViewEntry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'project_id', 'update_user_id', 'state'], 'integer'],
            [['personal_name', 'project_name', 'start_date', 'start_time', 'description', 'update_time', 'update_user_name'], 'safe'],
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
        $query = ViewEntry::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('start_date DESC, start_time ASC'),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'state' => $this->state,
            'start_date' => $this->start_date,
            //'start_time' => $this->start_time,
            //'update_time' => $this->update_time,
            //'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'personal_name', $this->personal_name])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'update_user_name', $this->update_user_name]);

        return $dataProvider;
    }
}
