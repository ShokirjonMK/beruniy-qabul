<?php

namespace common\models;

use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\Languages;
use common\models\Message;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentPerevot;
use common\models\StudentOferta;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;
use common\models\StudentMaster;

/**
 * Signup form
 */
class StepFour extends Model
{
    const STEP = 5;

    public $check;

    public function rules()
    {
        return [
            [['check'], 'integer'],
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
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $direction = $student->eduDirection;
        function checkFileStatus($model, $message, &$errors) {
            if (!$model || $model->file_status == 0) {
                $errors[] = [$message];
            }
        }

        if ($direction->is_oferta == 1) {
            $oferta = StudentOferta::findOne([
                'edu_direction_id' => $direction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            checkFileStatus($oferta, '5 yillik staj fayl yuklanmagan. Iltimos staj faylni yuklang!', $errors);
        }

        $fileChecks = [
            2 => ['class' => StudentPerevot::class, 'message' => 'Transkript yuklanmagan. Iltimos transkriptni yuklang!'],
            3 => ['class' => StudentDtm::class, 'message' => 'UZBMB yuklanmagan. Iltimos UZBMBni yuklang!'],
            4 => ['class' => StudentMaster::class, 'message' => 'Diplom yuklanmagan. Iltimos diplomni yuklang!']
        ];

        if (isset($fileChecks[$student->edu_type_id])) {
            $model = $fileChecks[$student->edu_type_id]['class']::findOne([
                'edu_direction_id' => $direction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            checkFileStatus($model, $fileChecks[$student->edu_type_id]['message'], $errors);
        }

        if ($student->edu_type_id == 1) {
            $exam = Exam::findOne([
                'edu_direction_id' => $direction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if (!$exam) {
                $errors[] = ['Imtixon mavjud emas!'];
            } else {
                $exam->contract_price = $direction->price;
                $exam->confirm_date = time();
                $exam->ball = 60;
                $exam->status = 3;
                $exam->save(false);
            }
        }

        $user->step = self::STEP;
        $user->is_confirm = time();
        $user->save(false);

        if (count($errors) == 0) {
            $amo = CrmPush::processType(6, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }

            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }
}
