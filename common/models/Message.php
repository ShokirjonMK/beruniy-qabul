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

class Message extends \yii\db\ActiveRecord
{
    public static function sendSms($phone, $text)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $email = 'selfpower400@gmail.com';
        $password = 'siPadaT8ZzW9PMDLzl4j4Y2NYRHM2JN2yChMOab2';
        $url = 'http://notify.eskiz.uz/api/auth/login';
        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod("POST")
            ->setUrl($url)
            ->setData([
                'email' => $email,
                'password' => $password
            ])
            ->send();
        $data = (json_decode($response->content))->data;
        $token = $data->token;
        $from = "4546";
        $url = 'http://notify.eskiz.uz/api/message/sms/send';
        $textNew = "SARBON UNIVERSITETI qabul saytiga ro'yxatdan o'tish uchun tasdiqlash kodi: " . $text;
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod("POST")
            ->setUrl($url)
            ->addHeaders(['Authorization' => 'Bearer ' . $token])
            ->setData([
                'message' => $textNew,
                'mobile_phone' => $phone,
                'from' => $from
            ])
            ->send();
        return ($response->statusCode);
    }



    public static function sendedSms($phone, $text)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $email = 'selfpower400@gmail.com';
        $password = 'siPadaT8ZzW9PMDLzl4j4Y2NYRHM2JN2yChMOab2';
        $url = 'http://notify.eskiz.uz/api/auth/login';
        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod("POST")
            ->setUrl($url)
            ->setData([
                'email' => $email,
                'password' => $password
            ])
            ->send();
        $data = (json_decode($response->content))->data;
        $token = $data->token;
        $from = "4546";
        $url = 'http://notify.eskiz.uz/api/message/sms/send';
        $textNew = "ABU RAYHON BERUNIY UNIVERSITETI qabul saytiga ro'yxatdan o'tish uchun tasdiqlash kodi: " . $text;
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod("POST")
            ->setUrl($url)
            ->addHeaders(['Authorization' => 'Bearer ' . $token])
            ->setData([
                'message' => $textNew,
                'mobile_phone' => $phone,
                'from' => $from
            ])
            ->send();
        return ($response->statusCode);
    }


}

