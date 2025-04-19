<?php

namespace app\components;

use yii\base\Behavior;
use yii\web\Application;

class Lang extends Behavior
{
    public function events()
    {
        return [Application::EVENT_BEFORE_REQUEST => 'language'];
    }

    public function language()
    {
        if (\Yii::$app->session->has('lang')) {
            \Yii::$app->language = \Yii::$app->session->get('lang');
        }
    }

}
