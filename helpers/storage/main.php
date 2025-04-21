<?php
use common\models\AuthItem;
use common\models\Consulting;
use common\models\Branch;
use common\models\Actions;
use common\models\Permission;


function current_user()
{
    return \Yii::$app->user->identity;
}

// Get current user id
function current_user_id($role)
{
    $user = \Yii::$app->user;
    $user_id = Yii::$app->user->identity;
    return is_numeric($user_id) ? $user_id : 0;
}
function isRole($string) {
    $user = Yii::$app->user->identity;
    if ($user->user_role == $string) {
        return true;
    }
    return false;
}


function custom_shuffle($my_array = array()) {
    $copy = array();
    while (count($my_array)) {
        // takes a rand array elements by its key
        $element = array_rand($my_array);
        // assign the array and its value to an another array
        $copy[$element] = $my_array[$element];
        //delete the element from source array
        unset($my_array[$element]);
    }
    return $copy;
}



function current_education_id()
{
    $user = Yii::$app->user->identity;
    return $user->id;
}



function tt($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    die;
}

function formatPhoneNumber($number)
{
    $normalizedPhoneNumber = preg_replace('/[^\d+]/', '', $number);
    return $normalizedPhoneNumber;
}

function getDomainFromURL($url) {
    // URL dan domen nomini ajratib olish
    $parsedUrl = parse_url($url);
    $domain = $parsedUrl['host'];

    return $domain;
}

function getIpAddress()
{
    return \Yii::$app->request->getUserIP();
}


function getIpMK()
{
    $mainIp = '';
    if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_X_CLUSTER_CLIENT_IP'))
        $mainIp = getenv('HTTP_X_CLUSTER_CLIENT_IP');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
    else
        $mainIp = 'UNKNOWN';
    return $mainIp;

    $mainIp = '';
    if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_X_CLUSTER_CLIENT_IP'))
        $mainIp = getenv('HTTP_X_CLUSTER_CLIENT_IP');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
    else
        $mainIp = 'UNKNOWN';
    return $mainIp;
}


// Is IP in allowed  List
function checkAllowedIP()
{
    // return true;
    $userIp = getIpMK();
    $sam = '10.0';
   
    $allowedIps = [
        '45.150.24.183',
        '89.104.102.200',
    ];

    if (in_array($userIp, $allowedIps)) {
        return true;
    } elseif (str_starts_with($userIp, $sam)) {
        return true;
    } elseif ($userIp == '127.0.0.1') {
        return true;
    }
    return false;
}

function getConsIk()
{
    $user = Yii::$app->user->identity;
    $role = $user->user_role;
    $authItem = AuthItem::findOne(['name' => $role]);

    $data = [
        's.branch_id' => null,
        'u.cons_id' => null,
    ];

    if ($authItem) {
        $cons = Consulting::find()
            ->select('id')
            ->column();

        $branch = Branch::find()
            ->select('id')
            ->column();

        if ($authItem->type == 1) {
            $data = [
                's.branch_id' => $branch,
                'u.cons_id' => $cons,
            ];
        } elseif ($authItem->type == 2) {
            $data = [
                's.branch_id' => $authItem->branch_id,
                'u.cons_id' => $cons,
            ];
        } elseif ($authItem->type == 3) {
            $data = [
                's.branch_id' => $branch,
                'u.cons_id' => $user->cons_id,
            ];
        } elseif ($authItem->type == 4) {
            $data = [
                's.branch_id' => $authItem->branch_id,
                'u.cons_id' => $user->cons_id,
            ];
        }
    }

    return $data;
}

function getBranchOneIk()
{
    $user = Yii::$app->user->identity;
    $role = $user->user_role;
    $authItem = AuthItem::findOne(['name' => $role]);

    $data = null;

    if ($authItem) {
        $branch = Branch::find()
            ->select('id')
            ->column();

        if ($authItem->type == 1) {
            $data = $branch;
        } elseif ($authItem->type == 2) {
            $data = $authItem->branch_id;
        } elseif ($authItem->type == 3) {
            $data = $branch;
        } elseif ($authItem->type == 4) {
            $data = $authItem->branch_id;
        }
    }

    return $data;
}

function getConsOneIk()
{
    $user = Yii::$app->user->identity;
    $role = $user->user_role;
    $authItem = AuthItem::findOne(['name' => $role]);

    $data = null;

    if ($authItem) {
        $cons = Consulting::find()
            ->select('id')
            ->column();

        if ($authItem->type == 1) {
            $data = $cons;
        } elseif ($authItem->type == 2) {
            $data = $cons;
        } elseif ($authItem->type == 3) {
            $data = $user->cons_id;
        } elseif ($authItem->type == 4) {
            $data = $user->cons_id;
        }
    }

    return $data;
}

function permission($controller, $action)
{
    $act = Actions::findOne([
        'controller' => $controller,
        'action' => $action,
        'status' => 0
    ]);

    if ($act) {
        $userRole = Yii::$app->user->identity->user_role;
        return Permission::find()
            ->where([
                'role_name' => $userRole,
                'action_id' => $act->id,
                'status' => 1
            ])
            ->exists();
    }

    return false;
}
