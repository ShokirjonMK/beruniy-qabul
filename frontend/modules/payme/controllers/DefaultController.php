<?php

namespace app\modules\payme\controllers;

use yii\web\Controller;

/**
 * Default controller for the `payme` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        dd("Basic " . base64_encode(\Yii::$app->params['payme_login'].':'.\Yii::$app->params['payme_password']));
        return $this->render('index');
    }
}
