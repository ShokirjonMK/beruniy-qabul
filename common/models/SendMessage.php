<?php

namespace common\models;

use Yii;
use DateTime;
use DateTimeZone;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property int $kimdan
 * @property int $kimga
 * @property string $title
 * @property int $status
 * @property int $date
 */

class SendMessage extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'send_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['status', 'push_time'], 'integer'],
            [['phone'], 'string', 'max' => 255],
        ];
    }
}

