<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "consulting_branch".
 *
 * @property int $id
 * @property int|null $branch_id
 * @property int|null $consulting_id
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Branch $branch
 * @property Consulting $consulting
 */
class ConsultingBranch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consulting_branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'consulting_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['branch_id' => 'id']],
            [['consulting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Consulting::class, 'targetAttribute' => ['consulting_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
            'consulting_id' => Yii::t('app', 'Consulting ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branch_id']);
    }

    /**
     * Gets query for [[Consulting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsulting()
    {
        return $this->hasOne(Consulting::class, ['id' => 'consulting_id']);
    }
}
