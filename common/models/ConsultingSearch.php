<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Consulting;

/**
 * ConsultingSearch represents the model behind the search form of `common\models\Consulting`.
 */
class ConsultingSearch extends Consulting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['name', 'hr', 'bank_name_uz', 'bank_name_ru', 'bank_name_en', 'bank_adress_uz', 'bank_adress_ru', 'bank_adress_en', 'mfo', 'inn', 'tel1', 'tel2', 'domen', 'code'], 'safe'],
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
        $query = Consulting::find()
            ->where(['is_deleted' => 0])
            ->andWhere(['in', 'id' , getConsOneIk()]);

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'hr', $this->hr])
            ->andFilterWhere(['like', 'bank_name_uz', $this->bank_name_uz])
            ->andFilterWhere(['like', 'bank_name_ru', $this->bank_name_ru])
            ->andFilterWhere(['like', 'bank_name_en', $this->bank_name_en])
            ->andFilterWhere(['like', 'bank_adress_uz', $this->bank_adress_uz])
            ->andFilterWhere(['like', 'bank_adress_ru', $this->bank_adress_ru])
            ->andFilterWhere(['like', 'bank_adress_en', $this->bank_adress_en])
            ->andFilterWhere(['like', 'mfo', $this->mfo])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'tel1', $this->tel1])
            ->andFilterWhere(['like', 'tel2', $this->tel2])
            ->andFilterWhere(['like', 'domen', $this->domen])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
