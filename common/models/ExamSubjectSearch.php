<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExamSubject;

/**
 * ExamSubjectSearch represents the model behind the search form of `common\models\ExamSubject`.
 */
class ExamSubjectSearch extends ExamSubject
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
            [['id', 'exam_id', 'user_id', 'student_id', 'edu_direction_id', 'direction_id', 'language_id', 'edu_type_id', 'edu_form_id', 'direction_subject_id', 'subject_id', 'file_status', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['ball'], 'number'],
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
        $query = ExamSubject::find()
            ->joinWith(['student s', 'user u'])
            ->where([
                'exam_subject.is_deleted' => 0,
                'u.status' => [9,10],
            ])
            ->andWhere(getConsIk())
            ->andWhere(['not in', 'exam_subject.file_status', [0]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['exam_subject.file_status' => $this->file_status]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.username', $this->phone]);

        return $dataProvider;
    }
}
