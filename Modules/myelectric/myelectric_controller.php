<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function myelectric_controller()
{
    global $session,$route,$mysqli;

    $result = false;
    $modulename = "myelectric";
    $basedir = MODULE.DS.$modulename.DS;

    include $basedir.$modulename."_model.php";
    $myelectric = new MyElectric($mysqli);

    if ($route->format == 'html')
    {
        if ($route->action == "" && $session['write']) $result = view($basedir.$modulename."_view.php",array());
    }

    if ($route->format == 'json')
    {
        if ($route->action == "set" && $session['write']) $result = $myelectric->set_mysql($session['userid'],get('data'));
        if ($route->action == "get" && $session['read']) $result = $myelectric->get_mysql($session['userid']);
    }

    return array('content'=>$result);
}

