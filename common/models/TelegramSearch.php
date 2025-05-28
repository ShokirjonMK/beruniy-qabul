<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Telegram;

/**
 * TelegramSearch represents the model behind the search form of `common\models\Telegram`.
 */
class TelegramSearch extends Telegram
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'step', 'lang_id', 'edu_type_id', 'edu_form_id', 'edu_lang_id', 'edu_direction_id', 'direction_course_id', 'exam_type', 'branch_id', 'exam_date_id', 'cons_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['telegram_id', 'phone', 'username', 'birthday', 'passport_number', 'passport_serial', 'passport_pin', 'oferta', 'tr', 'dtm', 'master'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Telegram::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'step' => $this->step,
            'lang_id' => $this->lang_id,
            'edu_type_id' => $this->edu_type_id,
            'edu_form_id' => $this->edu_form_id,
            'edu_lang_id' => $this->edu_lang_id,
            'edu_direction_id' => $this->edu_direction_id,
            'direction_course_id' => $this->direction_course_id,
            'exam_type' => $this->exam_type,
            'branch_id' => $this->branch_id,
            'exam_date_id' => $this->exam_date_id,
            'cons_id' => $this->cons_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'telegram_id', $this->telegram_id])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'passport_number', $this->passport_number])
            ->andFilterWhere(['like', 'passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 'passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 'oferta', $this->oferta])
            ->andFilterWhere(['like', 'tr', $this->tr])
            ->andFilterWhere(['like', 'dtm', $this->dtm])
            ->andFilterWhere(['like', 'master', $this->master]);

        return $dataProvider;
    }
}
