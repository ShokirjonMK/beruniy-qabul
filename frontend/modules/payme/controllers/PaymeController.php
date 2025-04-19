<?php

namespace app\modules\payme\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Wallet;
use yii\web\Response;

/**
 * SiteAboutController implements the CRUD actions for SiteAbout model.
 */
class PaymeController extends Controller
{
    public function actionPaymeHook()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return (new Wallet(file_get_contents("php://input")))->response();
    }
}
