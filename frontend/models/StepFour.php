<?php

namespace frontend\models;

use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\DirectionForm;
use common\models\DirectionLanguage;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\EduYearType;
use common\models\Languages;
use common\models\Message;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 *
 */
class StepFour extends Model
{
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

        $direction = $student->direction;

        if ($direction->oferta == 1) {
            $oferta = StudentOferta::findOne([
                'direction_id' => $direction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($oferta->file_status == 0) {
                $errors[] = ['5 yillik staj fayl yuklanmagan. Iltimos staj faylni yuklang!'];
            }
        }

        if ($student->edu_type_id == 2) {
            $perevot = StudentPerevot::findOne([
                'direction_id' => $direction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($perevot->file_status == 0) {
                $errors[] = ['Transkript yuklanmagan. Iltimos transkriptni yuklang!'];
            }
        } elseif ($student->edu_type_id == 3) {
            $dtm = StudentDtm::findOne([
                'direction_id' => $direction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($dtm->file_status == 0) {
                $errors[] = ['UZBMB  yuklanmagan. Iltimos UZBMBni yuklang!'];
            }
        }

        if (count($errors) == 0) {
            $user->step = 5;
            $user->step_confirm_time = time();
            $user->save(false);
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }

    public static function updateCrm($student)
    {
        try {
            $amoCrmClient = Yii::$app->ikAmoCrm;
            $leadId = $student->lead_id;
            $tags = [];
            $message = '';

            $updatedFields = [
                'pipelineId' => $student->pipeline_id,
                'statusId' => User::STEP_STATUS_6
            ];

            $customFields = [
                // '900679' => $student->last_name, // Familya
                // '900681' => $student->first_name, // Ism
                // '900683' => $student->middle_name,  // Otasi
                // '900749' => $student->passport_serial,  // pas seriya
                // '900751' => $student->passport_number, // pas raqam
                // '900757' => $student->birthday, // Tug'ilgan sana
                // '900685' => null, // qabul turi
                // '900687' => null, // Ta'lim shakli
                // '900689' => null, // Ta'lim tili
                // '900691' => null, // Ta'lim yo'nalishi
                // '900739' => null, // Imtixon shakli
            ];

            $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            return ['is_ok' => true, 'data' => $updatedLead];
        } catch (\Exception $e) {
            $errors[] = ['Ma\'lumot uzatishda xatolik STEP 2: ' . $e->getMessage()];
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

}
