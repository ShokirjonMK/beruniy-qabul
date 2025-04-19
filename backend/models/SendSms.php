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
class SendSms extends Model
{
    public $text;

    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'safe'],
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

    public function sendSms($student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $user = $student->user;
        $phone = $user->username;
        $text = $this->text;
        Message::sendedSms($phone , $text);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }

}
