<?php

namespace common\models;

use Yii;
use yii\base\Model;

class StepThreeOne extends Model
{
    const STEP = 4;

    public $filial_id;
    public $lang_id;
    public $edu_form_id;
    public $edu_type_id;
    public $edu_direction_id;
    public $exam_type;
    public $exam_date_id;

    public function rules()
    {
        return [
            [['filial_id', 'lang_id', 'edu_form_id', 'edu_direction_id', 'edu_type_id',], 'required'],
            [['filial_id', 'lang_id', 'edu_form_id', 'edu_direction_id', 'edu_type_id', 'exam_type' , 'exam_date_id'], 'integer'],

            [['filial_id'], function ($attribute) {
                if (!Branch::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan filial mavjud emas.');
                }
            }],
            [['lang_id'], function ($attribute) {
                if (!Lang::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan til mavjud emas.');
                }
            }],
            [['edu_form_id'], function ($attribute) {
                if (!EduForm::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan taʼlim shakli mavjud emas.');
                }
            }],
            [['edu_direction_id'], function ($attribute) {
                if (!EduDirection::find()->where([
                    'id' => $this->$attribute,
                    'branch_id' => $this->filial_id,
                    'edu_form_id' => $this->edu_form_id,
                    'edu_type_id' => $this->edu_type_id,
                    'lang_id' => $this->lang_id,
                    'status' => 1,
                    'is_deleted' => 0
                ])->exists()) {
                    $this->addError($attribute, 'Tanlangan taʼlim yo‘nalishi mavjud emas.');
                }
            }],
            [['edu_type_id'], function ($attribute) {
                if (!EduType::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan taʼlim turi mavjud emas.');
                }
            }],
        ];
    }

    public function getEduDirection()
    {
        return $this->hasOne(EduDirection::class, ['id' => 'edu_direction_id']);
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }

    public function ikStep($user, $student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $examDate = false;
        $this->exam_type = 0;

        if (!$this->validate()) {
            $transaction->rollBack();
            $errors[] = $this->simple_errors($this->errors);
            return ['is_ok' => false, 'errors' => $errors];
        }

//        if ($this->exam_type == 1) {
//
//            if ($this->exam_date_id == null) {
//                $errors[] = ['Imtixon sanasi tanlang.'];
//                $transaction->rollBack();
//                return ['is_ok' => false , 'errors' => $errors];
//            }
//
//            if ($student->exam_date_id != $this->exam_date_id) {
//                $examDate = ExamDate::findOne([
//                    'id' => $this->exam_date_id,
//                    'branch_id' => $this->filial_id,
//                    'status' => 1,
//                    'is_deleted' => 0
//                ]);
//                if (!$examDate) {
//                    $errors[] = ['Imtixon sanasi o\'zgarganligi uchun boshqa sanani tanlang.'];
//                    $transaction->rollBack();
//                    return ['is_ok' => false , 'errors' => $errors];
//                }
//
//                $studentExamDateCount = Student::find()
//                    ->joinWith('user')
//                    ->where([
//                        'student.exam_date_id' => $examDate->id,
//                    ])
//                    ->andWhere(['user.status' => [9, 10]])
//                    ->count();
//                if ($studentExamDateCount >= $examDate->limit) {
//                    $examDate->status = 0;
//                    $examDate->save(false);
//                }
//            }
//        } else {
//            $this->exam_date_id = null;
//        }

        $student->setAttributes([
            'branch_id' => $this->filial_id,
            'exam_type' => $this->exam_type,
            'exam_date_id' => $this->exam_date_id,
        ]);

        $eduDirection = EduDirection::findOne($this->edu_direction_id);
        if ($student->edu_direction_id != $this->edu_direction_id) {
            $student->setAttributes([
                'lang_id' => $this->lang_id,
                'edu_form_id' => $this->edu_form_id,
                'edu_direction_id' => $this->edu_direction_id,
                'edu_type_id' => $this->edu_type_id,
                'direction_id' => $eduDirection->direction_id,
            ]);

            $result = StepThree::createEduType($student);
            if (!$result['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $result['errors']];
            }

            $amo = CrmPush::processType(5, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if (!$student->save(false)) {
            $errors[] = ['Student maʼlumotlarini yangilashda xatolik yuz berdi.'];
        }

        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->type = 101;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            CrmPush::FILIAL => $student->branch->name_uz,
            CrmPush::EXAM_TYPE => Status::getExamStatus($student->exam_type),
            CrmPush::EXAM_DATE => $examDate ? $examDate->date : null,
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);

        $noStudentUser = Yii::$app->user->identity;
        if ($noStudentUser->user_role != 'student') {
            $user->step = 5;
            if ($student->edu_type_id == 1) {
                $exam = Exam::findOne([
                    'edu_direction_id' => $eduDirection->id,
                    'student_id' => $student->id,
                    'status' => 1,
                    'is_deleted' => 0
                ]);
                if (!$exam) {
                    $errors[] = ['Imtixon mavjud emas!'];
                } else {
                    $exam->contract_price = $eduDirection->price;
                    $exam->confirm_date = time();
                    $exam->ball = 60;
                    $exam->status = 3;
                    $exam->save(false);
                }
            }
        } else {
            $user->step = self::STEP;
        }

        if (!$user->save(false)) {
            $errors[] = ['Foydalanuvchi maʼlumotlarini yangilashda xatolik yuz berdi.'];
        }

        if ($user->step == 5) {
            $amo = CrmPush::processType(6, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }
}
