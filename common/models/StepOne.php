<?php

namespace common\models;

use common\models\AuthAssignment;
use common\models\Message;
use common\models\Student;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 * Signup form
 */
class StepOne extends Model
{
    public $jshshr;

    public function rules()
    {
        return [
            [['jshshr'], 'required'],
            [['jshshr'], 'string', 'min' => 14, 'max' => 14, 'message' => 'Pasport pin 14 xonali bo\'lishi kerak'],
            [['jshshr'], 'match', 'pattern' => '/^\d{14}$/', 'message' => 'Pasport pin faqat 14 ta raqamdan iborat bo‘lishi kerak'],
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

    public function ikStep($user , $student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $pinfl = $student->passport_pin;

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        if ($pinfl != $this->jshshr) {

            self::deleteNull($student->id);

            $integration = new Integration();
            $integration->pinfl = $this->jshshr;
            $data = $integration->checkPinfl();
            if ($data['is_ok']) {
                $data = $data['data'];
                $student->first_name = $data['first_name'];
                $student->last_name = $data['last_name'];
                $student->middle_name = $data['middle_name'];
                $student->passport_number = $data['passport_number'];
                $student->passport_serial = $data['passport_serial'];
                $student->passport_pin = (string)$data['passport_pin'];
                $student->birthday = $data['birthday'];
                $student->gender = $data['gender'];

                if (!$student->validate()){
                    $errors[] = $this->simple_errors($student->errors);
                }

                $amo = CrmPush::processType(3, $student, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }
            } else {
                $errors[] = ['Ma\'lumotlarni olishda xatolik yuz berdi.'];
                $transaction->rollBack();
                return ['is_ok' => false, 'errors' => $errors];
            }
        }

        $student->update(false);
        $user->step = 2;
        $user->update(false);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }

        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }

    public static function deleteNull($studentId)
    {
        try {
            Student::updateAll([
                'edu_type_id' => null,
                'edu_form_id' => null,
                'direction_id' => null,
                'edu_direction_id' => null,
                'lang_id' => null,
                'direction_course_id' => null,
                'course_id' => null,
                'edu_name' => null,
                'edu_direction' => null,
                'exam_type' => 0,
                'exam_date_id' => null,
            ], ['id' => $studentId]);

            foreach (['common\models\Exam', 'common\models\ExamSubject','common\models\StudentDtm', 'common\models\StudentPerevot', 'common\models\StudentMaster', 'common\models\StudentOferta'] as $table) {
                if (class_exists($table)) {
                    call_user_func([$table, 'updateAll'], ['is_deleted' => 1], ['student_id' => $studentId, 'is_deleted' => 0]);
                }
            }
        } catch (\Exception $e) {
            Yii::error("deleteNull error: " . $e->getMessage());
        }
    }

}
