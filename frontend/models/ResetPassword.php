<?php

namespace frontend\models;

use common\models\AuthAssignment;
use common\models\Message;
use common\models\Student;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class ResetPassword extends Model
{
    public $username;
    public $reset_password;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique',
                'targetClass' => User::class,
                'message' => 'Bu telefon raqam avval ro\'yhatdan o\'tmagan.',
                'when' => function ($model) {
                    $user = User::findOne(['username' => $model->username]);
                    return $user && $user->status != 10;
                }
            ],
            [
                'username',
                'match',
                'pattern' => '/^[+][0-9]{3} [(][0-9]{2}[)] [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
                'message' => 'Telefon raqamni to\'liq kiriting',
            ],

            [['password'], 'required'],
            [['reset_password'], 'required'],
            [['password' , 'reset_password'], 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['reset_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Parollar bir xil bo\'lishi kerak.'],
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

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function reset()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $t = false;

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $user = User::findUserStudent($this->username);

        if ($user) {
            if ($user->status == 10) {
                if ($user->sms_time <= time()) {
                    $t = true;
                    $user->sms_time = strtotime('+2 minutes', time());
                    $user->sms_number = rand(100000, 999999);
                }
                $user->new_password = $this->password;
                $user->new_key = User::ikToken();
                $user->update(false);
            } else {
                $errors[] = ['Telefon nomer avval ro\'yhatdan o\'tmagan.'];
            }
        } else {
            $errors[] = ['Telefon nomer avval ro\'yhatdan o\'tmagan.'];
        }

        if (count($errors) == 0) {
            if ($t) {
                Message::sendSms($user->username, $user->sms_number);
            }
            $transaction->commit();
            return ['is_ok' => true , 'user' => $user];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
