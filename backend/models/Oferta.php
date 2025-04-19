<?php

namespace backend\models;

use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionCourse;
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
class Oferta extends Model
{
    public $file;
    public $fileMaxSize = 1024 * 1000 * 5;

    public function rules()
    {
        return [
            [
                [ 'file' ],
                'file',
                'extensions'=>'pdf',
                'skipOnEmpty'=>true,
                'maxSize' => $this->fileMaxSize
            ],
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

    public static function upload($model , $oferta) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        if ($oferta->file_status == 2) {
            $errors[] = ['Avval yuklangan fayl tasdiqlangan'];
        } else {
            $photoFile = UploadedFile::getInstance($model, 'file');
            if ($photoFile) {
                if (isset($photoFile->size)) {
                    $photoFolderName = '@frontend/web/uploads/'. $oferta->student_id .'/';
                    if (!file_exists(\Yii::getAlias($photoFolderName))) {
                        mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                    }
                    $photoName = $oferta->student_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                    if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                        $oferta->file = $photoName;
                        $oferta->file_status = 1;
                        $oferta->save(false);
                    }
                }
            } else {
                $errors[] = ['Fayl yuborilmadi!'];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

    public static function check($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!($model->file_status == 2 || $model->file_status == 3)) {
            $errors[] = ['Oferta statusi noto\'g\'ri yuborildi'];
        }

        if (count($errors) == 0) {
            $model->save(false);
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }


}
