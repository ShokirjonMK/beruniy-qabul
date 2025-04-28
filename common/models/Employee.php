<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $image
 * @property string|null $middle_name
 * @property string $phone
 * @property int $gender
 * @property string $brithday
 * @property string $adress
 * @property string $password
 * @property int|null $status
 *
 * @property User $user
 */
class Employee extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    public $username;

    public $avatar;

    public $role;

    public $cons_id;

    public $avatarMaxSize = 1024 * 1000 * 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            // Username
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Foydalanuvchi nomi majburiy.'],
            ['username', 'string', 'min' => 5, 'max' => 20],

            ['username', 'validateUsernameUniqueness'],

            // Role
            ['role', 'required', 'message' => 'Rol tanlanishi kerak.'],
            ['role', 'string', 'message' => 'Rol matn bo\'lishi kerak.'],
            ['role', 'exist',
                'targetClass' => AuthItem::class,
                'targetAttribute' => ['role' => 'name'],
                'message' => 'Bunday rol mavjud emas.'
            ],

            // Password
            ['password', 'required', 'message' => 'Parol majburiy.'],
            ['password', 'string',
                'min' => Yii::$app->params['user.passwordMinLength'],
                'message' => 'Parol kamida {min} belgidan iborat bo\'lishi kerak.'
            ],

            // Required fields
            [['first_name', 'last_name', 'phone', 'gender', 'brithday', 'password', 'role', 'status'],
                'required', 'message' => 'Bu maydon majburiy.'
            ],

            // Integer fields
            [['user_id', 'gender', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'],
                'integer', 'message' => 'Raqamli qiymat bo\'lishi kerak.'
            ],

            // Address
            ['adress', 'string', 'max' => 500, 'message' => 'Manzil maksimal 500 belgidan iborat bo\'lishi kerak.'],

            // Phone
            ['phone', 'match',
                'pattern' => '/^[+][0-9]{3} [(][0-9]{2}[)] [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
                'message' => 'Telefon raqamni to\'g\'ri formatda kiriting.'
            ],

            // Name fields
            [['first_name', 'last_name', 'middle_name'],
                'string', 'max' => 255, 'message' => 'Maydon maksimal 255 belgidan iborat bo\'lishi kerak.'
            ],

            // Birthdate
            ['brithday', 'string', 'max' => 50, 'message' => 'Tug\'ilgan sana maksimal 50 belgidan iborat bo\'lishi kerak.'],

            // Foreign key validations
            ['user_id', 'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
                'skipOnError' => true,
                'message' => 'Foydalanuvchi topilmadi.'
            ],
            ['cons_id', 'exist',
                'targetClass' => Consulting::class,
                'targetAttribute' => ['cons_id' => 'id'],
                'skipOnError' => true,
                'message' => 'Konsultatsiya topilmadi.'
            ],
        ];
    }

    public function validateUsernameUniqueness($attribute, $params)
    {
        $query = User::find()->where(['username' => $this->$attribute]);

        if ($this->user_id) {
            // O‘zidan tashqari boshqa username bo‘lishini tekshiradi
            $query->andWhere(['<>', 'id', $this->user_id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, 'Bu username avval ro\'yhatdan o\'tgan.');
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'first_name' => Yii::t('app', 'Ism'),
            'last_name' => Yii::t('app', 'Familya'),
            'middle_name' => Yii::t('app', 'Otasi'),
            'phone' => Yii::t('app', 'Telefon raqam'),
            'gender' => Yii::t('app', 'Jinsi'),
            'brithday' => Yii::t('app', 'Tug\'ilgan sana'),
            'adress' => Yii::t('app', 'Adress'),
            'password' => Yii::t('app', 'Parol'),
            'username' => Yii::t('app', 'Login'),
            'role' => Yii::t('app', 'Rol'),
            'cons_id' => Yii::t('app', 'Xamkor'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Tizimga qo\'shilgan vaqt'),
            'updated_at' => Yii::t('app', 'Mal. o\'zgargan vaqt'),
            'created_by' => Yii::t('app', 'Kim tomonidan qo\'shilgan'),
            'updated_by' => Yii::t('app', 'Kim tomonidan o\'zgartirilgan'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function createUser($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $currentUser = Yii::$app->user->identity;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $user = new User();
        $user->username = $model->username;
        $user->user_role = $model->role;
        $user->status = $model->status;
        $user->cons_id = $currentUser->cons_id;
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->setPassword($model->password);

        if (!$user->save(false)) {
            $errors[] = ['User not saved.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $newAuth = new AuthAssignment([
            'item_name' => $model->role,
            'user_id' => $user->id,
            'created_at' => time(),
        ]);
        if (!$newAuth->save(false)) {
            $errors[] = ['AuthAssignment not saved.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }


        $model->user_id = $user->id; // Employee modelga `user_id` ni bog'lash
        if (!$model->save(false)) {
            $errors[] = ['Employee not saved.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $transaction->commit();
        return ['is_ok' => true];
    }

    public static function updateUser($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $currentUser = Yii::$app->user->identity;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $user = $model->user;
        $user->setAttributes([
            'username' => $model->username,
            'user_role' => $model->role,
            'status' => $model->status,
            'cons_id' => $currentUser->cons_id,
        ]);
        $user->setPassword($model->password);

        if (!$user->save(false)) {
            $errors[] = ['User not saved.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        AuthAssignment::deleteAll(['user_id' => $user->id]);

        $newAuth = new AuthAssignment([
            'item_name' => $model->role,
            'user_id' => $user->id,
            'created_at' => time(),
        ]);
        if (!$newAuth->save(false)) {
            $errors[] = ['AuthAssignment not saved.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        if (!$model->save(false)) {
            $errors[] = ['Employee not saved.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $transaction->commit();
        return ['is_ok' => true];
    }


    public static function deleteUser($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        try {
            // Foydalanuvchi holatini yangilash
            $user = $model->user;
            if ($user) {
                $user->status = 0;
                if (!$user->update(false)) {
                    $errors[] = ['Foydalanuvchi statusini yangilashda xatolik yuz berdi.'];
                }
            } else {
                $errors[] = ['Foydalanuvchi topilmadi.'];
            }

            // Model holatini yangilash
            $model->status = 0;
            if (!$model->update(false)) {
                $errors[] = ['Modelni yangilashda xatolik yuz berdi.'];
            }

            if (count($errors) == 0) {
                $transaction->commit();
                return ['is_ok' => true];
            } else {
                $transaction->rollBack();
                return ['is_ok' => false, 'errors' => $errors];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => [['Xatolik yuz berdi: ' . $e->getMessage()]]];
        }
    }


    public function consArray()
    {
        $model = new Consulting();

        $query = $model->find()
            ->select('id')
            ->andWhere(['status' => 1, 'is_deleted' => 0]);
        if (isRole('super_admin')) {

        }

        if (isRole('admin')) {

        }

        if (isRole('branch_admin')) {

        }

        if (isRole('branch_moderator')) {

        }

        if (isRole('cons_admin')) {

        }

        if (isRole('cons_moderator')) {

        }

        if (isRole('moderator')) {

        }

        $query->asArray()->all();

    }


    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_by = Yii::$app->user->identity->id;
        } else {
            $this->updated_by = Yii::$app->user->identity->id;
        }
        return parent::beforeSave($insert);
    }

}
