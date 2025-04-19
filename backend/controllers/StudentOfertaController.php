<?php

namespace backend\controllers;

use common\models\StudentOferta;
use common\models\StudentOfertaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentOfertaController implements the CRUD actions for StudentOferta model.
 */
class StudentOfertaController extends Controller
{
    use ActionTrait;

    /**
     * Lists all StudentOferta models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StudentOfertaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = StudentOferta::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
