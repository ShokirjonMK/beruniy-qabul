<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `common\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * {@inheritdoc}
     */

    public $cons_id;
    public $role;

    public function rules()
    {
        return [
            [['id', 'user_id', 'gender', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted' , 'cons_id'], 'integer'],
            [['first_name', 'last_name', 'middle_name', 'phone', 'brithday', 'image', 'adress', 'password', 'role'], 'safe'],
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
        $userRole = $user->user_role;

        $query = Employee::find()
            ->alias('e') // Aliasni ishlatamiz
            ->innerJoin(['u' => User::tableName()], 'u.id = e.user_id') // User jadvali bilan birlashtirish
            ->innerJoin(['aic' => AuthChild::tableName()], 'u.user_role = aic.child') // Role bilan birlashtirish
            ->where([
                'u.cons_id' => $user->cons->id,
                'u.status' => [10,9],
                'aic.parent' => $userRole,
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'e.id' => $this->id,
            'e.user_id' => $this->user_id,
            'e.gender' => $this->gender,
            'e.status' => $this->status,
            'e.created_at' => $this->created_at,
            'e.updated_at' => $this->updated_at,
            'e.created_by' => $this->created_by,
            'e.updated_by' => $this->updated_by,
            'e.is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'e.first_name', $this->first_name])
            ->andFilterWhere(['like', 'e.last_name', $this->last_name])
            ->andFilterWhere(['like', 'e.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'u.user_role', $this->role])
            ->andFilterWhere(['like', 'e.phone', $this->phone])
            ->andFilterWhere(['like', 'e.brithday', $this->brithday])
            ->andFilterWhere(['like', 'e.image', $this->image])
            ->andFilterWhere(['like', 'e.adress', $this->adress])
            ->andFilterWhere(['like', 'e.password', $this->password]);

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $user = \Yii::$app->user->identity;
        $userRole = $user->user_role;

        $query = Employee::find()
            ->alias('e') // Aliasni ishlatamiz
            ->innerJoin(['u' => User::tableName()], 'u.id = e.user_id') // User jadvali bilan birlashtirish
            ->innerJoin(['aic' => AuthChild::tableName()], 'u.user_role = aic.child') // Role bilan birlashtirish
            ->where([
                'aic.parent' => $userRole,
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'e.id' => $this->id,
            'e.user_id' => $this->user_id,
            'e.gender' => $this->gender,
            'e.status' => $this->status,
            'e.created_at' => $this->created_at,
            'e.updated_at' => $this->updated_at,
            'e.created_by' => $this->created_by,
            'e.updated_by' => $this->updated_by,
            'e.is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'e.first_name', $this->first_name])
            ->andFilterWhere(['like', 'e.last_name', $this->last_name])
            ->andFilterWhere(['like', 'e.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'u.user_role', $this->role])
            ->andFilterWhere(['like', 'e.phone', $this->phone])
            ->andFilterWhere(['like', 'e.brithday', $this->brithday])
            ->andFilterWhere(['like', 'e.image', $this->image])
            ->andFilterWhere(['like', 'e.adress', $this->adress])
            ->andFilterWhere(['like', 'e.password', $this->password]);

        return $dataProvider;
    }

}
