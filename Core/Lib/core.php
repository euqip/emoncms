<?php

/*

    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

*/
/*
  redirect to index.php when calling an existing file, salme as non existing file
 */
if (!defined('EMONCMS_EXEC')){
    // works with APACHE and NGINX
  $redir =  $_SERVER['SERVER_NAME'].preg_replace('/\/[a-zA-Z0-9-+.]*\.php/', '/index.php', $_SERVER['REQUEST_URI']);
  header ('Location:'.$redir);
}

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function get_application_path()
{
    // Default to http protocol
    $proto = "http";

    // Detect if we are running HTTPS or proxied HTTPS
    if (server('HTTPS') == 'on') {
        // Web server is running native HTTPS
        $proto = "https";
    } elseif (server('HTTP_X_FORWARDED_PROTO') == "https") {
        // Web server is running behind a proxy which is running HTTPS
        $proto = "https";
    }

    if( isset( $_SERVER['HTTP_X_FORWARDED_SERVER'] ))
        $path = dirname("$proto://" . server('HTTP_X_FORWARDED_SERVER') . server('SCRIPT_NAME')) . "/";
    else
        $path = dirname("$proto://" . server('HTTP_HOST') . server('SCRIPT_NAME')) . "/";

    return $path;
}

function db_check($mysqli,$database)
{
    $result = $mysqli->query("SELECT count(table_schema) from information_schema.tables WHERE table_schema = '$database'");
    $row = $result->fetch_array();
    if ($row['0']>0) return true; else return false;
}

function controller($controller_name)
{
    $output = array('content'=>'');

    if ($controller_name)
    {
        $controller = $controller_name."_controller";
        $controllerScript = "Modules/".$controller_name."/".$controller.".php";
        if (is_file($controllerScript))
        {
            // Load language files for module
            $domain = "messages";
            bindtextdomain($domain, "Modules/".$controller_name."/locale");
            //bind_textdomain_codeset($domain, 'UTF-8');
            textdomain($domain);

            require $controllerScript;
            $output = $controller();
        }
        else{
            //return permanent redirection to the root path
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = '';
            header('Status: 301 Moved Permanently', false, 301);
            header("Location: http://$host$uri/$extra");
            exit();

        }
    }
    return $output;
}

function view($filepath, array $args)
{
    extract($args);
    ob_start();
    include "$filepath";
    $content = ob_get_clean();
    return $content;
}

function get($index)
{
    $val = null;
    if (isset($_GET[$index])) $val = $_GET[$index];

    if (get_magic_quotes_gpc()) $val = stripslashes($val);
    return $val;
}

function post($index)
{
    $val = null;
    if (isset($_POST[$index])) $val = $_POST[$index];

    if (get_magic_quotes_gpc()) $val = stripslashes($val);
    return $val;
}

function prop($index)
{
    $val = null;
    if (isset($_GET[$index])) $val = $_GET[$index];
    if (isset($_POST[$index])) $val = $_POST[$index];

    if (get_magic_quotes_gpc()) $val = stripslashes($val);
    return $val;
}


function server($index)
{
    $val = null;
    if (isset($_SERVER[$index])) $val = $_SERVER[$index];
    return $val;
}

function load_db_schema()
{
    $schema = array();
    $dir = scandir("Modules");
    for ($i=2; $i<count($dir); $i++)
    {
        if (filetype("Modules/".$dir[$i])=='dir')
        {
            if (is_file("Modules/".$dir[$i]."/".$dir[$i]."_schema.php"))
            {
               require "Modules/".$dir[$i]."/".$dir[$i]."_schema.php";
            }
        }
    }
    return $schema;
}

function load_menu()
{
    $menu_left = array();
    $menu_right = array();
    $menu_dropdown = array();

    $dir = scandir("Modules");
    for ($i=2; $i<count($dir); $i++)
    {
        if (filetype("Modules".DS.$dir[$i])=='dir')
        {
            if (is_file("Modules".DS.$dir[$i].DS.$dir[$i]."_menu.php"))
            {
                require "Modules".DS.$dir[$i].DS.$dir[$i]."_menu.php";
            }
        }
    }

    usort($menu_left, "menu_sort");
    return array('left'=>$menu_left, 'right'=>$menu_right, 'dropdown'=>$menu_dropdown);
}


function load_credits()
{
    $credits = array();

    $dir = scandir("Modules");
    for ($i=2; $i<count($dir); $i++){
        if (filetype("Modules".DS.$dir[$i])=='dir'){
            if (is_file("Modules".DS.$dir[$i].DS.$dir[$i]."_credits.php")){
                require "Modules".DS.$dir[$i].DS.$dir[$i]."_credits.php";
            }
        }
    }
    return array('credits'=>$credits);
}


// Menu sort by order
function menu_sort($a,$b) {
    return $a['order']>$b['order'];
}


function flagselector($path,$dir){
  $languages= directoryLocaleScan(dirname($dir));
  $html='<div id ="flags">';
  foreach ($languages as $k => $v) {

    //code...build a flags div to select login block language
    // use iso country codes (flags images are 96px)

    $country= strtolower(substr($v,-2));
    $pth = 'Theme/flags_96/';
    //zz is a black flag, used to show undefined country
    if (!file_exists($pth.$country.'.png')){$country="zz";}
    $html .= '<a href="';
    $html .= $path.'user/login/lang='.$v.'">';
    $html .= '<img title= "'._($v).'" src="'.$path.$pth.$country.'.png"></a>';
  }
  $html .= '</div>';
  return $html;
}