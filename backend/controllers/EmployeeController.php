<?php

namespace backend\controllers;

use common\models\AuthChild;
use common\models\AuthItemChild;
use common\models\Employee;
use common\models\EmployeeSearch;
use common\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use common\models\AuthItem;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    use ActionTrait;

    /**
     * @inheritDoc
     */

    /**
     * Lists all Employee models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionAll()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->searchAll($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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

    public function actionSelf()
    {
        $errors = [];
        $user = Yii::$app->user->identity;
        $employee = Employee::findOne(['user_id' => $user->id]);
        if (!$employee) {
            $errors[] = ['Ma\'lumot topilmadi.'];
            \Yii::$app->session->setFlash('error' , $errors);
            return $this->redirect(['site/index']);
        }
        return $this->render('view', [
            'model' => $employee,
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new Employee();
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = Employee::createUser($model);
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
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = $model->user;
        $model->username = $user->username;
        $model->role = $user->user_role;

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = Employee::updateUser($model);
                if ($result['is_ok']) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                    $model->loadDefaultValues();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $result = Employee::deleteUser($model);

        if ($result['is_ok']) {
            return $this->redirect(['index']);
        } else {
            \Yii::$app->session->setFlash('error' , $result['errors']);
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        $user = Yii::$app->user->identity;
        $userRole = $user->user_role;

        $model = Employee::findOne(['id' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        if ($model->user_id == $user->id) {
            return $model;
        }

        $modelUser = $model->user;
        if ($userRole !== 'super_admin' && $modelUser->cons_id !== $user->cons_id) {
            throw new NotFoundHttpException(Yii::t('app', 'You do not have permission to view this page.'));
        }

        $isChild = AuthChild::find()
            ->where(['parent' => $userRole, 'child' => $modelUser->user_role])
            ->exists();

        if (!$isChild) {
            throw new NotFoundHttpException(Yii::t('app', 'You do not have permission to view this page.'));
        }

        return $model;
    }

}
