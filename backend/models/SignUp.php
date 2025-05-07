<?php

namespace backend\models;

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
class SignUp extends Model
{
    public $username;
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
        $password = 12345678;

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
                if ($user->step == 0) {
                    $user->step = 1;
                }
                $user->setPassword($password);
                $user->generateAuthKey();
                $user->generateEmailVerificationToken();
                $user->generatePasswordResetToken();
                $user->status = 10;
                $user->update(false);

                $student = $user->student;
                $student->username = $this->username;
                $student->password = $password;
                $student->branch_id = $this->filial_id;
                $student->update(false);

                if ($user->step == 1) {
                    $amo = CrmPush::processType(2, $student, $user);
                    if (!$amo['is_ok']) {
                        $transaction->rollBack();
                        return ['is_ok' => false , 'errors' => $amo['errors']];
                    }
                }
            } else {
                $errors[] = ['Raqamingiz tizim tomonidan blocklangan.'];
            }
        } else {
            $user = new User();
            $user->username = $this->username;
            $user->user_role = 'student';

            $user->setPassword($password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->generatePasswordResetToken();
            $user->status = 10;
            $user->step = 1;

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

                $student = new Student();
                $student->user_id = $user->id;
                $student->username = $user->username;
                $student->password = $password;
                $student->branch_id = $this->filial_id;
                $student->created_by = 0;
                $student->updated_by = 0;
                $student->save(false);

                $amo = CrmPush::processType(2, $student, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }
            } else {
                $errors[] = ['Student not saved.'];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true , 'student' => $student];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

}
