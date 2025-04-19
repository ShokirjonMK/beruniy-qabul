<?php

namespace backend\controllers;

use common\models\ExamSubject;
use common\models\ExamSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExamSubjectController implements the CRUD actions for ExamSubject model.
 */
class ExamSubjectController extends Controller
{
    use ActionTrait;

    public function actionIndex()
    {
        $searchModel = new ExamSubjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = ExamSubject::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
