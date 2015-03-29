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

function user_controller()
{
    global $user, $path, $session, $route ,$allowusersregister;

    $result = false;
    $modulename = "user";

    // Load html,css,js pages to the client
    if ($route->format == 'html')
    {
        $view = MODULE.DS.$modulename.DS;
        if ($route->action == 'login' && !$session['read']) $result = view($view."login_block.php", array());
        //if ($route->action == 'view' && $session['write']) $result = view($view."profile/profile.php", array());
        if ($route->action == 'view' && $session['write']) $result = view($view."profile/profile.php", array());
        if ($route->action == 'currentuser' && $session['write']){
            $_SESSION['showuserid']=intval($_SESSION['userid']);
            $result = view($view."profile/profile.php", array());
        }
        if ($route->action == 'logout' && $session['read']) {$user->logout(); header('Location: '.$path);}
    }

    // JSON API
    if ($route->format == 'json')
    {
        // Core session
        $myuser=intval($session['userid']);
        if (isset($_SESSION['showuserid'])) $myuser=intval($_SESSION['showuserid']);
        if ($route->action == 'login' && !$session['read']) $result = $user->login(post('username'),post('password'),post('rememberme'));
        if ($route->action == 'register' && $allowusersregister) $result = $user->register(post('username'),post('password'),post('orgname'),post('email'));
        if ($route->action == 'logout' && $session['read']) $user->logout();

        if ($route->action == 'changeusername' && $session['write']) $result = $user->change_username($myuser,get('username'));
        if ($route->action == 'changeemail' && $session['write']) $result = $user->change_email($myuser,get('email'));
        if ($route->action == 'changepassword' && $session['write']) $result = $user->change_password($myuser,get('old'),get('new'));
        if ($route->action == 'forcepwdchange' && $session['write']) $result = $user->forcenewpwd(post('userid'));
        //if ($route->action == 'changeusername' && $session['write']) $result = $user->change_username($session['userid'],get('username'));
        //if ($route->action == 'changeemail' && $session['write']) $result = $user->change_email($session['userid'],get('email'));
        //if ($route->action == 'changepassword' && $session['write']) $result = $user->change_password($session['userid'],get('old'),get('new'));

        // Apikey
        if ($route->action == 'newapikeyread' && $session['write']) $result = $user->new_apikey_read($session['userid']);
        if ($route->action == 'newapikeywrite' && $session['write']) $result = $user->new_apikey_write($session['userid']);


        // Get and set - user by profile client
        if ($route->action == 'get' && $session['write']) $result = $user->get_partial($myuser);
        if ($route->action == 'set' && $session['write']) $result = $user->set($myuser,json_decode(post('data')));

        //if ($route->action == 'get' && $session['write']) $result = $user->get($session['userid']);
        //if ($route->action == 'set' && $session['write']) $result = $user->set($session['userid'],json_decode(get('data')));

        //if ($route->action == 'getconvert' && $session['write']) $result = $user->get_convert_status($session['userid']);
        //if ($route->action == 'setconvert' && $session['write']) $result = $user->set_convert_status($session['userid']);
        if ($route->action == 'passwordreset') $result = $user->passwordreset(get('username'),get('email'));


        if ($route->action == 'auth' && !$session['read']) $result = $user->get_apikeys_from_login(post('username'),post('password'));
        if ($route->action == 'timezone' && $session['read']) $result = $user->get_timezone_offset($session['userid']); // to maintain compatibility but in seconds
        if ($route->action == 'gettimezone' && $session['read']) $result = $user->get_timezone($session['userid']);
    // /user/gettimezones.json
        if ($route->action == 'gettimezones' && $session['read']) $result = $user->get_timezones();
    }
    return array('content'=>$result);
}
