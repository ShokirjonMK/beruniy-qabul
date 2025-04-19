<?php
namespace backend\controllers;

use backend\components\HttpBearerAuth;
use backend\components\PermissonCheck;
//use base\ResponseStatus;
use common\models\Actions;
use common\models\Permission;
use common\models\RoleRestriction;
use common\models\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use yii\web\NotFoundHttpException;

trait ActionTrait
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login'])->send();
        }



        $controllerCheck = Yii::$app->controller->id;
        $actionCheck = Yii::$app->controller->action->id;

        $isAction = self::isAction($controllerCheck, $actionCheck);
        if (!$isAction) {
            return $this->redirect(['site/login'])->send();
        }

        if (!self::isPermission($isAction)) {
            return $this->redirect(['site/login'])->send();
        }

        return parent::beforeAction($action);
    }

    public static function isAction($controller, $action) {
        $errors = [];
        $isAction = Actions::findOne([
            'controller' => $controller,
            'action' => $action,
            'status' => 0
        ]);
        if ($isAction) {
            return $isAction;
        }
        $errors[] = ['Bu sahifa mavjud emas.'];
        \Yii::$app->session->setFlash('error' , $errors);
        return false;
    }

    public static function isPermission($isAction) {
        $errors = [];
        $user = \Yii::$app->user->identity;
        $isPermission = Permission::findOne([
            'role_name' => $user->user_role,
            'action_id' => $isAction->id,
            'status' => 1
        ]);
        if ($isPermission) {
            return true;
        }
        $errors[] = ['Bu amalani bajarishga sizga ruxsat mavjud emas.'];
        \Yii::$app->session->setFlash('error' , $errors);
        return false;
    }
}
