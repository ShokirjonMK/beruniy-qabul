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
use common\models\Options;
use common\models\Questions;
use common\models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;
use yii\web\UploadedFile;

/**
 * Signup form
 */
class Upload extends Model
{
    public $file;
    public $fileMaxSize = 1024 * 1000 * 20;

    public function rules()
    {
        return [
            [
                [ 'file' ],
                'file',
                'extensions'=>'xlsx',
                'skipOnEmpty' => true,
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

    public static function upload($model, $subject) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'file');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/excel_questions/';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }
                $photoName = \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                $url = $photoFolderName."/".$photoName;
                $photoFile->saveAs($photoFolderName."/".$photoName);

                $inputFileName = \Yii::getAlias($url);
                $spreadsheet = IOFactory::load($inputFileName);
                $data = $spreadsheet->getActiveSheet()->toArray();

                if (!file_exists($inputFileName)) {
                    unlink($inputFileName);
                }

                foreach ($data as $key => $row) {

                    if ($key != 0) {
                        $question = $row[0];
                        $optionTrue = $row[1];
                        $option1 = $row[2];
                        $option2 = $row[3];
                        $option3 = $row[4];

                        if ($question == "") {
                            break;
                        }
                        if ($optionTrue == "") {
                            $optionTrue = ".";
                        }
                        if ($option1 == "") {
                            $option1 = ".";
                        }
                        if ($option2 == "") {
                            $option2 = ".";
                        }
                        if ($option3 == "") {
                            $option3 = ".";
                        }

                        $option = [
                            0 => $optionTrue,
                            1 => $option1,
                            2 =>$option2,
                            3 =>$option3,
                        ];
                        $optionData = custom_shuffle($option);
                        $new = new Questions();
                        $new->subject_id = $subject->id;
                        $new->text = $question;
                        $new->status = 1;
                        if (!$new->validate()) {
                            $errors[] = $new->errors;
                            $transaction->rollBack();
                            return ['is_ok' => false , 'errors' => $errors];
                        }
                        if ($new->save(false)) {
                            foreach ($optionData as $key => $item) {
                                $newOption = new Options();
                                $newOption->question_id = $new->id;
                                $newOption->text = $item;
                                $newOption->subject_id = $subject->id;
                                if ($key == 0) {
                                    $newOption->is_correct = 1;
                                }
                                if (!$newOption->save(false)) {
                                    $errors[] = ['Option not saved.'];
                                    $transaction->rollBack();
                                    return ['is_ok' => false , 'errors' => $errors];
                                }
                            }
                        } else {
                            $errors[] = $new->errors;
                            $transaction->rollBack();
                            return ['is_ok' => false , 'errors' => $errors];
                        }
                    }
                }
            }
        } else {
            $errors[] = ['Fayl yuborilmadi!'];
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
