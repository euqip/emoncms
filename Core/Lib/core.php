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

function controller($controller_name,$moduledir = "Modules")
{
    $output = array('content'=>'');

    if ($controller_name)
    {
        $controller = $controller_name."_controller";
        $controllerScript = $moduledir.DS.$controller_name.DS.$controller.".php";
        if (is_file($controllerScript))
        {
            // Load language files for module
            $domain = "messages";
            bindtextdomain($domain, $moduledir.DS.$controller_name.DS."locale");
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

function load_db_schema($moduledir = "Modules")
{
    $schema = array();
    $dir = scandir($moduledir);
    for ($i=2; $i<count($dir); $i++)
    {
        if (filetype($moduledir.DS.$dir[$i])=='dir')
        {
            if (is_file($moduledir.DS.$dir[$i].DS.$dir[$i]."_schema.php"))
            {
               require $moduledir.DS.$dir[$i].DS.$dir[$i]."_schema.php";
            }
        }
    }
    return $schema;
}

function load_menu($moduledir = "Modules")
{
    $menu_left = array();
    $menu_right = array();
    $menu_dropdown = array();

    $dir = scandir($moduledir);
    for ($i=2; $i<count($dir); $i++)
    {
        if (filetype($moduledir.DS.$dir[$i])=='dir')
        {
            if (is_file($moduledir.DS.$dir[$i].DS.$dir[$i]."_menu.php"))
            {
                require $moduledir.DS.$dir[$i].DS.$dir[$i]."_menu.php";
            }
        }
    }

    usort($menu_left, "menu_sort");
    return array('left'=>$menu_left, 'right'=>$menu_right, 'dropdown'=>$menu_dropdown);
}


function load_credits($moduledir = "Modules")
{
    $credits = array();

    $dir = scandir($moduledir);
    for ($i=2; $i<count($dir); $i++){
        if (filetype($moduledir.DS.$dir[$i])=='dir'){
            if (is_file($moduledir.DS.$dir[$i].DS.$dir[$i]."_credits.php")){
                require $moduledir.DS.$dir[$i].DS.$dir[$i]."_credits.php";
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
    $pth = 'Theme'.DS.'flags_96'.DS;
    //zz is a black flag, used to show undefined country
    if (!file_exists($pth.$country.'.png')){$country="zz";}
    $html .= '<a href="';
    $html .= $path.'user'.DS.'login/lang='.$v.'">';
    $html .= '<img title= "'._($v).'" src="'.$path.$pth.$country.'.png"></a>';
  }
  $html .= '</div>';
  return $html;
}

/**
 * Replace accented characters with non accented
 *
 * @param $str
 * @return mixed
 * @link http://myshadowself.com/coding/php-function-to-convert-accented-characters-to-their-non-accented-equivalant/
 */
function removeAccents($str) {
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
  return str_replace($a, $b, $str);
}

function toSlug($str){
    $a = removeAccents($str);
    $b= trim(strtolower(strtoupper($a)));
    $x= array('?','/',' ','&','--');
    $y= array('-','-','-','-','-');
    return str_Replace($x,$y,$b);
}

/**
 * returns the user IP address
 *
 * @param $str
 * @return mixed
 * @link  http://stackoverflow.com/questions/11864059/how-to-get-ip-address-in-php/13604604#13604604
 */

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))    {
        $ip = $forward;
    }
    else    {
        $ip = $remote;
    }
    return $ip;
}
