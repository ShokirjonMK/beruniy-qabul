<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "direction_course".
 *
 * @property int $id
 * @property int|null $course_id
 * @property int|null $edu_direction_id
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Course $course
 * @property EduDirection $eduDirection
 */
class DirectionCourse extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direction_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'edu_direction_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            [['edu_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
            [['course_id', 'edu_direction_id'], 'unique',
                'targetAttribute' => ['course_id', 'edu_direction_id'],
                'filter' => ['is_deleted' => 0],
                'message' => 'Bu kombinatsiya allaqachon mavjud.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
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

    public static function createItem($model, $post, $eduDirection) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model->edu_direction_id = $eduDirection->id;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        // Modelni saqlash
        if (!$model->save(false)) {
            $errors[] = ['Model saqlashda xatolik yuz berdi.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        // Natijalarni tekshirish
        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }
}
