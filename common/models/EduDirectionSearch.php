<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EduDirection;

/**
 * EduDirectionSearch represents the model behind the search form of `common\models\EduDirection`.
 */
class EduDirectionSearch extends EduDirection
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'branch_id','direction_id', 'lang_id', 'edu_type_id', 'edu_form_id', 'is_oferta', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['duration', 'price'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = EduDirection::find()
            ->where(['is_deleted' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'direction_id' => $this->direction_id,
            'lang_id' => $this->lang_id,
            'edu_type_id' => $this->edu_type_id,
            'edu_form_id' => $this->edu_form_id,
            'is_oferta' => $this->is_oferta,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'price', $this->price]);

        return $dataProvider;
    }
}
