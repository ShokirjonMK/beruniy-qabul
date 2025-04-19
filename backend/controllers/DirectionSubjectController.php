<?php

namespace backend\controllers;

use common\models\DirectionSubject;
use common\models\DirectionSubjectSearch;
use common\models\EduDirection;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DirectionSubjectController implements the CRUD actions for DirectionSubject model.
 */
class DirectionSubjectController extends Controller
{
    use ActionTrait;

    /**
     * Lists all DirectionSubject models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $eduDirection = $this->modelEduDirection($id);

        $searchModel = new DirectionSubjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams , $eduDirection);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'eduDirection' => $eduDirection,
        ]);
    }

    /**
     * Displays a single DirectionSubject model.
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
     * Creates a new DirectionSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate($id)
    {
        $eduDirection = $this->modelEduDirection($id);
        $model = new DirectionSubject();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = DirectionSubject::createItem($model , $post , $eduDirection);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['index' , 'id' => $eduDirection->id]);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'eduDirection' => $eduDirection,
        ]);
    }

    /**
     * Updates an existing DirectionSubject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $eduDirection = $this->modelEduDirection($model->edu_direction_id);

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = DirectionSubject::createItem($model , $post, $eduDirection);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['index' , 'id' => $eduDirection->id]);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'eduDirection' => $eduDirection,
        ]);
    }

    /**
     * Deletes an existing DirectionSubject model.
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
        return $this->redirect(['index' , 'id' => $model->edu_direction_id]);
    }

    /**
     * Finds the DirectionSubject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DirectionSubject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function modelEduDirection($id)
    {
        if (($model = EduDirection::findOne(['id' => $id , 'is_deleted' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModel($id)
    {
        if (($model = DirectionSubject::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
