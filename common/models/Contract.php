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
class Contract extends \yii\db\ActiveRecord
{
    public $price;

    public function rules()
    {
        return [
            [['price'], 'required'],
            [['price'], 'number'],
        ];
    }

    public static function crmPush($student)
    {
        $transaction = Yii::$app->db->beginTransaction();

        if ($student->is_down == 0) {
            $user = $student->user;
            $amo = CrmPush::processType(9, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        $transaction->commit();
        return ['is_ok' => true];
    }
}
