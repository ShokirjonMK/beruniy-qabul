<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Verify extends Model
{
    public $sms_code;
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sms_code'], 'required'],
            ['sms_code', 'integer', 'min' => 100000, 'max' => 999999, 'message' => 'SMS kod 6 xonali bo\'lishi kerak'],
        ];
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 14 : 0);
        }

        return false;
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

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

    public static function confirm($user , $model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $time = time() + 5;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $smsTime = $user->sms_time;

        if ($smsTime < $time) {
            $user->new_key = User::ikToken();
            $user->sms_time = strtotime('+2 minutes', time());
            $user->sms_number = rand(100000, 999999);
            $user->update(false);
            Message::sendSms($user->username, $user->sms_number);
        } else {
            if ($user->sms_number == $model->sms_code) {
                $user->status = 10;
                $user->new_key = null;
                $user->sms_number = 0;
                $user->sms_time = 0;
                $user->step = 1;
                $user->update(false);

                $student = $user->student;

                $amo = CrmPush::processType(2, $student, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }

                Yii::$app->user->login($user,  3600 * 15);
            } else {
                $errors[] = ['SMS kod noto\'g\'ri.'];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true, 'user' => $user];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors, 'user' => $user];
        }
    }

    public static function sendSms($user) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $time = time();
        $t = false;

        $smsTime = $user->sms_time;

        if ($smsTime < $time) {
            $user->new_key = User::ikToken();
            $user->sms_time = strtotime('+2 minutes', time());
            $user->sms_number = rand(100000, 999999);
            $user->save(false);
            $t = true;
        } else {
            $errors[] = ['SMS tasdiqlash vaqti yakunlanmagan!'];
        }

        if (count($errors) == 0) {
            if ($t) {
                Message::sendSms($user->username, $user->sms_number);
            }
            $transaction->commit();
            return ['is_ok' => true , 'user' => $user];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors , 'user' => $user];
        }
    }


    public static function password($user , $model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $time = time() + 5;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $smsTime = $user->sms_time;
        if ($smsTime < $time) {
            $user->new_key = User::ikToken();
            $user->sms_time = strtotime('+2 minutes', time());
            $user->sms_number = rand(100000, 999999);
            $user->save(false);
            Message::sendSms($user->username, $user->sms_number);
        } else {
            if ($user->sms_number == $model->sms_code) {
                $user->setPassword($user->new_password);
                $user->generateAuthKey();
                $user->generateEmailVerificationToken();
                $user->generatePasswordResetToken();
                $user->new_key = null;
                $user->sms_number = 0;
                $user->sms_time = 0;
                $user->new_password = null;
                $user->save(false);

                $student = $user->student;
                $student->password = $user->new_password;
                $student->save(false);
                Yii::$app->user->login($user,  3600 * 15);
            } else {
                $errors[] = ['SMS kod noto\'g\'ri.'];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true , 'user' => $user];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors, 'user' => $user];
        }
    }


    public static function updateCrm($student)
    {
        try {
            $amoCrmClient = Yii::$app->ikAmoCrm;
            $leadId = $student->lead_id;
            $tags = [];
            $customFields = [];
            $message = '';

            $updatedFields = [
                'pipelineId' => $student->pipeline_id,
                'statusId' => User::STEP_STATUS_2
            ];

            $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            return ['is_ok' => true, 'data' => $updatedLead];
        } catch (\Exception $e) {
            $errors[] = ['Ma\'lumot uzatishda xatolik STEP 2: ' . $e->getMessage()];
            return ['is_ok' => false, 'errors' => $errors];
        }
    }
}
