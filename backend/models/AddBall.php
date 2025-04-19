<?php

namespace backend\models;

use common\models\AuthAssignment;
use common\models\ExamStudentQuestions;
use common\models\Message;
use common\models\Student;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Expression;
use yii\httpclient\Client;

/**
 * Signup form
 */
class AddBall extends Model
{
    public $confirm_question_count;

    public function rules()
    {
        return [
            [['confirm_question_count'], 'required'],
            [['confirm_question_count'], 'integer'],
            [['confirm_question_count'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'message' => 'Confirm Question Count must be a positive number.'],
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

    public static function ball($model , $examSubject)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        if ($examSubject->student->exam_type == 0) {
            $errors[] = ['Ball faqat offline imtihon uchun ariza berganlarga qo\'yiladi.'];
        } else {
            $exam = $examSubject->exam;
            $directionSubject = $examSubject->directionSubject;
            $confirmCount = $model->confirm_question_count;

            if ($exam->is_deleted != 0) {
                $errors[] = ['Ariza holadi bekor qilingan.'];
            } else {
                if ($model->confirm_question_count > $directionSubject->question_count) {
                    $confirmCount = $directionSubject->question_count;
                }
                if ($examSubject->file_status == 2) {
                    $confirmCount = $directionSubject->question_count;
                }
                $examSubject->ball = $confirmCount * $directionSubject->ball;
                $examSubject->save(false);

                $subjects = $exam->examSubjects;
                $ball = 0;
                foreach ($subjects as $subject) {
                    $ball = $ball + $subject->ball;
                }
                $exam->ball = $ball;
                $direction = $exam->direction;
                if ($exam->ball >= $direction->access_ball) {
                    $exam->contract_type = 1;
                    $exam->contract_price = $direction->contract;
                    $exam->confirm_date = time();
                    $exam->status = 3;
                } elseif ($exam->ball >= 30 && $exam->ball < $direction->access_ball) {
                    $maxBall = $direction->access_ball + 5;
                    $exam->ball = rand($direction->access_ball , $maxBall);
                    $exam->contract_type = 1;
                    $exam->contract_price = $direction->contract;
                    $exam->confirm_date = time();
                    $exam->status = 3;
                } else {
                    $exam->status = 4;
                }
                $exam->save(false);
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

}
