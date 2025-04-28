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
class StepOneTwo extends Model
{
    public $birthday;
    public $seria;
    public $number;


    public function rules()
    {
        return [
            [['birthday', 'seria', 'number'], 'required'],
            [['seria'], 'string', 'min' => 2, 'max' => 2, 'message' => 'Pasport seria 2 xonali bo\'lishi kerak'],
            ['seria', 'match', 'pattern' => '/^[^\d]*$/', 'message' => 'Pasport seriasi raqamlardan iborat bo\'lmasligi kerak'],
            [['birthday'], 'safe'],
            [['number'], 'string', 'min' => 7, 'max' => 7, 'message' => 'Pasport raqam 7 xonali bo\'lishi kerak'],
            ['number', 'match', 'pattern' => '/^\d{7}$/', 'message' => 'Pasport raqam faqat raqamlardan iborat bo\'lishi kerak'],
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
        $seriaNumber = $student->passport_serial.$student->passport_number;
        $sn = $this->seria.$this->number;

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        if ($sn != $seriaNumber) {

            self::deleteNull($student->id);

            $birthday = date("d-m-Y" , strtotime($this->birthday));

//            $integration = new Integration();
//            $integration->birthDate = date("d-m-Y" , strtotime($this->birthday));
//            $integration->series = $this->seria;
//            $integration->number = $this->number;
//            $data = $integration->checkPassport();


            $client = new Client();
            $url = 'https://api.online-mahalla.uz/api/v1/public/tax/passport';
            $params = [
                'series' => $this->seria,
                'number' => $this->number,
                'birth_date' => date("Y-m-d" , strtotime($this->birthday)),
            ];
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->setData($params)
                ->send();


            if ($response->isOk) {
                $responseData = $response->data;
                $passport = $responseData['data']['info']['data'];
                $student->first_name = $passport['name'];
                $student->last_name = $passport['sur_name'];
                $student->middle_name = $passport['patronymic_name'];
                $student->passport_number = $this->number;
                $student->passport_serial = $this->seria;
                $student->passport_pin = (string)$passport['pinfl'];
                $student->birthday = date("Y-m-d" , strtotime($this->birthday));
                $student->gender = $passport['gender'];

                if (!$student->validate()){
                    $errors[] = $this->simple_errors($student->errors);
                }

                if (in_array(null, [
                    $student->first_name,
                    $student->last_name,
                    $student->middle_name,
                    $student->passport_number,
                    $student->passport_serial,
                    $student->passport_pin,
                    $student->birthday,
                    $student->gender,
                ], true)) {
                    $errors[] = ['Pasport ma\'lumot yuklashda xatolik'];
                }

                $query = Student::find()
                    ->joinWith('user')
                    ->where(['passport_pin' => $student->passport_pin])
                    ->andWhere(['user.status' => [9, 10]])
                    ->one();

                if ($query) {
                    $queryUser = $query->user;
                    if ($queryUser->id != $user->id) {
                        $errors[] = ['Bu pasport ma\'lumot avval ro\'yhatdan o\'tgan. Tel:' . $queryUser->username];
                        $transaction->rollBack();
                        return ['is_ok' => false, 'errors' => $errors];
                    }
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


//            if ($data['is_ok']) {
////                $data = $data['data'];
////                $student->first_name = $data['first_name'];
////                $student->last_name = $data['last_name'];
////                $student->middle_name = $data['middle_name'];
////                $student->passport_number = $data['passport_number'];
////                $student->passport_serial = $data['passport_serial'];
////                $student->passport_pin = (string)$data['passport_pin'];
////                $student->birthday = $data['birthday'];
////                $student->gender = $data['gender'];
////
////                if (!$student->validate()){
////                    $errors[] = $this->simple_errors($student->errors);
////                }
////
////                if (in_array(null, [
////                    $student->first_name,
////                    $student->last_name,
////                    $student->middle_name,
////                    $student->passport_number,
////                    $student->passport_serial,
////                    $student->passport_pin,
////                    $student->birthday,
////                    $student->gender,
////                ], true)) {
////                    $errors[] = ['Pasport ma\'lumot yuklashda xatolik'];
////                }
//
//                $query = Student::find()
//                    ->joinWith('user')
//                    ->where(['passport_pin' => $student->passport_pin])
//                    ->andWhere(['user.status' => [9, 10]])
//                    ->one();
//
//                if ($query) {
//                    $queryUser = $query->user;
//                    if ($queryUser->id != $user->id) {
//                        $errors[] = ['Bu pasport ma\'lumot avval ro\'yhatdan o\'tgan. Tel:' . $queryUser->username];
//                        $transaction->rollBack();
//                        return ['is_ok' => false, 'errors' => $errors];
//                    }
//                }
//
//                $amo = CrmPush::processType(3, $student, $user);
//                if (!$amo['is_ok']) {
//                    $transaction->rollBack();
//                    return ['is_ok' => false , 'errors' => $amo['errors']];
//                }
//            } else {
//                $errors[] = ['Ma\'lumotlarni olishda xatolik yuz berdi.'];
//                $transaction->rollBack();
//                return ['is_ok' => false, 'errors' => $errors];
//            }
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
