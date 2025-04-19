<?php

namespace backend\controllers;

use common\components\AmoCrmClient;
use common\models\CrmPush;
use common\models\Lang;
use common\models\LangSearch;
use common\models\Student;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class LangController extends Controller
{
    use ActionTrait;

    /**
     * Lists all Lang models.
     *
     * @return string
     */
    public function actionIndex()
    {
//        $query = CrmPush::find()
//            ->andWhere(['lead_id' => 17600633, 'type' => 9])
//            ->orderBy('id desc')
//            ->limit(1)
//            ->all();
//
//        if (!empty($query)) {
//            foreach ($query as $item) {
//                if ($item->type == 1) {
//                    $result = self::createItem($item);
//                } else {
//                    $result = self::updateItem($item);
//                }
//                if ($result !== null && $result['is_ok']) {
//                    $amo = $result['data'];
//                    $item->status = 1;
//                    if ($item->type == 1) {
//                        $item->lead_id = $amo->id;
//                        $student = Student::findOne($item->student_id);
//                        $user = $student->user;
//                        CrmPush::updateAll(['lead_id' => $amo->id], ['student_id' => $item->student_id]);
//                        $user->lead_id = $item->lead_id;
//                        $user->save(false);
//                    }
//                } else {
//                    $item->is_deleted = 1;
//                }
//                $item->push_time = time();
//                $item->save(false);
//            }
//        }


        $searchModel = new LangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public static function createItem($model)
    {
        $student = Student::findOne($model->student_id);
        if ($student) {
            $phoneNumber = preg_replace('/[^\d+]/', '', $student->username);
            $leadName = $phoneNumber;
            $message = '';
            $tags = ['zarmed'];
            $pipelineId = AmoCrmClient::DEFAULT_PIPELINE_ID;
            $statusId = $model->lead_status;
            $leadPrice = 0;

            $customFields = [];
            $jsonData = json_decode($model->data, true);
            foreach ($jsonData as $key => $value) {
                $customFields[$key] = $value;
            }

            return self::addItem($phoneNumber, $leadName, $message, $tags, $customFields, $pipelineId, $statusId, $leadPrice);
        } else {
            return ['is_ok' => false];
        }
    }

    public static function addItem($phoneNumber, $leadName, $message, $tags, $customFields, $pipelineId, $statusId, $leadPrice)
    {
        try {
            $amoCrmClient = \Yii::$app->ikAmoCrm;
            $newLead = $amoCrmClient->addLeadToPipeline(
                $phoneNumber,
                $leadName,
                $message,
                $tags,
                $customFields,
                $pipelineId,
                $statusId,
                $leadPrice
            );
            return ['is_ok' => true, 'data' => $newLead];
        } catch (\Exception $e) {
            return ['is_ok' => false];
        }
    }

    public static function updateItem($model)
    {
        try {
            $amoCrmClient = \Yii::$app->ikAmoCrm;
            $leadId = $model->lead_id;
            $tags = [];
            $message = '';
            $customFields = [];
            $updatedFields = [];

            if ($model->pipeline_id != null) {
                $updatedFields['pipelineId'] = (string)$model->pipeline_id;
            }

            if ($model->lead_status != null) {
                $updatedFields['statusId'] = $model->lead_status;
            }


            if ($model->data != null) {
                $jsonData = json_decode($model->data, true);
                foreach ($jsonData as $key => $value) {
                    $customFields[$key] = (string)$value;
                }
            }
            $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            return ['is_ok' => true, 'data' => $updatedLead];
        } catch (\Exception $e) {
            return ['is_ok' => false];
        }
    }
    /**
     * Displays a single Lang model.
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
     * Updates an existing Lang model.
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
     * Deletes an existing Lang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Lang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lang::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
