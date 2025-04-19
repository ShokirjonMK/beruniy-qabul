<?php

namespace backend\controllers;

use common\models\Branch;
use common\models\ExamDate;
use common\models\ExamDateSearch;
use common\models\Student;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExamDateController implements the CRUD actions for ExamDate model.
 */
class ExamDateController extends Controller
{
    use ActionTrait;

    /**
     * Lists all ExamDate models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExamDateSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExamDate model.
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
     * Creates a new ExamDate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new ExamDate();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = ExamDate::createItem($model , $post);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['index']);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDateCheck()
    {
        $result = ExamDate::dateCheck();
        if ($result['is_ok']) {
            \Yii::$app->session->setFlash('success');
            return $this->redirect(['index']);
        } else {
            \Yii::$app->session->setFlash('error' , $result['errors']);
        }
    }

    /**
     * Updates an existing ExamDate model.
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
                $result = ExamDate::createItem($model , $post);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['index']);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExamDate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $result = ExamDate::deleteItem($model);
        if ($result['is_ok']) {
            \Yii::$app->session->setFlash('success');
            return $this->redirect(['index']);
        } else {
            \Yii::$app->session->setFlash('error' , $result['errors']);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExamDate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ExamDate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExamDate::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
