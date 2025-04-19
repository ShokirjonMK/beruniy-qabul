<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StudentOferta;

/**
 * StudentOfertaSearch represents the model behind the search form of `common\models\StudentOferta`.
 */
class StudentOfertaSearch extends StudentOferta
{
    public $first_name;
    public $last_name;
    public $middle_name;
    public $phone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'student_id', 'edu_direction_id', 'direction_id', 'language_id', 'edu_type_id', 'edu_form_id', 'file_status', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['file', 'phone', 'first_name' ,'last_name' , 'middle_name'], 'safe'],
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
        $user = \Yii::$app->user->identity;
        $query = StudentOferta::find()
            ->joinWith(['student', 'user'])
            ->where([
                'student_oferta.is_deleted' => 0,
                'user.status' => [9,10],
                'user.cons_id' => $user->cons_id,
            ])->andWhere(['not in', 'file_status', [0]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['student_oferta.file_status' => $this->file_status]);

        $query->andFilterWhere(['like', 'student.first_name', $this->first_name])
            ->andFilterWhere(['like', 'student.last_name', $this->last_name])
            ->andFilterWhere(['like', 'student.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'student.username', $this->phone]);

        return $dataProvider;
    }
}
