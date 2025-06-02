<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "telegram".
 *
 * @property int $id
 * @property string|null $telegram_id
 * @property string|null $phone
 * @property string|null $username
 * @property int|null $step
 * @property int|null $lang_id
 * @property string|null $birthday
 * @property string|null $passport_number
 * @property string|null $passport_serial
 * @property string|null $passport_pin
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property int|null $edu_lang_id
 * @property int|null $edu_direction_id
 * @property int|null $direction_course_id
 * @property int|null $exam_type
 * @property int|null $branch_id
 * @property int|null $exam_date_id
 * @property int|null $cons_id
 * @property string|null $oferta
 * @property string|null $tr
 * @property string|null $dtm
 * @property string|null $master
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 */
class Telegram extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_id', 'phone', 'username', 'birthday', 'passport_number', 'passport_serial', 'passport_pin', 'edu_type_id', 'edu_form_id', 'edu_lang_id', 'edu_direction_id', 'direction_course_id', 'branch_id', 'exam_date_id', 'cons_id', 'oferta', 'tr', 'dtm', 'master', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['is_deleted', 'type'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['step', 'lang_id', 'edu_type_id', 'edu_form_id', 'edu_lang_id', 'edu_direction_id', 'direction_course_id', 'exam_type', 'branch_id', 'exam_date_id', 'cons_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'type'], 'integer'],
            [['telegram_id', 'phone', 'username', 'birthday', 'passport_number', 'passport_serial', 'passport_pin', 'oferta', 'tr', 'dtm', 'master', 'first_name', 'last_name', 'middle_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'telegram_id' => Yii::t('app', 'Telegram ID'),
            'phone' => Yii::t('app', 'Phone'),
            'username' => Yii::t('app', 'Username'),
            'step' => Yii::t('app', 'Step'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'birthday' => Yii::t('app', 'Birthday'),
            'passport_number' => Yii::t('app', 'Passport Number'),
            'passport_serial' => Yii::t('app', 'Passport Serial'),
            'passport_pin' => Yii::t('app', 'Passport Pin'),
            'edu_type_id' => Yii::t('app', 'Edu Type ID'),
            'edu_form_id' => Yii::t('app', 'Edu Form ID'),
            'edu_lang_id' => Yii::t('app', 'Edu Lang ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'direction_course_id' => Yii::t('app', 'Direction Course ID'),
            'exam_type' => Yii::t('app', 'Exam Type'),
            'branch_id' => Yii::t('app', 'Branch ID'),
            'exam_date_id' => Yii::t('app', 'Exam Date ID'),
            'cons_id' => Yii::t('app', 'Cons ID'),
            'oferta' => Yii::t('app', 'Oferta'),
            'tr' => Yii::t('app', 'Tr'),
            'dtm' => Yii::t('app', 'Dtm'),
            'master' => Yii::t('app', 'Master'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }


    public function getEduDirection()
    {
        return $this->hasOne(EduDirection::class, ['id' => 'edu_direction_id']);
    }

    /**
     * Gets query for [[EduForm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEduForm()
    {
        return $this->hasOne(EduForm::class, ['id' => 'edu_form_id']);
    }

    /**
     * Gets query for [[EduType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEduType()
    {
        return $this->hasOne(EduType::class, ['id' => 'edu_type_id']);
    }

    public function getLang()
    {
        return $this->hasOne(Lang::class, ['id' => 'lang_id']);
    }

    public function getStatusName()
    {
        if ($this->step == 0) {
            $text = 'Pasport seriya va raqamini kiritmagan';
        } elseif ($this->step == 1) {
            $text = 'Tug\'ilgan sana kiritmagan';
        } elseif ($this->step == 2) {
            $text = 'Qabul turini kiritmagan';
        } elseif ($this->step == 3) {
            $text = 'Ta\'lim shaklini kiritmagan';
        } elseif ($this->step == 4) {
            $text = 'Ta\'lim tilini kiritmagan';
        } elseif ($this->step == 5) {
            $text = 'Filial kiritmagan';
        } elseif ($this->step == 6) {
            $text = 'Ta\'lim yo\'nalishini kiritmagan';
        } elseif ($this->step == 7) {
            $text = 'Imtixon turini kiritmagan';
        } elseif ($this->step == 8) {
            $text = 'Offline imtixon sanasini kiritmagan';
        } elseif ($this->step == 9) {
            $text = 'Boshqich kiritmagan';
        } elseif ($this->step == 10) {
            $text = 'Oferta kiritmagan';
        } elseif ($this->step == 11) {
            $text = 'Transkript kiritmagan';
        } elseif ($this->step == 12) {
            $text = 'DTM kiritmagan';
        } elseif ($this->step == 13) {
            $text = 'MASTER kiritmagan';
        } elseif ($this->step == 14) {
            $text = 'Ma\'lumotlarni tasdiqlamagan';
        } else {
            $text = 'Tasdiqlangan';
        }
        return $text;
    }

}
