<?php

namespace backend\models;

use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\DirectionForm;
use common\models\DirectionLanguage;
use common\models\DirectionSubject;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\EduYearType;
use common\models\Exam;
use common\models\ExamSubject;
use common\models\Languages;
use common\models\Message;
use common\models\Student;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;
use yii\web\UploadedFile;

/**
 * Signup form
 */
class SerDel extends Model
{
    public $status;

    public function rules()
    {
        return [
            [[ 'status' ], 'integer'],
        ];
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }

    public static function upload($model , $student , $query) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if ($query->status == 2) {
            $errors[] = ['Avval yuklangan fayl tasdiqlangan'];
        } else {
            if ($query->file != null) {
                $fileName = \Yii::getAlias('@frontend/web/uploads/'. $student->id .'/'.$query->file);
                if (file_exists($fileName)) {
                    unlink($fileName);
                }
            }
            $query->file_status = 0;
            $query->file = null;
            $query->save(false);
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

}
