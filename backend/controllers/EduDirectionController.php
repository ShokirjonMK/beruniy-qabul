<?php

namespace backend\controllers;

use common\models\EduDirection;
use common\models\EduDirectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EduDirectionController implements the CRUD actions for EduDirection model.
 */
class EduDirectionController extends Controller
{
    use ActionTrait;

    /**
     * Lists all EduDirection models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EduDirectionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EduDirection model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EduDirection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new EduDirection();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = EduDirection::createItem($model , $post);
                if ($result['is_ok']) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                    $model->loadDefaultValues();
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EduDirection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = EduDirection::createItem($model , $post);
                if ($result['is_ok']) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                    $model->loadDefaultValues();
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EduDirection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->update(false);
        return $this->redirect(['index']);
    }

    /**
     * Finds the EduDirection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EduDirection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EduDirection::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
