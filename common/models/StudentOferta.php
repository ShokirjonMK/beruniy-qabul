<?php

namespace common\models;

use common\models\Direction;
use common\models\EduDirection;
use common\models\EduForm;
use common\models\EduType;
use common\models\Lang;
use common\models\Student;
use common\models\User;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "student_oferta".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $student_id
 * @property int|null $edu_direction_id
 * @property int|null $direction_id
 * @property int|null $language_id
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property string|null $file
 * @property int|null $file_status
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Direction $direction
 * @property EduDirection $eduDirection
 * @property EduForm $eduForm
 * @property EduType $eduType
 * @property Lang $language
 * @property Student $student
 * @property User $user
 */
class StudentOferta extends \yii\db\ActiveRecord
{
    public $file_pdf;
    public $fileMaxSize = 1024 * 1000 * 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_oferta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'student_id', 'edu_direction_id', 'direction_id', 'language_id', 'edu_type_id', 'edu_form_id', 'file_status', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['file'], 'string', 'max' => 255],
            [
                ['file_pdf'],
                'file',
                'extensions' => 'pdf',
                'skipOnEmpty' => true,
                'maxSize' => $this->fileMaxSize
            ],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::class, 'targetAttribute' => ['direction_id' => 'id']],
            [['edu_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
            [['edu_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduForm::class, 'targetAttribute' => ['edu_form_id' => 'id']],
            [['edu_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduType::class, 'targetAttribute' => ['edu_type_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::class, 'targetAttribute' => ['language_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'direction_id' => Yii::t('app', 'Direction ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'edu_type_id' => Yii::t('app', 'Edu Type ID'),
            'edu_form_id' => Yii::t('app', 'Edu Form ID'),
            'file' => Yii::t('app', 'File'),
            'file_status' => Yii::t('app', 'File Status'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Direction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(Direction::class, ['id' => 'direction_id']);
    }

    /**
     * Gets query for [[EduDirection]].
     *
     * @return \yii\db\ActiveQuery
     */
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

    /**
     * Gets query for [[Language]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Lang::class, ['id' => 'language_id']);
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
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

    public static function upload($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        if ($model->file_status == 2) {
            $errors[] = ['Avval yuklangan fayl tasdiqlangan'];
        } else {
            $photoFile = UploadedFile::getInstance($model, 'file_pdf');
            if ($photoFile) {
                if (isset($photoFile->size)) {
                    $photoFolderName = '@frontend/web/uploads/'. $model->student_id .'/';
                    if (!file_exists(\Yii::getAlias($photoFolderName))) {
                        mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                    }
                    $photoName = $model->student_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                    if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                        $model->file = $photoName;
                        $model->file_status = 1;
                        $model->save(false);
                    }
                }
            } else {
                $errors[] = ['Fayl yuborilmadi!'];
            }
        }

        if (count($errors) == 0) {
            $student = $model->student;
            $user = $student->user;
            if ($user->step == 5) {
                $amo = CrmPush::processType(6, $student, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }
            }
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

    public static function confirm($model, $old) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model->user_id = $old->user_id;
        $model->student_id = $old->student_id;
        $model->edu_direction_id = $old->edu_direction_id;
        $model->direction_id = $old->direction_id;
        $model->language_id = $old->language_id;
        $model->edu_type_id = $old->edu_type_id;
        $model->edu_form_id = $old->edu_form_id;
        $model->file = $old->file;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
        $model->save(false);

        if (count($errors) == 0) {

            $student = $model->student;
            $user = $student->user;
            if ($user->step == 5) {
                $amo = CrmPush::processType(6, $student, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }
            }

            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }
}
