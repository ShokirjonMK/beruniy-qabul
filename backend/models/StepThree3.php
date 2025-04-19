<?php

namespace backend\models;

use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\DirectionForm;
use common\models\DirectionLanguage;
use common\models\DirectionSubject;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\EduYearType;
use common\models\Exam;
use common\models\ExamStudentQuestions;
use common\models\ExamSubject;
use common\models\Filial;
use common\models\Languages;
use common\models\Message;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentMagistr;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 * Signup form
 */
class StepThree3 extends Model
{
    public $language_id;
    public $edu_year_form_id;
    public $direction_id;

    public function rules()
    {
        return [
            [['language_id', 'edu_year_form_id', 'direction_id'], 'required'],
            [['language_id', 'edu_year_form_id', 'direction_id'], 'integer'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Languages::class, 'targetAttribute' => ['language_id' => 'id']],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::class, 'targetAttribute' => ['direction_id' => 'id']],
            [['edu_year_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduYearForm::class, 'targetAttribute' => ['edu_year_form_id' => 'id']],
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

        $direction = Direction::findOne([
            'id' => (int)$this->direction_id,
            'edu_year_type_id' => $student->edu_year_type_id,
            'edu_year_form_id' => (int)$this->edu_year_form_id,
            'language_id' => (int)$this->language_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if (!$direction) {
            $errors[] = ['Yo\'nalish noto\'g\'ri tanlandi.'];
        } else {
            $student->direction_course_id = null;
            $student->course_id = null;
            $student->edu_name = null;
            $student->edu_direction = null;
            if ($direction->id != $student->direction_id) {

                Exam::updateAll(['status' => 0 , 'is_deleted' => 2] , ['direction_id' => $student->direction_id , 'student_id' => $student->id]);
                ExamSubject::updateAll(['status' => 0 , 'is_deleted' => 2] , ['direction_id' => $student->direction_id , 'student_id' => $student->id]);
                ExamStudentQuestions::updateAll(['status' => 0 , 'is_deleted' => 2] , ['user_id' => $student->user_id]);
                StudentOferta::updateAll(['status' => 0 , 'is_deleted' => 2] , ['direction_id' => $student->direction_id , 'student_id' => $student->id]);
                StudentPerevot::updateAll(['status' => 0 , 'is_deleted' => 2] , ['direction_id' => $student->direction_id , 'student_id' => $student->id]);
                StudentDtm::updateAll(['status' => 0 , 'is_deleted' => 2] , ['direction_id' => $student->direction_id , 'student_id' => $student->id]);

                $student->direction_id = (int)$this->direction_id;
                $student->edu_year_form_id = (int)$this->edu_year_form_id;
                $student->edu_form_id = $student->eduYearForm->edu_form_id;
                $student->language_id = (int)$this->language_id;

                if ($direction->oferta == 1) {
                    $oferta = new StudentOferta();
                    $oferta->user_id = $user->id;
                    $oferta->student_id = $student->id;
                    $oferta->direction_id = $student->direction_id;
                    $oferta->save(false);
                }
                if ($student->edu_type_id == 3) {
                    $dtm = new StudentDtm();
                    $dtm->user_id = $user->id;
                    $dtm->student_id = $student->id;
                    $dtm->direction_id = $student->direction_id;
                    $dtm->save(false);
                } else {
                    $errors[] = ['Xatolik!!!'];
                }
            }

            $student->save(false);
            $user->step = 5;
            $user->save(false);

            if ($student->lead_id != null) {
                $result = StepThree3::updateCrm($student);
                if ($result['is_ok']) {
                    $amo = $result['data'];
                    $student->pipeline_id = $amo->pipelineId;
                    $student->status_id = $amo->statusId;
                    $student->save(false);
                } else {
                    return ['is_ok' => false, 'errors' => $result['errors']];
                }
            }

        }

        if (count($errors) == 0) {
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

            $examTYpe = "Online";
            if ($student->exam_type == 1) {
                $examTYpe = "Offline";
            }

            $customFields = [
                // '900679' => $student->last_name, // Familya
                // '900681' => $student->first_name, // Ism
                // '900683' => $student->middle_name,  // Otasi
                // '900749' => $student->passport_serial,  // pas seriya
                // '900751' => $student->passport_number, // pas raqam
                // '900757' => $student->birthday, // Tug'ilgan sana
                //'900685' => $student->eduType->name_uz, // qabul turi
                '900687' => $student->eduForm->name_uz, // Ta'lim shakli
                '900689' => $student->language->name_uz, // Ta'lim tili
                '900691' => $student->direction->name_uz, // Ta'lim yo'nalishi
                '900739' => $examTYpe, // imtihon shakli
            ];

            $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            return ['is_ok' => true, 'data' => $updatedLead];
        } catch (\Exception $e) {
            $errors[] = ['Ma\'lumot uzatishda xatolik STEP 2: ' . $e->getMessage()];
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

}
