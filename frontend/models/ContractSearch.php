<?php

namespace frontend\models;

use common\models\AuthAssignment;
use common\models\Message;
use common\models\Student;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 * Signup form
 */
class ContractSearch extends Model
{
    public $pin;
    public $seria;
    public $number;

    public function rules()
    {
        return [
            [['pin', 'seria', 'number'], 'required'],

            [['seria'], 'string', 'min' => 2, 'max' => 2, 'message' => 'Pasport seria 2 xonali bo\'lishi kerak'],
            ['seria', 'match', 'pattern' => '/^[^\d]*$/', 'message' => 'Pasport seriasi faqat raqamlardan iborat bo\'lmasligi kerak'],

            [['pin'], 'string', 'min' => 14, 'max' => 14, 'message' => 'Pasport pin 14 xonali bo\'lishi kerak'],
            ['pin', 'match', 'pattern' => '/^\d{14}$/', 'message' => 'Pasport raqam faqat raqamlardan iborat bo\'lishi kerak'],

            [['number'], 'string', 'min' => 7, 'max' => 7, 'message' => 'Pasport raqam 7 xonali bo\'lishi kerak'],
            ['number', 'match', 'pattern' => '/^\d{7}$/', 'message' => 'Pasport raqam faqat raqamlardan iborat bo\'lishi kerak'],
        ];
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
