<?php

namespace backend\controllers;

use common\models\Direction;
use common\models\Languages;
use common\models\LanguagesSearch;
use yii\base\NotSupportedException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * LanguagesController implements the CRUD actions for Languages model.
 */
class LanguagesController extends Controller
{
    use ActionTrait;

    public function actionIndex()
    {
        $searchModel = new LanguagesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Languages::findOne(['id' => $id])) !== null) {
            return $model;
        }

        \Yii::$app->session->setFlash('info', 'The requested page does not exist.');
        return $this->redirect(\Yii::$app->request->referrer);
    }
}
