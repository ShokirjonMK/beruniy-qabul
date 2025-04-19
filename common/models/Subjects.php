<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subjects".
 *
 * @property int $id
 * @property int $language_id
 * @property string $name_uz
 * @property string $name_en
 * @property string $name_ru
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property Languages $language
 * @property Options[] $options
 * @property Questions[] $questions
 */
class Subjects extends \yii\db\ActiveRecord
{
    use ResourceTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subjects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['language_id', 'name_uz', 'name_en', 'name_ru'], 'required'],
            [['language_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['name_uz', 'name_en', 'name_ru'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::class, 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ru' => Yii::t('app', 'Name Ru'),

            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Kiritgan:'),
            'updated_at' => Yii::t('app', 'Tahrirlagan:'),
            'created_by' => Yii::t('app', 'Kiritgan:'),
            'updated_by' => Yii::t('app', 'Tahrirlagan:'),
            'is_deleted' => Yii::t('app', 'O\'chirilgan'),
        ];
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
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Options::class, ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Questions::class, ['subject_id' => 'id']);
    }
}
