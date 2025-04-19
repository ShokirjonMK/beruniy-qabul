<?php

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
