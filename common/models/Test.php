<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $full_name
 * @property string $adress
 * @property string $phone
 * @property string $brithday
 * @property string $course
 * @property string $group
 * @property int $status
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'adress', 'phone', 'brithday', 'course', 'group', 'status'], 'required'],
            [['brithday'], 'safe'],
            [['status'], 'integer'],
            [['full_name', 'course', 'group'], 'string', 'max' => 100],
            [['adress'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'adress' => 'Adress',
            'phone' => 'Phone',
            'brithday' => 'Brithday',
            'course' => 'Course',
            'group' => 'Group',
            'status' => 'Status',
        ];
    }
}
