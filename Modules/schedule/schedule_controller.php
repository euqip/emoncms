<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function schedule_controller()
{
    global $session,$route,$mysqli;

    $result = false;
    $modulename = "schedule";
    $basedir= MODULE.DS.$modulename.DS;
    include $basedir."schedule_model.php";
    $schedule = new Schedule($mysqli);

    if ($route->format == 'html')
    {
    	$view = $basedir."Views".DS.$modulename;
        if ($route->action == "view" && $session['write']) $result = view($view."_view.php",array());
        if ($route->action == 'api') $result = view($view."_api.php", array());
    }

    if ($route->format == 'json')
    {
		if ($route->action == 'list') {
			if ($session['userid']>0 && $session['userid'] && $session['read']) $result = $schedule->get_list($session['userid']);
		}
		elseif ($route->action == "create") {
			if ($session['userid']>0 && $session['write']) $result = $schedule->create($session['userid']);
		}
		elseif ($route->action == "test") {
			if ($session['read']) $result = $schedule->test_expression(get('expression'));
		}
		else {
			$scheduleid = (int) get('id');
            if ($schedule->exist($scheduleid)) // if the feed exists
            {
				$scheduleget = $schedule->get($scheduleid);
				// if public or belongs to user
				if ($session['read'] && ($scheduleget['public'] || ($session['userid']>0 && $scheduleget['userid']==$session['userid'])))
				{
					if (($route->action == "get")) $result = $scheduleget;
					if (($route->action == "expression")) $result = $schedule->get_expression($scheduleid);
				}
				// if public
				if (isset($session['write']) && $session['write'] && $session['userid']>0 && $scheduleget['userid']==$session['userid']) {
					if ($route->action == "delete") $result = $schedule->delete($scheduleid );
					if ($route->action == 'set') $result = $schedule->set_fields($scheduleid ,get('fields'));
				}
            }
            else
            {
                $result = array('success'=>false, 'message'=>'Schedule does not exist');
            }
		}
    }

    return array('content'=>$result);
}