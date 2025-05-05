<?php

namespace backend\controllers;

use backend\models\UserUpdate;
use common\components\AmoCrmClient;
use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\AuthItemSearch;
use common\models\CrmPush;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\DirectionSubject;
use common\models\EduDirection;
use common\models\Employee;
use common\models\Exam;
use common\models\ExamDate;
use common\models\ExamStudentQuestions;
use common\models\ExamSubject;
use common\models\IkIp;
use common\models\Questions;
use common\models\Status;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentMaster;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
{
    use ActionTrait;

    /**
     * Lists all AuthItem models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = CrmPush::find()
            ->where(['status' => 0, 'student_id' => 603])
            ->andWhere([
                'or',
                ['and', ['type' => 1], ['lead_id' => null]],  // type 1 uchun lead_id null bo'lishi kerak
                ['and', ['<>', 'type', 1], ['is not', 'lead_id', null]]  // boshqalar uchun lead_id null emas
            ])
            ->orderBy('id asc')
            ->limit(6)
            ->all();

        if (!empty($query)) {
            foreach ($query as $item) {
                if ($item->type == 1) {
                    $result = self::createItem($item);
                } else {
                    $result = self::updateItem($item);
                }
                if ($result !== null && $result['is_ok']) {
                    $amo = $result['data'];
                    $item->status = 1;
                    if ($item->type == 1) {
                        $item->lead_id = $amo->id;
                        $student = Student::findOne($item->student_id);
                        $user = $student->user;
                        CrmPush::updateAll(['lead_id' => $amo->id], ['student_id' => $item->student_id]);
                        $user->lead_id = $item->lead_id;
                        $user->save(false);
                    }
                } else {
                    $item->is_deleted = 1;
                }
                $item->push_time = time();
                $item->save(false);
            }
        }

        $searchModel = new AuthItemSearch();
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
            $tags = ['arbu-edu.uz'];
            $pipelineId = AmoCrmClient::DEFAULT_PIPELINE_ID;
            $statusId = $model->lead_status;
            $leadPrice = 0;

            $customFields = [];
            $jsonData = json_decode($model->data, true);
            foreach ($jsonData as $key => $value) {
                $customFields[$key] = (string)$value;
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
                    if ($key == CrmPush::TEL) {
                        $updatedFields['name'] = (string)$value;
                    }
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
     * Displays a single AuthItem model.
     * @param string $name Name
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name)
    {
        return $this->render('view', [
            'model' => $this->findModel($name),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AuthItem();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = AuthItem::createItem($model , $post);
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

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name Name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = AuthItem::updateItem($model , $post);
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
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name Name
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = AuthItem::findOne(['name' => $name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
