<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Student;

/**
 * StudentSearch represents the model behind the search form of `common\models\Student`.
 */
class StudentSearch extends Student
{
    public $start_date;
    public $end_date;
    public $user_status;
    public $step;
    public $exam_date_status;
    public $branch_id;
    public $cons_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'is_down','is_down', 'cons_id', 'id', 'exam_date_id','exam_date_status', 'user_id', 'gender', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'edu_type_id', 'edu_form_id', 'direction_id', 'edu_direction_id', 'lang_id', 'direction_course_id', 'course_id', 'exam_type', 'step'], 'integer'],
            [['first_name', 'last_name', 'middle_name', 'student_phone', 'username', 'password', 'birthday', 'passport_number', 'passport_serial', 'passport_pin', 'passport_issued_date', 'passport_given_date', 'passport_given_by', 'adress', 'edu_name', 'edu_direction','user_status','end_date' ,'start_date'], 'safe'],
            [['passport_serial'], 'string', 'min' => 2, 'max' => 2, 'message' => 'Pasport seria 2 xonali bo\'lishi kerak'],
            [['passport_number'], 'string', 'min' => 7, 'max' => 7, 'message' => 'Pasport raqam 7 xonali bo\'lishi kerak'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $eduType)
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->where([
                's.edu_type_id' => $eduType->id,
                'u.step' => 5,
                'u.status' => 10,
                'u.user_role' => 'student',
            ])
            ->andWhere(getConsIk())->orderBy('s.id desc');

        // Ma'lumotlarni chiqarish uchun ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->status != null) {
            switch ($eduType->id) {
                case 1:
                    $query->innerJoin(Exam::tableName() . ' e', 's.id = e.student_id')
                        ->andWhere(['e.edu_type_id' => $eduType->id]);
                    if ($this->status <= 4) {
                        $query->andWhere(['e.status' => $this->status]);
                    } elseif ($this->status == 5) {
                        $query->andWhere(['e.status' => 3])->andWhere(['>', 'e.down_time', 0]);
                    } elseif ($this->status == 6) {
                        $query->andWhere(['e.status' => 3, 'e.down_time' => null]);
                    }
                    break;
                case 2:
                    $query->innerJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id')
                        ->andWhere([
                            'sp.file_status' => $this->status,
                            'sp.status' => 1,
                            'sp.is_deleted' => 0,
                        ]);
                    break;

                case 3:
                    $query->innerJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id')
                        ->andWhere([
                            'sd.file_status' => $this->status,
                            'sd.status' => 1,
                            'sd.is_deleted' => 0,
                        ]);
                    break;

                case 4:
                    $query->innerJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id')
                        ->andWhere([
                            'sm.file_status' => $this->status,
                            'sm.status' => 1,
                            'sm.is_deleted' => 0,
                        ]);
                    break;
            }
        }

        if ($this->start_date != null) {
            $start = $this->start_date.' 00:00:00';
            $query->andWhere(['>=', 'u.created_at', strtotime($start)]);
        }
        if ($this->end_date != null) {
            $end = $this->start_date.' 23:59:59';
            $query->andWhere(['<=', 'u.created_at', strtotime($end)]);
        }


        if ($this->user_status != null) {
            $query->andWhere(['u.status' => $this->user_status]);
        }

        if ($this->username != '+998 (__) ___-__-__') {
            $query->andFilterWhere(['like', 'u.username', $this->username]);
        }

        $query->andFilterWhere([
            's.id' => $this->id,
            's.user_id' => $this->user_id,
            's.gender' => $this->gender,
            's.birthday' => $this->birthday,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.is_deleted' => $this->is_deleted,
            's.edu_type_id' => $this->edu_type_id,
            's.edu_form_id' => $this->edu_form_id,
            's.direction_id' => $this->direction_id,
            's.edu_direction_id' => $this->edu_direction_id,
            's.lang_id' => $this->lang_id,
            's.direction_course_id' => $this->direction_course_id,
            's.course_id' => $this->course_id,
            's.exam_type' => $this->exam_type,
            'u.cons_id' => $this->cons_id,
            's.branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.student_phone', $this->student_phone])
            ->andFilterWhere(['like', 's.passport_number', $this->passport_number])
            ->andFilterWhere(['like', 's.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 's.passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 's.passport_issued_date', $this->passport_issued_date])
            ->andFilterWhere(['like', 's.passport_given_date', $this->passport_given_date])
            ->andFilterWhere(['like', 's.passport_given_by', $this->passport_given_by])
            ->andFilterWhere(['like', 's.adress', $this->adress])
            ->andFilterWhere(['like', 's.edu_name', $this->edu_name])
            ->andFilterWhere(['like', 's.edu_direction', $this->edu_direction]);

        return $dataProvider;
    }

    public function chala($params)
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->where([
                'u.status' => [9,10],
                'u.user_role' => 'student',
            ])
            ->andWhere(getConsIk())
            ->andWhere(['<', 'step' ,5])->orderBy('s.id desc');

        // Ma'lumotlarni chiqarish uchun ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->step != null) {
            $step = $this->step;
            if ($this->step < 5) {
                $query->andWhere(['u.step' => $step]);
            } elseif ($this->step == 5) {
                $query->andWhere(['u.step' => 0]);
            } elseif ($this->step == 6) {
                $query->andWhere(['>' , 'u.step', 0])->andWhere(['u.status' => 9]);
            }
        }

        if ($this->start_date != null) {
            $start = $this->start_date.' 00:00:00';
            $query->andWhere(['>=', 'u.created_at', strtotime($start)]);
        }
        if ($this->end_date != null) {
            $end = $this->start_date.' 23:59:59';
            $query->andWhere(['<=', 'u.created_at', strtotime($end)]);
        }

        if ($this->user_status != null) {
            $query->andWhere(['u.status' => $this->user_status]);
        }

        if ($this->username != '+998 (__) ___-__-__') {
            $query->andFilterWhere(['like', 'u.username', $this->username]);
        }

        $query->andFilterWhere([
            's.id' => $this->id,
            's.user_id' => $this->user_id,
            's.gender' => $this->gender,
            's.birthday' => $this->birthday,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.is_deleted' => $this->is_deleted,
            's.edu_type_id' => $this->edu_type_id,
            's.edu_form_id' => $this->edu_form_id,
            's.direction_id' => $this->direction_id,
            's.edu_direction_id' => $this->edu_direction_id,
            's.lang_id' => $this->lang_id,
            's.direction_course_id' => $this->direction_course_id,
            's.course_id' => $this->course_id,
            's.exam_type' => $this->exam_type,
            'u.cons_id' => $this->cons_id,
            's.branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.student_phone', $this->student_phone])
            ->andFilterWhere(['like', 's.passport_number', $this->passport_number])
            ->andFilterWhere(['like', 's.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 's.passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 's.passport_issued_date', $this->passport_issued_date])
            ->andFilterWhere(['like', 's.passport_given_date', $this->passport_given_date])
            ->andFilterWhere(['like', 's.passport_given_by', $this->passport_given_by])
            ->andFilterWhere(['like', 's.adress', $this->adress])
            ->andFilterWhere(['like', 's.edu_name', $this->edu_name])
            ->andFilterWhere(['like', 's.edu_direction', $this->edu_direction]);

        return $dataProvider;
    }

    public function contract($params)
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->leftJoin(Exam::tableName() . ' e', 's.id = e.student_id AND e.status = 3 AND e.is_deleted = 0')
            ->leftJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id AND sp.file_status = 2 AND sp.is_deleted = 0')
            ->leftJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id AND sd.file_status = 2 AND sd.is_deleted = 0')
            ->leftJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id AND sm.file_status = 2 AND sm.is_deleted = 0')
            ->where([
                'u.step' => 5,
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.is_deleted' => 0,
            ])
            ->andWhere(getConsIk())
            ->andWhere([
                'or',
                ['not', ['e.student_id' => null]],
                ['not', ['sp.student_id' => null]],
                ['not', ['sd.student_id' => null]],
                ['not', ['sm.student_id' => null]]
            ])->orderBy('s.id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->username != '+998 (__) ___-__-__') {
            $query->andFilterWhere(['like', 'u.username', $this->username]);
        }

        if ($this->start_date || $this->end_date) {
            $dateConditions = [];

            if ($this->start_date && $this->end_date) {
                $start = strtotime($this->start_date . ' 00:00:00');
                $end = strtotime($this->end_date . ' 23:59:59');

                $dateConditions[] = ['and', ['not', ['e.student_id' => null]], ['between', 'e.confirm_date', $start, $end]];
                $dateConditions[] = ['and', ['not', ['sp.student_id' => null]], ['between', 'sp.confirm_date', $start, $end]];
                $dateConditions[] = ['and', ['not', ['sd.student_id' => null]], ['between', 'sd.confirm_date', $start, $end]];
                $dateConditions[] = ['and', ['not', ['sm.student_id' => null]], ['between', 'sm.confirm_date', $start, $end]];
            } elseif ($this->start_date) {
                $start = strtotime($this->start_date . ' 00:00:00');

                $dateConditions[] = ['and', ['not', ['e.student_id' => null]], ['>=', 'e.confirm_date', $start]];
                $dateConditions[] = ['and', ['not', ['sp.student_id' => null]], ['>=', 'sp.confirm_date', $start]];
                $dateConditions[] = ['and', ['not', ['sd.student_id' => null]], ['>=', 'sd.confirm_date', $start]];
                $dateConditions[] = ['and', ['not', ['sm.student_id' => null]], ['>=', 'sm.confirm_date', $start]];
            } elseif ($this->end_date) {
                $end = strtotime($this->end_date . ' 23:59:59');

                $dateConditions[] = ['and', ['not', ['e.student_id' => null]], ['<=', 'e.confirm_date', $end]];
                $dateConditions[] = ['and', ['not', ['sp.student_id' => null]], ['<=', 'sp.confirm_date', $end]];
                $dateConditions[] = ['and', ['not', ['sd.student_id' => null]], ['<=', 'sd.confirm_date', $end]];
                $dateConditions[] = ['and', ['not', ['sm.student_id' => null]], ['<=', 'sm.confirm_date', $end]];
            }

            $query->andWhere(['or', ...$dateConditions]);
        }



        $query->andFilterWhere([
            's.id' => $this->id,
            's.user_id' => $this->user_id,
            's.gender' => $this->gender,
            's.birthday' => $this->birthday,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.edu_type_id' => $this->edu_type_id,
            's.edu_form_id' => $this->edu_form_id,
            's.direction_id' => $this->direction_id,
            's.edu_direction_id' => $this->edu_direction_id,
            's.lang_id' => $this->lang_id,
            's.direction_course_id' => $this->direction_course_id,
            's.course_id' => $this->course_id,
            's.exam_type' => $this->exam_type,
            'u.cons_id' => $this->cons_id,
            's.branch_id' => $this->branch_id,
            's.is_down' => $this->is_down,
        ]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.student_phone', $this->student_phone])
            ->andFilterWhere(['like', 's.passport_number', $this->passport_number])
            ->andFilterWhere(['like', 's.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 's.passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 's.passport_issued_date', $this->passport_issued_date])
            ->andFilterWhere(['like', 's.passport_given_date', $this->passport_given_date])
            ->andFilterWhere(['like', 's.passport_given_by', $this->passport_given_by])
            ->andFilterWhere(['like', 's.adress', $this->adress])
            ->andFilterWhere(['like', 's.edu_name', $this->edu_name])
            ->andFilterWhere(['like', 's.edu_direction', $this->edu_direction]);

        return $dataProvider;
    }

    public function all($params)
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->where([
                'u.status' => [5, 9, 10],
                'u.user_role' => 'student',
            ])
            ->andWhere(getConsIk())
            ->orderBy('s.id desc');

        // Ma'lumotlarni chiqarish uchun ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->user_status != null) {
            $query->andWhere(['u.status' => $this->user_status]);
        }

        if ($this->username != '+998 (__) ___-__-__') {
            $query->andFilterWhere(['like', 'u.username', $this->username]);
        }

        $query->andFilterWhere([
            's.id' => $this->id,
            's.user_id' => $this->user_id,
            's.gender' => $this->gender,
            's.birthday' => $this->birthday,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.is_deleted' => $this->is_deleted,
            's.edu_type_id' => $this->edu_type_id,
            's.edu_form_id' => $this->edu_form_id,
            's.direction_id' => $this->direction_id,
            's.edu_direction_id' => $this->edu_direction_id,
            's.lang_id' => $this->lang_id,
            's.direction_course_id' => $this->direction_course_id,
            's.course_id' => $this->course_id,
            's.exam_type' => $this->exam_type,
            'u.cons_id' => $this->cons_id,
            's.branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.student_phone', $this->student_phone])
            ->andFilterWhere(['like', 's.passport_number', $this->passport_number])
            ->andFilterWhere(['like', 's.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 's.passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 's.passport_issued_date', $this->passport_issued_date])
            ->andFilterWhere(['like', 's.passport_given_date', $this->passport_given_date])
            ->andFilterWhere(['like', 's.passport_given_by', $this->passport_given_by])
            ->andFilterWhere(['like', 's.adress', $this->adress])
            ->andFilterWhere(['like', 's.edu_name', $this->edu_name])
            ->andFilterWhere(['like', 's.edu_direction', $this->edu_direction]);

        return $dataProvider;
    }

    public function offline($params)
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->where([
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.exam_type' => 1,
            ])->orderBy('s.id desc');

        // Ma'lumotlarni chiqarish uchun ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->exam_date_status != null) {
            $query->innerJoin(Exam::tableName() . ' e', 's.id = e.student_id');
            if ($this->exam_date_status <= 4) {
                $query->andWhere(['e.status' => $this->exam_date_status]);
            } elseif ($this->exam_date_status == 5) {
                $query->andWhere(['e.status' => 3, 's.is_down' => 1]);
            } elseif ($this->exam_date_status == 6) {
                $query->andWhere(['e.status' => 3, 's.is_down' => 0]);
            }
        }

        if ($this->user_status != null) {
            $query->andWhere(['u.status' => $this->user_status]);
        }

        if ($this->username != '+998 (__) ___-__-__') {
            $query->andFilterWhere(['like', 'u.username', $this->username]);
        }

        $query->andFilterWhere([
            's.id' => $this->id,
            's.user_id' => $this->user_id,
            's.gender' => $this->gender,
            's.birthday' => $this->birthday,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.is_deleted' => $this->is_deleted,
            's.edu_type_id' => $this->edu_type_id,
            's.edu_form_id' => $this->edu_form_id,
            's.direction_id' => $this->direction_id,
            's.edu_direction_id' => $this->edu_direction_id,
            's.lang_id' => $this->lang_id,
            's.direction_course_id' => $this->direction_course_id,
            's.course_id' => $this->course_id,
            's.exam_date_id' => $this->exam_date_id,
            's.exam_type' => $this->exam_type,
            'u.cons_id' => $this->cons_id,
            's.branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.student_phone', $this->student_phone])
            ->andFilterWhere(['like', 's.passport_number', $this->passport_number])
            ->andFilterWhere(['like', 's.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 's.passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 's.passport_issued_date', $this->passport_issued_date])
            ->andFilterWhere(['like', 's.passport_given_date', $this->passport_given_date])
            ->andFilterWhere(['like', 's.passport_given_by', $this->passport_given_by])
            ->andFilterWhere(['like', 's.adress', $this->adress])
            ->andFilterWhere(['like', 's.edu_name', $this->edu_name])
            ->andFilterWhere(['like', 's.edu_direction', $this->edu_direction]);

        return $dataProvider;
    }

    public function archive($params)
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->where([
                'u.status' => [0],
                'u.user_role' => 'student',
            ])->orderBy('s.id desc');

        // Ma'lumotlarni chiqarish uchun ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->exam_date_status != null) {
            $query->innerJoin(Exam::tableName() . ' e', 's.id = e.student_id');
            if ($this->exam_date_status <= 4) {
                $query->andWhere(['e.status' => $this->exam_date_status]);
            } elseif ($this->exam_date_status == 5) {
                $query->andWhere(['e.status' => 3, 's.is_down' => 1]);
            } elseif ($this->exam_date_status == 6) {
                $query->andWhere(['e.status' => 3, 's.is_down' => 0]);
            }
        }

        if ($this->user_status != null) {
            $query->andWhere(['u.status' => $this->user_status]);
        }

        if ($this->username != '+998 (__) ___-__-__') {
            $query->andFilterWhere(['like', 'u.username', $this->username]);
        }

        $query->andFilterWhere([
            's.id' => $this->id,
            's.user_id' => $this->user_id,
            's.gender' => $this->gender,
            's.birthday' => $this->birthday,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.is_deleted' => $this->is_deleted,
            's.edu_type_id' => $this->edu_type_id,
            's.edu_form_id' => $this->edu_form_id,
            's.direction_id' => $this->direction_id,
            's.edu_direction_id' => $this->edu_direction_id,
            's.lang_id' => $this->lang_id,
            's.direction_course_id' => $this->direction_course_id,
            's.course_id' => $this->course_id,
            's.exam_date_id' => $this->exam_date_id,
            's.exam_type' => $this->exam_type,
            'u.cons_id' => $this->cons_id,
            's.branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 's.first_name', $this->first_name])
            ->andFilterWhere(['like', 's.last_name', $this->last_name])
            ->andFilterWhere(['like', 's.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 's.student_phone', $this->student_phone])
            ->andFilterWhere(['like', 's.passport_number', $this->passport_number])
            ->andFilterWhere(['like', 's.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 's.passport_pin', $this->passport_pin])
            ->andFilterWhere(['like', 's.passport_issued_date', $this->passport_issued_date])
            ->andFilterWhere(['like', 's.passport_given_date', $this->passport_given_date])
            ->andFilterWhere(['like', 's.passport_given_by', $this->passport_given_by])
            ->andFilterWhere(['like', 's.adress', $this->adress])
            ->andFilterWhere(['like', 's.edu_name', $this->edu_name])
            ->andFilterWhere(['like', 's.edu_direction', $this->edu_direction]);

        return $dataProvider;
    }
}
