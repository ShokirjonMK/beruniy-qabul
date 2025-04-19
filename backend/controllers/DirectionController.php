<?php

namespace backend\controllers;

use common\models\Direction;
use common\models\DirectionSearch;
use common\models\EduDirection;
use common\models\Student;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DirectionController implements the CRUD actions for Direction model.
 */
class DirectionController extends Controller
{
    use ActionTrait;

    /**
     * Lists all Direction models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DirectionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDirection()
    {
        $form_id = \Yii::$app->request->post('form_id');
        $lang_id = \Yii::$app->request->post('lang_id');
        $type_id = \Yii::$app->request->post('type_id');

        $directions = EduDirection::find()
            ->where([
                'edu_type_id' => $type_id,
                'edu_form_id' => $form_id,
                'lang_id' => $lang_id,
                'status' => 1,
                'is_deleted' => 0
            ])->all();

        $options = "";
        $options .= "<option value=''>Yo'nalish tanlang ...<option>";
        if (count($directions) > 0) {
            foreach ($directions as $direction) {
                $eduDirection = $direction->direction;
                $options .= "<option value='$direction->id'>". $eduDirection->code ." - ". $eduDirection['name_uz']. "</option>";
            }
        }
        return $options;
    }

    /**
     * Displays a single Direction model.
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
     * Creates a new Direction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Direction();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Direction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Direction model.
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
     * Finds the Direction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Direction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Direction::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
