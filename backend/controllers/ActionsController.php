<?php

namespace backend\controllers;


use common\models\Actions;
use common\models\ActionsSearch;
use common\models\AuthItem;
use common\models\Menu;
use common\models\Permission;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActionsController implements the CRUD actions for Actions model.
 */
class ActionsController extends Controller
{
    use ActionTrait;

    public function actionIndex()
    {
        $searchModel = new ActionsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionPermission($role) {
        $query = AuthItem::findOne([
            'name' => $role
        ]);
        if (!isset($query)) {

        }
        $model = Actions::find()
            ->where(['status' => 1])
            ->all();
        if ($this->request->isPost) {
            $post = $this->request->post();
            $result = Permission::createPermission($post , $role);
            if ($result) {
//                return $this->redirect(['permission-view' , 'role' => $role]);
            }
        }
        return $this->render('permission' , [
            'role' => $role,
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Actions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionPermissionView($role) {
        return $this->render('permission-view', [
            'role' => $role
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(\Yii::$app->request->referrer ?: ['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Actions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $result = Actions::deleteItem($model);
        if (!$result['is_ok']) {
            \Yii::$app->session->setFlash('error' , $result['errors']);
        }

        return $this->redirect(\Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Finds the Actions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Actions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Actions::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
