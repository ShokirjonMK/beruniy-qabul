<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class StepTwo extends Model
{
    const STEP = 3;

    public $edu_type_id;

    public function rules()
    {
        return [
            // `edu_type_id` majburiy maydon
            [['edu_type_id'], 'required'],

            // `edu_type_id` butun son bo'lishi kerak
            [['edu_type_id'], 'integer'],

            // `edu_type_id` ning `status = 1` va `is_deleted = 0` shartlari bajarilgan holda mavjudligini tekshirish
            [['edu_type_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => EduType::class,
                'targetAttribute' => ['edu_type_id' => 'id'],
                'filter' => ['status' => 1, 'is_deleted' => 0],
                'message' => 'Tanlangan taʼlim turi mavjud emas yoki faol emas.'
            ],
        ];
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

        if (!$this->validate()) {
            return ['is_ok' => false, 'errors' => $this->simple_errors($this->errors)];
        }

        if ($student->edu_type_id != $this->edu_type_id) {
            $student->edu_type_id = $this->edu_type_id;
            self::deleteNull($student->id);

            $amo = CrmPush::processType(4, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if (!$student->save(false)) {
            $errors[] = ['Student maʼlumotlarini yangilashda xatolik yuz berdi.'];
        }

        $user->step = self::STEP;
        if (!$user->save(false)) {
            $errors[] = ['Foydalanuvchi maʼlumotlarini yangilashda xatolik yuz berdi.'];
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }

    public static function deleteNull($studentId)
    {
        Student::updateAll([
            'edu_form_id' => null,
            'direction_id' => null,
            'edu_direction_id' => null,
            'lang_id' => null,
            'direction_course_id' => null,
            'course_id' => null,
            'exam_type' => 0,
            'edu_name' => null,
            'edu_direction' => null,
            'exam_date_id' => null,
        ], ['id' => $studentId]);

        $tables = [
            'common\models\Exam',
            'common\models\ExamSubject',
            'common\models\StudentDtm',
            'common\models\StudentPerevot',
            'common\models\StudentMaster',
            'common\models\StudentOferta',
        ];

        foreach ($tables as $table) {
            if (class_exists($table)) {
                $table::updateAll(['is_deleted' => 1], ['student_id' => $studentId, 'is_deleted' => 0]);
            }
        }
    }

}
