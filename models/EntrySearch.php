<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Entry;

/**
 * EntrySearch represents the model behind the search form about `\app\models\Entry`.
 */
class EntrySearch extends Entry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'project_id', 'update_user_id', 'type', 'state', 'ext'], 'integer'],
            [['start_date', 'start_time', 'end_date', 'end_time', 'description', 'update_time', 'ext2'], 'safe'],
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
        $query = Entry::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
            'update_time' => $this->update_time,
            'update_user_id' => $this->update_user_id,
            'type' => $this->type,
            'state' => $this->state,
            //'ext' => $this->ext,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);
            //->andFilterWhere(['like', 'ext2', $this->ext2]);

        return $dataProvider;
    }
}
