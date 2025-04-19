<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "edu_direction".
 *
 * @property int $id
 * @property int|null $branch_id
 * @property int|null $direction_id
 * @property int|null $lang_id
 * @property string|null $duration
 * @property string|null $price
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property int|null $is_oferta
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Direction $direction
 * @property EduForm $eduForm
 * @property EduType $eduType
 * @property Lang $lang
 * @property Branch $branch
 */
class EduDirection extends \yii\db\ActiveRecord
{
    public $exam;

    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edu_direction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id' ,'direction_id', 'lang_id', 'edu_type_id', 'edu_form_id', 'is_oferta', 'status', 'duration','price'], 'required'],
            [['branch_id' ,'direction_id', 'lang_id', 'edu_type_id', 'edu_form_id', 'is_oferta', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['exam_type'], 'string'],
            [['duration'], 'string', 'max' => 10],
            [['price'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [0, 1]],
            ['is_oferta', 'in', 'range' => [0, 1]],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::class, 'targetAttribute' => ['direction_id' => 'id']],
            [['edu_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduForm::class, 'targetAttribute' => ['edu_form_id' => 'id']],
            [['edu_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduType::class, 'targetAttribute' => ['edu_type_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id']],

            [
                ['direction_id'],
                'unique',
                'targetClass' => EduDirection::class,
                'targetAttribute' => ['branch_id','direction_id', 'edu_type_id', 'edu_form_id', 'lang_id'], // Unikal kombinatsiya
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
            'branch_id' => Yii::t('app', 'Filial'),
            'direction_id' => Yii::t('app', 'Direction ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'duration' => Yii::t('app', 'Duration'),
            'price' => Yii::t('app', 'Price'),
            'edu_type_id' => Yii::t('app', 'Edu Type ID'),
            'edu_form_id' => Yii::t('app', 'Edu Form ID'),
            'is_oferta' => Yii::t('app', 'Is Oferta'),
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

    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branch_id']);
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
     * Gets query for [[Lang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::class, ['id' => 'lang_id']);
    }

    public static function createItem($model , $post)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $array = [];

        if ($model->edu_type_id == 1) {
            if (isset($post['exam'])) {
                $exams = $post['exam'];
                foreach ($exams as $key => $exam) {
                    $array[] = $exam;
                }
            }
            if (count($array) == 0) {
                $errors[] = ['Imtixon turi tanlanmagan.'];
                $transaction->rollBack();
                return ['is_ok' => false, 'errors' => $errors];
            }
        }

        $model->exam_type = json_encode($array, JSON_UNESCAPED_UNICODE);

        $query = EduDirection::find()
            ->where([
                'branch_id' => $model->branch_id,
                'direction_id' => $model->direction_id,
                'edu_type_id' => $model->edu_type_id,
                'edu_form_id' => $model->edu_form_id,
                'lang_id' => $model->lang_id,
                'status' => 1,
                'is_deleted' => 0
            ])
            ->andWhere(['<>' , 'id' , $model->id])
            ->exists();
        if ($query) {
            $errors[] = ['Bu ma\'lumot avval qo\'shilgan'];
        } else {
            $model->save(false);
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }
}
