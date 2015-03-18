<?php

/*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

*/

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function org_controller()
{
    global $user,$org, $path, $session, $route ,$allowusersregister;

    $result = false;
    $modulename = "org";

    // Load html,css,js pages to the client
    if ($route->format == 'html')
    {
        $basedir = MODULE.DS.$modulename.DS."Views".DS.$modulename;
        if ($route->action == 'new' && !$session['read']) $result = view($basedir."_create.php", array());
        if ($route->action == 'view' && $session['write']) $result = view($basedir."_view.php", array());
    }

    // JSON API
    if ($route->format == 'json')
    {
        $myuser = $session['userid'];
        // Core session
        if ($route->action == 'login' && !$session['read']) $result = $org->login(post('username'),post('password'),post('rememberme'));
        if ($route->action == 'register' && $allowusersregister) $result = $org->register(post('username'),post('password'),post('email'));
        if ($route->action == 'logout' && $session['read']) $org->logout();

        if ($route->action == 'changeusername' && $session['write']) $result = $org->change_username($myuser,get('username'));
        if ($route->action == 'changeemail' && $session['write']) $result = $org->change_email($myuser,get('email'));
        //if ($route->action == 'changepassword' && $session['write']) $result = $org->change_password($myuser,get('old'),get('new'));

        //if ($route->action == 'passwordreset') $result = $org->passwordreset(get('username'),get('email'));
        // Apikey
        if ($route->action == 'newapikeyread' && $session['write']) $result = $org->new_apikey_read($myuser);
        if ($route->action == 'newapikeywrite' && $session['write']) $result = $org->new_apikey_write($myuser);

        //if ($route->action == 'auth' && !$session['read']) $result = $org->get_apikeys_from_login(post('username'),post('password'));

        // Get and set - user by profile client
        if ($route->action == 'get' && $session['write']) $result = $org->get_partial($myuser);
        if ($route->action == 'set' && $session['write']) $result = $org->updaterecord($myuser,json_decode(post('data')));

        if ($route->action == 'timezone' && $session['read']) $result = $org->get_timezone($myuser);
    }

    return array('content'=>$result);
}
