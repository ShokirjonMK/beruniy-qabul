<?php

namespace frontend\controllers;

use common\models\Bot;
use yii\web\Controller;
use Yii;


/**
 * Ik Bot controller
 */
class IkBotController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionCons()
    {
        // Xamkor yozib ketiladi, eslab qolish uchun;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $telegram = Yii::$app->telegram;

        Bot::telegram($telegram);
    }

    public function actionCons2()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $telegram = Yii::$app->telegram2;

        // Foydalanuvchi Telegram ID sini olish:
        $telegramUpdate = $telegram->getWebhookUpdate();
        $chatId = $telegramUpdate->getMessage()->getChat()->getId();

        return $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Ro'yhatdan o'tish uchun quyidagi tugmani bosing:",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        [
                            'text' => "Ro'yhatdan o'tish",
                            'web_app' => [
                                'url' => 'https://arbu-edu.uz'
                            ]
                        ]
                    ]
                ]
            ])
        ]);
    }


}
