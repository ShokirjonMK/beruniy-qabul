<?php

namespace backend\models;

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
class UserUpdate extends Model
{
    public $password;
    public $status;

    public function rules()
    {
        return [
            [['password', 'status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [9, 10 , 0], 'message' => 'Status faqat 9 yoki 10 bo\'lishi kerak'],
            [['password'], 'string', 'min' => 6, 'max' => 20, 'tooShort' => 'Parol minimum 6 xonali bo\'lishi kerak', 'tooLong' => 'Parol maksimal 20 xonali bo\'lishi kerak'],
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

        $user = $student->user;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->generatePasswordResetToken();
        $user->new_key = null;
        $user->sms_number = 0;
        $user->sms_time = 0;
        $user->new_password = null;
        $user->status = $this->status;
        $user->save(false);

        $student->username = $user->username;
        $student->password = $this->password;
        $student->save(false);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }

}
