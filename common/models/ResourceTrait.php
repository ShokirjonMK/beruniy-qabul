<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\User;

trait ResourceTrait
{
    public function behaviors()
    {
        $behaviors = [];

        // Agar created_by yoki updated_by mavjud bo'lsa, BlameableBehavior qo'shing
        if ($this->hasAttribute('created_by') || $this->hasAttribute('updated_by')) {
            $behaviors[] = [
                'class' => BlameableBehavior::class,
            ];
        }

        // Agar created_at yoki updated_at mavjud bo'lsa, TimestampBehavior qo'shing
        if ($this->hasAttribute('created_at') || $this->hasAttribute('updated_at')) {
            $behaviors[] = [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ];
        }

        return $behaviors;
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
             foreach ($lev1 as $key => $error) {
                 $result[] = $error;
             }
        }
        return array_unique($result);
    }

}
