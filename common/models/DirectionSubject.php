<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "direction_subject".
 *
 * @property int $id
 * @property int|null $subject_id
 * @property int|null $edu_direction_id
 * @property float|null $ball
 * @property int|null $count
 * @property int|null $status
 * @property int $type
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property EduDirection $eduDirection
 * @property Subjects $subject
 */
class DirectionSubject extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direction_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'subject_id', 'edu_direction_id', 'count', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['ball'], 'number'],
            ['type', 'in', 'range' => [0, 1]],
            [['edu_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::class, 'targetAttribute' => ['subject_id' => 'id']],
            [
                ['subject_id'],
                'unique',
                'targetClass' => DirectionSubject::class,
                'targetAttribute' => ['edu_direction_id', 'subject_id'], // Unikal kombinatsiya
                'filter' => function ($query) {
                    $query->andWhere(['is_deleted' => 0]); // Faqat is_deleted = 0 bo'lsa tekshiradi
                },
                'message' => 'Bu ma\'lumot avval qo\'shilgan.',
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
            'subject_id' => Yii::t('app', 'Subject ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'ball' => Yii::t('app', 'Ball'),
            'count' => Yii::t('app', 'Count'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
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
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subjects::class, ['id' => 'subject_id']);
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


        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

}
