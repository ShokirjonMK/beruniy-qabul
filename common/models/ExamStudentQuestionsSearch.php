<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DirectionSubject;

/**
 * ExamStudentQuestionsSearch represents the model behind the search form of `common\models\ExamStudentQuestions`.
 */
class ExamStudentQuestionsSearch extends ExamStudentQuestions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'exam_id','exam_subject_id', 'question_id', 'option_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
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
    public function search($params , $exam)
    {
        $query = ExamStudentQuestions::find()
            ->joinWith('examSubject') // modeldagi relation nomi
            ->where([
                'exam_student_questions.exam_id' => $exam->id,
                'exam_student_questions.status' => 1,
                'exam_student_questions.is_deleted' => 0,
            ])
            ->andWhere(['!=', 'exam_subject.file_status', 2])
            ->orderBy(['exam_student_questions.order' => SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
