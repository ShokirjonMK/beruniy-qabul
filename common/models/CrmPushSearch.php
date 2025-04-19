<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CrmPush;

/**
 * CrmPushSearch represents the model behind the search form of `common\models\CrmPush`.
 */
class CrmPushSearch extends CrmPush
{
    public $first_name;
    public $last_name;
    public $middle_name;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'type', 'push_time', 'lead_id', 'lead_status', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['data', 'first_name' , 'last_name' , 'middle_name'], 'safe'],
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
        $query = CrmPush::find()->joinWith('student');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'crm_push.id' => $this->id,
            'crm_push.student_id' => $this->student_id,
            'crm_push.type' => $this->type,
            'crm_push.push_time' => $this->push_time,
            'crm_push.lead_id' => $this->lead_id,
            'crm_push.lead_status' => $this->lead_status,
            'crm_push.status' => $this->status,
            'crm_push.created_at' => $this->created_at,
            'crm_push.updated_at' => $this->updated_at,
            'crm_push.created_by' => $this->created_by,
            'crm_push.updated_by' => $this->updated_by,
            'crm_push.is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'crm_push.data', $this->data])
            ->andFilterWhere(['like', 'student.first_name', $this->first_name])
            ->andFilterWhere(['like', 'student.last_name', $this->last_name])
            ->andFilterWhere(['like', 'student.middle_name', $this->middle_name]);

        return $dataProvider;
    }

}
