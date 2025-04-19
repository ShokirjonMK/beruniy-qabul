<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exam_subject".
 *
 * @property int $id
 * @property int|null $exam_id
 * @property int|null $user_id
 * @property int|null $student_id
 * @property int|null $edu_direction_id
 * @property int|null $direction_id
 * @property int|null $language_id
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property int|null $direction_subject_id
 * @property int|null $subject_id
 * @property float|null $ball
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
 * @property DirectionSubject $directionSubject
 * @property EduDirection $eduDirection
 * @property EduForm $eduForm
 * @property EduType $eduType
 * @property Exam $exam
 * @property Lang $language
 * @property Student $student
 * @property Subjects $subject
 * @property ExamStudentQuestions $studentQuestions
 * @property User $user
 */
class ExamSubject extends \yii\db\ActiveRecord
{
    public $file_pdf;
    public $add_ball;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_id', 'user_id', 'student_id', 'edu_direction_id', 'direction_id', 'language_id', 'edu_type_id', 'edu_form_id', 'direction_subject_id', 'subject_id', 'file_status', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'add_ball'], 'integer'],
            [['ball'], 'number'],
            [['file'], 'string', 'max' => 255],
            [['direction_subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => DirectionSubject::class, 'targetAttribute' => ['direction_subject_id' => 'id']],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::class, 'targetAttribute' => ['direction_id' => 'id']],
            [['edu_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
            [['edu_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduForm::class, 'targetAttribute' => ['edu_form_id' => 'id']],
            [['edu_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduType::class, 'targetAttribute' => ['edu_type_id' => 'id']],
            [['exam_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exam::class, 'targetAttribute' => ['exam_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::class, 'targetAttribute' => ['language_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::class, 'targetAttribute' => ['subject_id' => 'id']],
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
            'exam_id' => Yii::t('app', 'Exam ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'direction_id' => Yii::t('app', 'Direction ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'edu_type_id' => Yii::t('app', 'Edu Type ID'),
            'edu_form_id' => Yii::t('app', 'Edu Form ID'),
            'direction_subject_id' => Yii::t('app', 'Direction Subject ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'ball' => Yii::t('app', 'Ball'),
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
     * Gets query for [[DirectionSubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirectionSubject()
    {
        return $this->hasOne(DirectionSubject::class, ['id' => 'direction_subject_id']);
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
     * Gets query for [[Exam]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExam()
    {
        return $this->hasOne(Exam::class, ['id' => 'exam_id']);
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
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subjects::class, ['id' => 'subject_id']);
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

    public function getStudentQuestions()
    {
        $user = Yii::$app->user->identity;
        return $this->hasMany(ExamStudentQuestions::class, ['exam_subject_id' => 'id'])
            ->where(['user_id' => $user->id , 'status' => 1, 'is_deleted' => 0]);
    }

    public static function confirm($model, $old) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $eduDirection = $model->eduDirection;

        $model->exam_id = $old->exam_id;
        $model->user_id = $old->user_id;
        $model->student_id = $old->student_id;
        $model->edu_direction_id = $old->edu_direction_id;
        $model->direction_id = $old->direction_id;
        $model->language_id = $old->language_id;
        $model->edu_type_id = $old->edu_type_id;
        $model->edu_form_id = $old->edu_form_id;
        $model->direction_subject_id = $old->direction_subject_id;
        $model->subject_id = $old->subject_id;
        $model->ball = $old->ball;
        $model->file = $old->file;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $directionSubject = $model->directionSubject;
        if ($model->file_status == 2) {
            $model->ball = $directionSubject->ball * $directionSubject->count;
        } else {
            $questions = ExamStudentQuestions::find()
                ->where([
                    'exam_subject_id' => $model->id,
                    'is_correct' => 1,
                    'status' => 1,
                    'is_deleted' => 0
                ])->count();
            $model->ball = ($questions * $directionSubject->count);
        }

        $model->save(false);

        $exam = $model->exam;
        if ($exam->status > 2) {
            $exam->ball = $exam->examBall;
            if ($exam->ball < 30) {
                $exam->status = 4;
                $exam->contract_price = null;
                $exam->confirm_date = null;
            } else {
                $exam->status = 4;
                $exam->contract_price = $eduDirection->price;
                $exam->confirm_date = time();
            }
            $exam->save(false);

            $student = $model->student;
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
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

    public static function addBall($model, $old) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $eduDirection = $model->eduDirection;
        $student = $model->student;

        $model->exam_id = $old->exam_id;
        $model->user_id = $old->user_id;
        $model->student_id = $old->student_id;
        $model->edu_direction_id = $old->edu_direction_id;
        $model->direction_id = $old->direction_id;
        $model->language_id = $old->language_id;
        $model->edu_type_id = $old->edu_type_id;
        $model->edu_form_id = $old->edu_form_id;
        $model->direction_subject_id = $old->direction_subject_id;
        $model->subject_id = $old->subject_id;
        $model->ball = $old->ball;
        $model->file = $old->file;
        $model->file_status = $old->file_status;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
        $directionSubject = $model->directionSubject;
        if ($model->add_ball < 0) {
            $model->add_ball = $model->add_ball * (-1);
        } elseif ($model->add_ball > $directionSubject->count) {
            $model->add_ball = $directionSubject->count;
        }

        if ($eduDirection->is_oferta == 1) {
            $oferta = StudentOferta::findOne([
                'student_id' => $student->id,
                'edu_direction_id' => $eduDirection->id,
                'file_status' => 2,
                'is_deleted' => 0
            ]);
            if (!$oferta) {
                $errors[] = ['Oferta tasdiqlanmagan.'];
            }
        }

        $model->ball = $model->add_ball * $directionSubject->ball;
        $model->save(false);

        $exam = $model->exam;
        $exam->status = 3;
        $exam->ball = $exam->examBall;
        if ($exam->ball < 30) {
            $exam->status = 4;
            $exam->contract_price = null;
            $exam->confirm_date = null;
        } else {
            $exam->contract_price = $eduDirection->price;
            $exam->confirm_date = time();
        }
        $exam->save(false);

        $student->is_down = 0;
        $student->update(false);

        if (count($errors) == 0) {

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
