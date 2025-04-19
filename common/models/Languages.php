<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "languages".
 *
 * @property int $id
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
 * @property Subjects[] $subjects
 */
class Languages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'languages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_uz', 'name_en', 'name_ru'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['name_uz', 'name_en', 'name_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subjects::class, ['language_id' => 'id']);
    }
}
