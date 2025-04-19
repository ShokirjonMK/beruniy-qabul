<?php

namespace commmon\models;

use common\models\AuthAssignment;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class EmployesForm extends Model
{
    public $username;
    public $password;

    public $last_name;
    public $first_name;
    public $middle_name;
    public $gender;
    public $avatar;
    public $brithday;
    public $role;
    public $adress;
    public $password_open;

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            [['last_name' ,'first_name' , 'middle_name' , 'avatar' , 'role' , 'password_open'] , 'string' , 'max' => 255],
            [['adress' , 'brithday'] , 'safe'],
            [['gender'] , 'integer'],

            [['last_name' ,'first_name' , 'gender' , 'brithday'] , 'required']

        ];
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

        if (!$this->validate()) {
            $errors[] = ['Ma\'lumot tasdiqlanmadi.'];
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->last_name = $this->last_name;
        $user->first_name = $this->first_name;
        $user->middle_name = $this->middle_name;
        $user->gender = $this->gender;
        $user->avatar = $this->avatar;
        $user->brithday = $this->brithday;
        $user->role = $this->role;
        $user->adress = $this->adress;
        $user->password_open = $this->password;
        $user->status = User::STATUS_ACTIVE;

        if (!$user->save()) {
            $errors[] = ['Ma\'lumot saqlashda xatolik.'];
        } else {
            $newAssignment = new AuthAssignment();
            $newAssignment->item_name = $this->role;
            $newAssignment->user_id = $user->id;
            $newAssignment->created_at = time();
            $newAssignment->save(false);
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

}
