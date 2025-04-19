<?php

namespace backend\controllers;

use common\models\EduForm;
use common\models\EduFormSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * EduFormController implements the CRUD actions for EduForm model.
 */
class EduFormController extends Controller
{
    use ActionTrait;

    /**
     * Lists all EduForm models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EduFormSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EduForm model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Creates a new EduForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new EduForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save(false)) {
                \Yii::$app->session->setFlash('success');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EduForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save(false)) {
            \Yii::$app->session->setFlash('success');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the EduForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EduForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EduForm::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
