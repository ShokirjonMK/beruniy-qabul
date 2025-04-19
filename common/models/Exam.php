<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exam".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $student_id
 * @property int|null $edu_direction_id
 * @property int|null $direction_id
 * @property int|null $language_id
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property int|null $start_time
 * @property int|null $finish_time
 * @property float|null $ball
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 * @property float|null $contract_price
 * @property float|null $invois
 * @property int|null $down_time
 * @property int|null $confirm_date
 *
 * @property Direction $direction
 * @property EduDirection $eduDirection
 * @property EduForm $eduForm
 * @property EduType $eduType
 * @property ExamSubject[] $examSubjects
 * @property Lang $language
 * @property Student $student
 * @property User $user
 */
class Exam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'student_id', 'edu_direction_id', 'direction_id', 'language_id', 'edu_type_id', 'edu_form_id', 'start_time', 'finish_time', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['ball'], 'number'],
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
            'start_time' => Yii::t('app', 'Start Time'),
            'finish_time' => Yii::t('app', 'Finish Time'),
            'ball' => Yii::t('app', 'Ball'),
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
     * Gets query for [[ExamSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamSubjects()
    {
        return $this->hasMany(ExamSubject::class, ['exam_id' => 'id']);
    }

    public function getExamBall()
    {
        $ball = 0;
        $subjects = $this->examSubjects;
        foreach ($subjects as $subject) {
            $ball = $ball + $subject->ball;
        }
        return $ball;
    }


    public function actionFinish()
    {

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

    public function beforeSave($insert)
    {
        if ($insert) {
            $micTime = (int) round(microtime(true) * 1000);
            $this->invois = $micTime;
        }
        return parent::beforeSave($insert);
    }

    public static function change($student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model = Exam::findOne([
           'student_id' => $student->id,
           'edu_direction_id' => $student->edu_direction_id,
           'is_deleted' => 0,
           'status' => 1
        ]);
        if (!$model) {
            $errors[] = ['Imtixonga qayta ruxsat berish mumkin emas.'];
        } else {
            $subjects = $model->examSubjects;
            foreach ($subjects as $subject) {
                $subject->ball = 0;
                $subject->update(false);
            }

            $model->ball = 0;
            $model->status = 1;
            $model->down_time = null;
            $model->confirm_date = null;
            $model->save(false);

            $student = $model->student;
            $student->is_down = 0;
            $student->update(false);

            $user = $student->user;
            $amo = CrmPush::processType(6, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }
}
