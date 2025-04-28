<?php

namespace frontend\models;

use common\models\AuthAssignment;
use common\models\Branch;
use common\models\Consulting;
use common\models\CrmPush;
use common\models\Message;
use common\models\Student;
use common\models\Target;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $reset_password;
    public $password;
    public $filial_id;


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
                'message' => 'Bu telefon raqam avval ro\'yhatdan o\'tgan.',
                'when' => function ($model) {
                    $user = User::findOne(['username' => $model->username]);
                    return $user && $user->status == 10;
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
            ['filial_id' , 'required'],
            ['filial_id' , 'integer'],
            [['filial_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Branch::class,
                'targetAttribute' => ['filial_id' => 'id'],
                'filter' => ['status' => 1, 'is_deleted' => 0],
                'message' => 'Tanlangan filial mavjud emas.'
            ],
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
    public function signup()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $sms = false;

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $user = User::findUserStudent($this->username);
        if ($user) {
            if ($user->status == 10) {
                $errors[] = ['Telefon nomer avval ro\'yhatdan o\'tgan.'];
            } elseif ($user->status == 9) {

                $user->setPassword($this->password);
                $user->generateAuthKey();
                $user->generateEmailVerificationToken();
                $user->generatePasswordResetToken();

                if ($user->sms_time <= time()) {
                    $user->sms_time = strtotime('+2 minutes', time());
                    $user->sms_number = rand(100000, 999999);
                    $sms = true;
                }

                $user->new_key = User::ikToken();
                $user->update(false);

                $student = $user->student;
                $student->username = $this->username;
                $student->password = $this->password;
                $student->branch_id = $this->filial_id;
                $student->update(false);
            } else {
                $errors[] = ['Raqamingiz tizim tomonidan blocklangan.'];
            }
        } else {
            $user = new User();
            $user->username = $this->username;
            $user->user_role = 'student';

            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->generatePasswordResetToken();

            $user->new_key = User::ikToken();
            $user->sms_time = strtotime('+2 minutes', time());
            $user->sms_number = rand(100000, 999999);
            $user->status = 9;
            $sms = true;

            $branch = Branch::findOne([
                'id' => $this->filial_id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if (!$branch) {
                $errors[] = ['Filial ma\'lumotida xatolik.'];
            } else {
                $cons = $branch->cons;
                $domen = $_SERVER['HTTP_HOST'];

                if ($cons->domen == $domen) {
                    $user->cons_id = $cons->id;
                } else {
                    $consulting = Consulting::findOne([
                        'domen' => $domen,
                        'status' => 1,
                        'is_deleted' => 0
                    ]);
                    if ($consulting) {
                        $user->cons_id = $consulting->id;
                    } else {
                        $user->cons_id = $cons->id;
                    }
                }
            }

            if ($user->save(false)) {
                $newAuth = new AuthAssignment();
                $newAuth->item_name = 'student';
                $newAuth->user_id = $user->id;
                $newAuth->created_at = time();
                $newAuth->save(false);

                $newStudent = new Student();
                $newStudent->user_id = $user->id;
                $newStudent->username = $user->username;
                $newStudent->password = $this->password;
                $newStudent->branch_id = $this->filial_id;
                $newStudent->created_by = 0;
                $newStudent->updated_by = 0;
                $newStudent->save(false);

                $amo = CrmPush::processType(1, $newStudent, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }
            } else {
                $errors[] = ['Student not saved.'];
            }
        }

        if (count($errors) == 0) {
            if ($sms) {
                Message::sendSms($user->username, $user->sms_number);
            }
            $transaction->commit();
            return ['is_ok' => true , 'user' => $user];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendCrm($user , $domen)
    {
        $errors = [];
        try {
            $amoCrmClient = Yii::$app->ikAmoCrm;

            $normalizedPhoneNumber = preg_replace('/[^\d+]/', '', $user->username);

            $leadName = $normalizedPhoneNumber;
            $message = '';
            $tags = ['@ikbol_2001'];
            $pipelineId = User::PIPELINE_ID;
            $statusId = User::STEP_STATUS_1;
            $leadPrice = 0;
            $customFields = [
                '900675' => $leadName
            ];

            $phoneNumber = $normalizedPhoneNumber;

            $newLead = $amoCrmClient->addLeadToPipeline(
                $phoneNumber,
                $leadName,
                $message,
                $tags,
                $customFields,
                $pipelineId,
                $statusId,
                $leadPrice
            );

            return ['is_ok' => true , 'data' => $newLead];
        } catch (\Exception $e) {
            return ['is_ok' => false, 'errors' => ['Ma\'lumot uzatishda xatolik: ' . $e->getMessage()]];
        }
    }
}
