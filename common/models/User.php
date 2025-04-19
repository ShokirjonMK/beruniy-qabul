<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property integer $step
 * @property integer $cons_id
 * @property integer $target_id
 * @property string $chat_id
 * @property string $username
 * @property string $user_role
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $brithday
 * @property integer $gender
 * @property string $avatar
 * @property string $role
 * @property string $password_open
 * @property string $adress
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $telegram_step
 * @property integer $lang_id
 * @property string $lead_id
 * @property integer $step_confirm_time
 * @property AuthItem $authItem
 * @property Constalting $cons
 * @property string $employeeFullName
 * @property  $student
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_BLOCK = 5;
    const STATUS_ACTIVE = 10;

    const PIPELINE_ID = 8485710;

    const STEP_STATUS_1 = 68986510; // Toliq ro'yhatdan o'tmaganlar
    const STEP_STATUS_2 = 68986514; // Tel raqam tasdiqlaganlar
    const STEP_STATUS_3 = 68986518; // Pasport kiritganlar
    const STEP_STATUS_4 = 69026370; // Qabul turini tanlaganlar
    const STEP_STATUS_5 = 69026374; // Yonalish tanlaganlar
    const STEP_STATUS_6 = 69026378; // Ariza Tasdiqlaganlar
    const STEP_STATUS_7 = 142; // Shartnoma olganlar
    const STEP_STATUS_8 = 143;  // O'qishni istamaganlar


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    public function getStudent()
    {
        return $this->hasOne(Student::class, ['user_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'user_role' => 'student', 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByNoStudent($username)
    {
        return static::find()
            ->where(['username' => $username, 'status' => self::STATUS_ACTIVE])
            ->andWhere(['not in' , 'user_role' , 'student'])
            ->one();
    }

    public static function findUserStudent($username)
    {
        return static::findOne(['username' => $username , 'user_role' => 'student']);
    }

    public static function ikToken()
    {
        $micTime = (int) round(microtime(true) * 1000);
        $startKey = Yii::$app->security->generateRandomString(10);
        $endKey = Yii::$app->security->generateRandomString(10);
        return "Ik_Anvarovich_".$startKey.$micTime.$endKey;
    }

    public function getEmployee() {
        return $this->hasOne(Employee::class, ['user_id' => 'id']);
    }

    public function getEmployeeFullName() {
        $profile = $this->employee;
        return $profile->last_name." ".$profile->first_name." ".$profile->middle_name;
    }

    public function getCons() {
        return $this->hasOne(Consulting::class, ['id' => 'cons_id']);
    }

    public function getAuthItem() {
        return $this->hasOne(AuthItem::class, ['name' => 'user_role']);
    }


    public function getIsBranch()
    {

    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getStep()
    {
        return $this->step;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
