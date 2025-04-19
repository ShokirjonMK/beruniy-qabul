<?php

namespace console\controllers;

use common\models\AuthAssignment;
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

class SettingController extends Controller
{

}
