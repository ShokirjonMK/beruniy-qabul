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
class Contract extends Model
{
    public $price;

    public function rules()
    {
        return [
            [['price'], 'required'],
            [['price'], 'integer', 'min' => 1, 'message' => 'Price must be a positive integer and not zero or negative.'],
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

    public function ikStep($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $user = Yii::$app->user->identity;
        if ($user->user_role != 'supper_admin') {
            $errors[] = ['Tahrirlashga ruxsatingiz yo\'q'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $model->contract_price = $this->price;
        $model->save(false);

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
