<?php

namespace backend\controllers;

use common\models\Options;
use common\models\OptionsSearch;
use common\models\Questions;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * OptionsController implements the CRUD actions for Options model.
 */
class OptionsController extends Controller
{
    use ActionTrait;

    /**
     * Lists all Options models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OptionsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Options model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $question = $this->questionFindModel($id);

        $model = new Options();
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $model->question_id = $question->id;
                $model->subject_id = $question->subject_id;
                $result = Options::createItem($model, $post);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['questions/view', 'id' => $model->question_id]);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                    $model->loadDefaultValues();
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $model->question_id = $question->id;
        $model->subject_id = $question->subject_id;

        return $this->render('create', [
            'model' => $model,
            'question' => $question
        ]);
    }

    /**
     * Updates an existing Options model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $model->question_id = $oldModel->question_id;
                $result = Options::updateItem($model, $post, $oldModel);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['questions/view', 'id' => $model->question_id]);
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
     * Deletes an existing Options model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $errors = [];
        $model = $this->findModel($id);

        $model->is_deleted = 1;
        $model->save(false);
        \Yii::$app->session->setFlash('success');

//        if ($model->question->status == 0) {
//            $model->is_deleted = 1;
//            $model->save(false);
//            \Yii::$app->session->setFlash('success');
//        } else {
//            $errors[] = ['Tasdiqlangan savolni tahrirlab bo\'lmaydi.'];
//            \Yii::$app->session->setFlash('error' , $errors);
//        }
        return $this->redirect(['questions/view' , 'id' => $model->question_id]);
    }

    public function actionImgDelete($id)
    {
        $errors = [];
        $model = $this->findModel($id);
        if ($model->question->status != 0) {
            $errors[] = ['Bu savol tasdiqlangan'];
        } else {
            if ($model->image == null) {
                $model->image = 1.0;
            }
            $fileName = \Yii::getAlias('@backend/web/uploads/options/'.$model->image);
            $model->image = null;
        }


        if (count($errors) == 0) {
            \Yii::$app->session->setFlash('success');
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            $model->save(false);
        } else {
            \Yii::$app->session->setFlash('error' , $errors);
        }
        return $this->redirect(['questions/view' , 'id' => $model->question_id]);
    }

    /**
     * Finds the Options model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Options the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Options::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function questionFindModel($id)
    {
        if (($model = Questions::findOne(['id' => $id , 'is_deleted' => 0])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException();
    }
}
