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
 * Signup form
 */
class StepThree extends Model
{
    public $language_id;
    public $edu_year_form_id;
    public $direction_id;
    public $exam_type;

    public function rules()
    {
        return [
            [['language_id', 'edu_year_form_id', 'direction_id', 'exam_type'], 'required'],
            [['language_id', 'edu_year_form_id', 'direction_id', 'exam_type'], 'integer'],
            [['exam_type'], 'in', 'range' => [0, 1], 'message' => 'Exam type must be either 0 or 1.'],
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
            $student->exam_type = (int)$this->exam_type;
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
                $student->save(false);

                if ($direction->oferta == 1) {
                    $oferta = new StudentOferta();
                    $oferta->user_id = $user->id;
                    $oferta->student_id = $student->id;
                    $oferta->direction_id = $student->direction_id;
                    $oferta->save(false);
                }

                if ($student->edu_type_id == 1) {
                    $exam = new Exam();
                    $exam->user_id = $user->id;
                    $exam->student_id = $student->id;
                    $exam->direction_id = $student->direction_id;
                    $exam->language_id = $student->language_id;
                    $exam->edu_year_form_id = $student->edu_year_form_id;
                    $exam->edu_year_type_id = $student->edu_year_type_id;
                    $exam->edu_type_id = $student->edu_type_id;
                    $exam->edu_form_id = $student->edu_form_id;
                    $exam->save(false);

                    $directionSubjects = DirectionSubject::find()
                        ->where([
                            'direction_id' => $exam->direction_id,
                            'status' => 1,
                            'is_deleted' => 0
                        ])->all();
                    if (count($directionSubjects) > 0) {
                        foreach ($directionSubjects as $directionSubject) {
                            $examSubject = new ExamSubject();
                            $examSubject->user_id = $user->id;
                            $examSubject->student_id = $student->id;
                            $examSubject->exam_id = $exam->id;
                            $examSubject->direction_id = $exam->direction_id;
                            $examSubject->direction_subject_id = $directionSubject->id;
                            $examSubject->subject_id = $directionSubject->subject_id;
                            $examSubject->language_id = $exam->language_id;
                            $examSubject->edu_year_form_id = $exam->edu_year_form_id;
                            $examSubject->edu_year_type_id = $exam->edu_year_type_id;
                            $examSubject->edu_type_id = $exam->edu_type_id;
                            $examSubject->edu_form_id = $exam->edu_form_id;
                            $examSubject->save(false);
                        }
                    } else {
                        $errors[] = ['Yo\'nalishda fanlar mavjud emas! Aloqaga chiqing!'];
                    }
                } else {
                    $errors[] = ['ERRORS!!! PEREVOT.'];
                }

            } else {
                $student->save(false);
            }
            $user->step = 5;
            $user->save(false);
            if ($student->lead_id != null) {
                $result = StepThree::updateCrm($student);
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
