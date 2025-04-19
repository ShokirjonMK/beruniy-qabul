<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property int $id
 * @property string|null $name_uz
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $rector_uz
 * @property string|null $rector_ru
 * @property string|null $rector_en
 * @property string|null $telegram
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $address_uz
 * @property string|null $address_ru
 * @property string|null $address_en
 * @property string|null $location
 * @property string|null $tel1
 * @property string|null $tel2
 * @property int|null $status
 * @property int|null $cons_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Consulting $cons
 */
class Branch extends \yii\db\ActiveRecord
{
    use ResourceTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'rector_uz', 'name_uz', 'name_ru', 'name_en','address_uz', 'address_ru', 'address_en','tel1',] , 'required'],
            [['location'], 'string'],
            [['tel1', 'tel2'], 'match',
                'pattern' => '/^[+][0-9]{3} [(][0-9]{2}[)] [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
                'message' => 'Telefon raqamni to\'g\'ri formatda kiriting.'
            ],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted' , 'cons_id'], 'integer'],
            [['rector_en', 'rector_ru' , 'rector_uz' ,'name_uz', 'name_ru', 'name_en', 'telegram', 'instagram', 'facebook', 'address_uz', 'address_ru', 'address_en', 'tel1', 'tel2'], 'string', 'max' => 255],
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
            'name_ru' => Yii::t('app', 'Name Ru'),
            'name_en' => Yii::t('app', 'Name En'),
            'telegram' => Yii::t('app', 'Telegram'),
            'instagram' => Yii::t('app', 'Instagram'),
            'facebook' => Yii::t('app', 'Facebook'),
            'address_uz' => Yii::t('app', 'Address Uz'),
            'address_ru' => Yii::t('app', 'Address Ru'),
            'address_en' => Yii::t('app', 'Address En'),
            'location' => Yii::t('app', 'Location'),
            'tel1' => Yii::t('app', 'Tel1'),
            'tel2' => Yii::t('app', 'Tel2'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    public function getCons()
    {
        return $this->hasOne(Consulting::class, ['id' => 'cons_id']);
    }
}
