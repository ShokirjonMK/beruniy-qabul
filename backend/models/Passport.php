<?php

namespace backend\models;

use common\models\AuthAssignment;
use common\models\Message;
use common\models\Student;
use frontend\models\StepOne;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 * Signup form
 */
class Passport extends Model
{
    public $birthday;
    public $seria;
    public $number;


    public function rules()
    {
        return [
            [['birthday', 'seria', 'number'], 'required'],
            [['seria'], 'string', 'min' => 2, 'max' => 2, 'message' => 'Pasport seria 2 xonali bo\'lishi kerak'],
            ['seria', 'match', 'pattern' => '/^[^\d]*$/', 'message' => 'Pasport seriasi faqat raqamlardan iborat bo\'lmasligi kerak'],
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

    public function ikStep($student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $client = new Client();
        $url = 'https://api.online-mahalla.uz/api/v1/public/tax/passport';
        $params = [
            'series' => $this->seria,
            'number' => $this->number,
            'birth_date' => date('Y-m-d' , strtotime($this->birthday)),
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

            $student->passport_issued_date = date("Y-m-d" , strtotime($passport['expiration_date']));
            $student->passport_given_date = date("Y-m-d" , strtotime($passport['given_date']));
            $student->passport_given_by = $passport['given_place'];
            $student->birthday = $this->birthday;
            $student->gender = $passport['gender'];
            if (!$student->validate()){
                $errors[] = $this->simple_errors($student->errors);
            } else {
                $student->save(false);
                $user = $student->user;
                if ($user->step != 5) {
                    $user->step = 2;
                    $user->save(false);
                }
                if ($student->lead_id != null) {
                    $result = Passport::updateCrm($student);
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
        } else {
            $errors[] = ['Ma\'lumotlarni olishda xatolik yuz berdi.'];
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

            if ($student->user->step != 5) {
                $updatedFields = [
                    'pipelineId' => $student->pipeline_id,
                    'statusId' => User::STEP_STATUS_3
                ];
            } else {
                $updatedFields = [
                    'pipelineId' => $student->pipeline_id
                ];
            }

            $customFields = [
                '900679' => $student->last_name, // Familya
                '900681' => $student->first_name, // Ism
                '900683' => $student->middle_name,  // Otasi
                '900749' => $student->passport_serial,  // pas seriya
                '900751' => $student->passport_number, // pas raqam
                '900757' => $student->birthday, // Tug'ilgan sana
            ];

            $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            return ['is_ok' => true, 'data' => $updatedLead];
        } catch (\Exception $e) {
            $errors[] = ['Ma\'lumot uzatishda xatolik STEP 2: ' . $e->getMessage()];
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

}
