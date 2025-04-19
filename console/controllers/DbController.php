<?php

namespace console\controllers;

use common\models\Permission;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Inflector;
use common\models\Actions;
class DbController extends Controller
{
    const MENU_STATUS = 1;
    const SUB_MENU_STATUS = 0;
    public $controllersWithoutPermissions = [
        'auth'
    ];

    public function actionActions()
    {
        $actions = $this->generatePermissions('backend');
        if (is_array($actions) && count($actions) > 0) {
            foreach ($actions as $action) {
                $query = Actions::findOne([
                    'controller' => $action['controller'],
                    'status' => 1,
                ]);
                if ($query == null) {
                    $newController = new Actions();
                    $newController->controller = $action['controller'];
                    $newController->action = ".";
                    $newController->description = $action['controller'];
                    $newController->status = self::MENU_STATUS;
                    $newController->save();
                }
                foreach ($action['action'] as $value) {
                    $querySecond = Actions::findOne([
                        'controller' => $action['controller'],
                        'action' => $value,
                        'status' => 0,
                    ]);
                    if ($querySecond == null) {
                        $newAction = new Actions();
                        $newAction->controller = $action['controller'];
                        $newAction->action = $value;
                        $newAction->description = $action['controller'];
                        $newAction->status = self::SUB_MENU_STATUS;
                        $newAction->save();

                        $newPermission = new Permission();
                        $newPermission->role_name = 'super_admin';
                        $newPermission->action_id = $newAction->id;
                        $newPermission->status = 1;
                        $newPermission->save(false);
                    }
                }
            }
        }
        echo "Actionlar kiritildi. \n";
    }

    protected function generatePermissions($folder)
    {
        $controllerlist = [];
        if ($handle = opendir($folder . '/controllers')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);

        $fulllist = [];
        $permissions = [];
        foreach ($controllerlist as $controller) :
            $handle = fopen($folder . '/controllers/' . $controller, "r");
            if ($handle) {
                $con = [];
                $conrollerId = Inflector::camel2id(substr($controller, 0, -14));
                $con['controller'] = $conrollerId;
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)) :
                        if (strlen($display[1]) > 0 && $display[1] != 's') :
                            if(!in_array($conrollerId, $this->controllersWithoutPermissions)) :
                                $actionId = Inflector::camel2id($display[1]);
                                $fulllist[$conrollerId][] = $actionId;
                                $con['action'][] = $actionId;
//                                $permissions[] = [0 => $conrollerId , 1 => $actionId];
                            endif;
                        endif;
                    endif;
                }
                $permissions[] = $con;
            }
            fclose($handle);
        endforeach;
        return $permissions;
    }

    private function permissonToWord($permission){
        list($controller, $action) = explode('_', $permission);
        return ucfirst( strtolower(Inflector::camel2words(Inflector::id2camel($controller . '|' . $action))));
    }

    public function actionSup()
    {
        $actions = Actions::find()
            ->where(['status' => 0])
            ->all();
        foreach ($actions as $action) {
            $query = Permission::findOne([
                'role_name' => 'super_admin',
                'action_id' => $action->id
            ]);
            if (!$query) {
                $new = new Permission();
                $new->role_name = 'super_admin';
                $new->action_id = $action->id;
                $new->status = 1;
                $new->save(false);
            }
        }
    }
}
