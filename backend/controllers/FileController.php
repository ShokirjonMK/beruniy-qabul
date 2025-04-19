<?php

namespace backend\controllers;

use common\models\EduDirection;
use common\models\ExamDate;
use common\models\Status;
use common\models\StudentMaster;
use backend\controllers\ActionTrait;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\ExamSubject;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use common\models\User;
use frontend\models\Contract;
use common\models\SerCreate;
use common\models\SerDel;
use frontend\models\StepFour;
use frontend\models\Test;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;


/**
 * Site controller
 */
class FileController extends Controller
{
    use ActionTrait;

    public $layout = 'cabinet';

    public function actionDirection()
    {
        $branch_id = yii::$app->request->get('branch_id');
        $form_id = yii::$app->request->get('form_id');
        $lang_id = yii::$app->request->get('lang_id');
        $std_id = yii::$app->request->get('std_id');
        $student = Student::findOne(['id' => $std_id]);

        $eduDirections = EduDirection::find()
            ->where([
                'branch_id' => $branch_id,
                'edu_type_id' => $student->edu_type_id,
                'edu_form_id' => $form_id,
                'lang_id' => $lang_id,
                'status' => 1,
                'is_deleted' => 0
            ])->all();

        $options = "";
        $options .= "<option value=''>Yo'nalish tanlang ...<option>";
        if (count($eduDirections) > 0) {
            foreach ($eduDirections as $eduDirection) {
                $direction = $eduDirection->direction;
                $options .= "<option value='$eduDirection->id'>". $direction->code ." - ". $direction['name_uz']. "</option>";
            }
        }
        return $options;
    }

    public function actionExamType()
    {
        $dir_id = yii::$app->request->get('dir_id');
        $eduDirection = EduDirection::findOne($dir_id);

        $options = "";
        $options .= "<option value=''>Imtixon turini tanlang ...<option>";

        if ($eduDirection) {
            if ($eduDirection->exam_type != null) {
                $examTypes = json_decode($eduDirection->exam_type, true);
                foreach ($examTypes as $examType) {
                    $options .= "<option value='$examType'>". Status::getExamStatus($examType) ."</option>";
                }
            }
        }
        return $options;
    }


    public function actionDirectionBranch()
    {
        $branch_id = yii::$app->request->get('branch_id');

        $directions = Direction::find()
            ->where([
                'branch_id' => $branch_id,
                'status' => 1,
                'is_deleted' => 0
            ])->all();

        $options = "";
        $options .= "<option value=''>Yo'nalish tanlang ...<option>";
        foreach ($directions as $direction) {
            $options .= "<option value='$direction->id'>". $direction->name ."</option>";
        }

        return $options;
    }


    public function actionDirectionCourse()
    {
        $branch_id = yii::$app->request->get('branch_id');
        $dir_id = yii::$app->request->get('dir_id');
        $std_id = yii::$app->request->get('std_id');
        $student = Student::findOne(['id' => $std_id]);

        $direction = EduDirection::findOne([
            'branch_id' => $branch_id,
            'edu_type_id' => $student->edu_type_id,
            'id' => $dir_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        $options = "";
        $options .= "<option value=''>Yakunlagan bosqichingiz ...<option>";

        if ($direction) {
            $directionCourses = DirectionCourse::find()
                ->where([
                    'edu_direction_id' => $direction->id,
                    'status' => 1,
                    'is_deleted' => 0
                ])->orderBy('course_id asc')->all();
            if (count($directionCourses) > 0) {
                foreach ($directionCourses as $course) {
                    $options .= "<option value='$course->id'>{$course->course['name_uz']}</option>";
                }
            }
        }

        return $options;
    }

    public function actionExamDate()
    {
        $branch_id = Yii::$app->request->get('branch_id');
        $std_id = Yii::$app->request->get('std_id');
        $student = Student::findOne(['id' => $std_id]);

        $examDates = ExamDate::find()
            ->where(['is_deleted' => 0, 'status' => 1, 'branch_id' => $branch_id])
            ->orderBy(['date' => SORT_ASC])->all();

        $html = "<div class='row'>";
        foreach ($examDates as $examDate) {
            $checked = '';
            if ($student->exam_date_id == $examDate->id) {
                $checked = 'checked';
            }
            $html .= "<div class='col-md-6 col-sm-12 col-12'>
                    <div class='exam-date-item bot20'>
                        <label for='check_{$examDate->id}' class='permission_label2'>
                            <div class='d-flex gap-2 align-items-center'>
                                <input type='radio' class='bu-check' name='StepThreeOne[exam_date_id]' id='check_{$examDate->id}' value='{$examDate->id}' {$checked}>
                                <span>Imtihon sanasi:</span>
                            </div>
                            <p>" . date('Y-m-d H:i', strtotime($examDate->date)) . "</p>
                        </label>
                    </div>
                </div>";
        }
        $html .= "</div>";

        return $html;
    }

    public function actionCreateSertificate($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $examSubject = ExamSubject::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($examSubject) {
            $model = new SerCreate();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerCreate::upload($model , $student , $examSubject);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('create-sertificate', [
                'model' => $model,
                'examSubject' => $examSubject
            ]);
        }
    }

    public function actionDelSertificate($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $query = ExamSubject::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($query) {
            $model = new SerDel();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerDel::upload($model , $student , $query);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('delete-sertificate', [
                'model' => $model,
                'query' => $query
            ]);
        }
    }

    public function actionDelOferta($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $query = StudentOferta::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($query) {
            $model = new SerDel();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerDel::upload($model , $student , $query);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('delete-sertificate', [
                'model' => $model,
                'query' => $query
            ]);
        }
    }

    public function actionDelTr($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $query = StudentPerevot::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($query) {
            $model = new SerDel();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerDel::upload($model , $student , $query);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('delete-sertificate', [
                'model' => $model,
                'query' => $query
            ]);
        }
    }

    public function actionCreateTr($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $examSubject = StudentPerevot::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($examSubject) {
            $model = new SerCreate();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerCreate::upload($model , $student , $examSubject);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('create-sertificate', [
                'model' => $model,
                'examSubject' => $examSubject
            ]);
        }
    }

    public function actionCreateOferta($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $examSubject = StudentOferta::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($examSubject) {
            $model = new SerCreate();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerCreate::upload($model , $student , $examSubject);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('create-sertificate', [
                'model' => $model,
                'examSubject' => $examSubject
            ]);
        }
    }

    public function actionDelDtm($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $query = StudentDtm::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($query) {
            $model = new SerDel();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerDel::upload($model , $student , $query);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('delete-sertificate', [
                'model' => $model,
                'query' => $query
            ]);
        }
    }

    public function actionCreateDtm($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $examSubject = StudentDtm::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($examSubject) {
            $model = new SerCreate();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerCreate::upload($model , $student , $examSubject);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('create-sertificate', [
                'model' => $model,
                'examSubject' => $examSubject
            ]);
        }
    }

    public function actionDelMaster($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $query = StudentMaster::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($query) {
            $model = new SerDel();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerDel::upload($model , $student , $query);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('delete-sertificate', [
                'model' => $model,
                'query' => $query
            ]);
        }
    }

    public function actionCreateMaster($id)
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $query = StudentMaster::findOne([
            'id' => $id,
            'student_id' => $student->id,
            'edu_direction_id' => $student->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if ($query) {
            $model = new SerCreate();
            if ($model->load(Yii::$app->request->post())) {
                $result = SerCreate::upload($model , $student , $query);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->renderAjax('create-sertificate', [
                'model' => $model,
                'examSubject' => $query
            ]);
        }
    }

}
