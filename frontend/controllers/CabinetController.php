<?php

namespace frontend\controllers;

use common\models\Contract;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\Exam;
use common\models\ExamStudentQuestionsSearch;
use common\models\ExamSubject;
use common\models\Options;
use common\models\Questions;
use common\models\StepFour;
use common\models\StepOneThree;
use common\models\StepOneTwo;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use common\models\Subjects;
use frontend\models\Test;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use common\models\StepOne;
use common\models\StepThree;
use common\models\StepTwo;
use common\models\StepThreeTwo;
use common\models\StepThreeOne;
use common\models\StepThreeThree;
use common\models\StepThreeFour;

/**
 * Site controller
 */
class CabinetController extends Controller
{
    public $layout = 'cabinet';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['student'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $controllerCheck = Yii::$app->controller->id;
            $actionCheck = Yii::$app->controller->action->id;
            if (!($controllerCheck == 'cabinet' && $actionCheck == 'step')) {
                $user = Yii::$app->user->identity;
                if ($user->step < 5) {
                    Yii::$app->response->redirect(['cabinet/step', 'id' => $user->step]);
                    return false;
                }
            }
        } else {
            Yii::$app->response->redirect(['site/login']);
            return false;
        }
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $student = Student::find()->with(['direction'])->where(['user_id' => $user->id])->one();
        // $student = Student::findOne(['user_id' => $user->id]);

        return $this->render('index', [
            'student' => $student
        ]);
    }

    public function actionConnection()
    {
        return $this->render('connection');
    }

    public function actionExamList()
    {
        return $this->render('exam-list');
    }

    public function actionSendFile()
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        return $this->render('send-file', [
            'student' => $student
        ]);
    }

    public function actionDownloadFile()
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        return $this->render('download-file', [
            'student' => $student
        ]);
    }

    public function actionExam()
    {
        $user = Yii::$app->user->identity;
        $student = Student::find()->with(['direction', 'eduDirection.eduForm', 'eduDirection.lang'])->where(['user_id' => $user->id])->one();

        // $student = Student::findOne(['user_id' => $user->id]);


        return $this->render('exam', [
            'student' => $student
        ]);
    }


    public function actionTest()
    {
        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        $result = Test::isCheck($student, $user);
        if (!$result['is_ok']) {
            \Yii::$app->session->setFlash('error', $result['errors']);
            return $this->redirect(['exam']);
        }

        $exam = $result['data'];

        $searchModel = new ExamStudentQuestionsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $exam);

        return $this->render('test', [
            'exam' => $exam,
            'dataProvider' => $dataProvider,
            'student' => $student
        ]);
    }

    public function actionFinish($id)
    {
        $examStudent = $this->findExamStudentModel($id);
        if ($examStudent->status == 2) {
            $result = Test::finishExam($examStudent);
            if (!$result['is_ok']) {
                \Yii::$app->session->setFlash('error', $result['errors']);
            }
        }
        return $this->redirect(['exam']);
    }


    public function actionStep($id = null)
    {
        $this->layout = '_cabinet-step';
        $errors = [];

        $user = Yii::$app->user->identity;
        $student = Student::findOne(['user_id' => $user->id]);

        if ($user->step > 4) {
            $errors[] = ['Ma\'lumotlaringizni tasdiqladingiz.'];
            Yii::$app->session->setFlash('error', $errors);
            return $this->redirect(['cabinet/index']);
        }

        if ($id == null) {
            $id = $user->step;
            if ($id > 4) {
                return $this->redirect(['cabinet/index']);
            }
        } else {
            if ($id > $user->step) {
                $id = $user->step;
            }
        }
        if ($id == 1) {
            if (Yii::$app->params['ikIntegration'] == 1) {
                $model = new StepOne();
            } elseif (Yii::$app->params['ikIntegration'] == 2) {
                $model = new StepOneTwo();
            } elseif (Yii::$app->params['ikIntegration'] == 3) {
                $model = new StepOneThree();
            } else {
                $errors[] = ['Birinchi bosqichni boshlay olmaysiz.'];
                Yii::$app->session->setFlash('error', $errors);
                return $this->redirect(['site/index']);
            }
        } elseif ($id == 2) {
            $model = new StepTwo();
        } elseif ($id == 3) {
            if ($student->edu_type_id == 1) {
                $model = new StepThreeOne();
            } elseif ($student->edu_type_id == 2) {
                $model = new StepThreeTwo();
            } elseif ($student->edu_type_id == 3) {
                $model = new StepThreeThree();
            } elseif ($student->edu_type_id == 4) {
                $model = new StepThreeFour();
            } else {
                $errors[] = ['XATOLIK!!!'];
                Yii::$app->session->setFlash('error', $errors);
                return $this->redirect(['step', 'id' => 1]);
            }
            $model->edu_type_id = $student->edu_type_id;
        } elseif ($id == 4) {
            $model = new StepFour();
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user, $student);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['step']);
            }
        }

        return $this->render('step', [
            'id' => $id,
            'model' => $model,
            'student' => $student,
            'user' => $user
        ]);
    }


    public function actionContractLoad($type)
    {
        $errors = [];
        $user = Yii::$app->user->identity;
        $student = $user->student;
        if ($student->exam_type == 1) {
            $errors[] = ['Shartnomani yuklab olish uchun universitetga murojat qiling!'];
            \Yii::$app->session->setFlash('error', $errors);
            return $this->redirect(\Yii::$app->request->referrer);
        }
        $eduDirection = $student->eduDirection;
        if ($eduDirection->edu_type_id != 4) {
            $action = 'contract';
        }  else {
            $errors[] = ['Shartnoma mavjud emas!'];
            \Yii::$app->session->setFlash('error', $errors);
            return $this->redirect(\Yii::$app->request->referrer);
        }

        $result = Contract::crmPush($student);
        if (!$result['is_ok']) {
            \Yii::$app->session->setFlash('error', $result['errors']);
            return $this->redirect(\Yii::$app->request->referrer);
        }

        $pdf = \Yii::$app->ikPdf;
        $content = $pdf->contract($student, $action);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssInline' => '
                body {
                    color: #000000;
                }
            ',
            'filename' => date('YmdHis') . ".pdf",
            'options' => [
                'title' => 'Contract',
                'subject' => 'Student Contract',
                'keywords' => 'pdf, contract, student',
            ],
        ]);

        return $pdf->render();
    }


    protected function findExamStudentModel($id)
    {
        $user = \Yii::$app->user->identity;
        if (($model = Exam::findOne(['id' => $id, 'user_id' => $user->id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException();
    }
}
