<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_child".
 *
 * @property int $id
 * @property string $parent
 * @property string $child
 * @property int|null $order
 * @property int|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 */
class AuthChild extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'child', 'created_at', 'updated_at'], 'required'],
            [['order', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['parent', 'child'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent' => Yii::t('app', 'Parent'),
            'child' => Yii::t('app', 'Child'),
            'order' => Yii::t('app', 'Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }
}
