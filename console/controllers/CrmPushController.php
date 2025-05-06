<?php

namespace console\controllers;

use common\components\AmoCrmClient;
use common\models\AuthAssignment;
use common\models\CrmPush;
use common\models\Direction;
use common\models\DirectionSubject;
use common\models\Drift;
use common\models\DriftCourse;
use common\models\DriftForm;
use common\models\Exam;
use common\models\ExamSubject;
use common\models\Message;
use common\models\Options;
use common\models\Questions;
use common\models\SendMessage;
use common\models\Std;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentGroup;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use common\models\User;
use Yii;
use yii\console\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\httpclient\Client;
use yii\web\Request;

class CrmPushController extends Controller
{
    public function actionPush()
    {
        $transaction = Yii::$app->db->beginTransaction();

        $query = CrmPush::find()
            ->where(['status' => 0, 'is_deleted' => 0])
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

        $transaction->commit();
        echo "MK! pushed to Crm \n";
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
            $amoCrmClient = Yii::$app->ikAmoCrm;
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
            $amoCrmClient = Yii::$app->ikAmoCrm;
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
}
