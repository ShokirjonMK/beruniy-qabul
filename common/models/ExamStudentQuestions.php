<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exam_student_questions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $exam_id
 * @property int $exam_subject_id
 * @property int $question_id
 * @property int|null $option_id
 * @property int|null $is_correct
 * @property string $option
 * @property int|null $subject_id
 * @property int|null $order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Exam $exam
 * @property ExamSubject $examSubject
 * @property Options $option0
 * @property Questions $question
 * @property Subjects $subject
 * @property User $user
 */
class ExamStudentQuestions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_student_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'exam_id', 'exam_subject_id', 'question_id', 'option'], 'required'],
            [['user_id', 'exam_id', 'exam_subject_id', 'question_id', 'option_id', 'is_correct', 'subject_id', 'order', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['option'], 'string', 'max' => 255],
            [['exam_subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExamSubject::class, 'targetAttribute' => ['exam_subject_id' => 'id']],
            [['exam_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exam::class, 'targetAttribute' => ['exam_id' => 'id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Options::class, 'targetAttribute' => ['option_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questions::class, 'targetAttribute' => ['question_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User ID'),
            'exam_id' => Yii::t('app', 'Exam ID'),
            'exam_subject_id' => Yii::t('app', 'Exam Subject ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'option_id' => Yii::t('app', 'Option ID'),
            'is_correct' => Yii::t('app', 'Is Correct'),
            'option' => Yii::t('app', 'Option'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'order' => Yii::t('app', 'Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
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
     * Gets query for [[ExamSubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamSubject()
    {
        return $this->hasOne(ExamSubject::class, ['id' => 'exam_subject_id']);
    }

    /**
     * Gets query for [[Option0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption0()
    {
        return $this->hasOne(Options::class, ['id' => 'option_id']);
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Questions::class, ['id' => 'question_id']);
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
}
