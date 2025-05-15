<?php

namespace common\models;

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
class SerCreate extends Model
{
    public $file;
    public $fileMaxSize = 1024 * 1000 * 5;

    public function rules()
    {
        return [
            [
                [ 'file' ],
                'file',
                'extensions'=>'pdf , png , jpg',
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

    public static function upload($model , $student , $examSubject) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $oldFile = $examSubject->file;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        if ($examSubject->file_status == 2) {
            $errors[] = ['Avval yuklangan fayl tasdiqlangan'];
        } else {
            $photoFile = UploadedFile::getInstance($model, 'file');
            if ($photoFile) {
                if (isset($photoFile->size)) {
                    $photoFolderName = '@frontend/web/uploads/'. $student->id .'/';
                    if (!file_exists(\Yii::getAlias($photoFolderName))) {
                        mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                    }
                    $photoName = $student->id ."_". time()."_".current_user_id()."_". \Yii::$app->security->generateRandomString(5). '.' . $photoFile->extension;
                    if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                        $examSubject->file = $photoName;
                        $examSubject->file_status = 1;
                        $examSubject->save(false);

                        $user = $student->user;
                        if ($user->step == 5) {
                            $amo = CrmPush::processType(6, $student, $user);
                            if (!$amo['is_ok']) {
                                $transaction->rollBack();
                                return ['is_ok' => false , 'errors' => $amo['errors']];
                            }
                        }

                    }
                }
            } else {
                $errors[] = ['Fayl yuborilmadi!'];
            }
        }

        if (count($errors) == 0) {
            if ($oldFile != $examSubject->file && $oldFile != null) {
                $fileName = \Yii::getAlias('@frontend/web/uploads/'. $student->id .'/'.$oldFile);
                if (file_exists($fileName)) {
                    unlink($fileName);
                }
            }
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }


}
