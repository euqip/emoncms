<?php
/*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
*/
if (!defined('EMONCMS_EXEC')){
    // works with APACHE and NGINX
  $redir =  $_SERVER['SERVER_NAME'].preg_replace(REGEX_SERVER_NAME, '/index.php', $_SERVER['REQUEST_URI']);
  header ('Location:'.$redir);
}

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

// Return all locale directory from all modules.
// If one module has a language it will be detected
function directoryLocaleScan($dir) {
    if (isset($dir) && is_readable($dir)) {
        $dlist = Array();
        $dir = realpath($dir);
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);

        foreach($objects as $entry => $object){
            $entry = str_replace($dir, '', $entry);
            if (basename(dirname($entry))=='locale' && basename($entry)!='.' && basename($entry)!='..') $dlist[] = basename($entry);
        }

        return array_unique($dlist);
    }
}

function get_available_languages()
{
   //return directoryLocaleScan(dirname(__FILE__));
   return directoryLocaleScan(dirname(CORE));
}


function lang_http_accept()
{
    $langs = array();

    foreach (explode(',', server('HTTP_ACCEPT_LANGUAGE')) as $lang)
    {
        $pattern = '/^(?P<primarytag>[a-zA-Z]{2,8})'.
        '(?:-(?P<subtag>[a-zA-Z]{2,8}))?(?:(?:;q=)'.
        '(?P<quantifier>\d\.\d))?$/';

        $splits = array();

        if (preg_match($pattern, $lang, $splits)) {
            $a = $splits["primarytag"];
            if (isset($splits["subtag"]) && $splits["subtag"]<> "") $a = $a."_".$splits["subtag"];
            $langs[]=$a;
        } else {
            // No match
        }
    }
    return $langs;
}

function set_lang($language)
{
    // set the first browser selected language
    // TODO: iterate to find a suitable available language

    // Chrome returns different HTTP_ACCEPT_LANGUAGE code than firefox!!!
    // Firefox      Chrome
    // -------------------
    //  en_EN         en
    //  es_ES         es
    // ... so translation system does not work in Chrome!!!
    // lets try to fix quickly

    if (isset($language[0]))
    {
        $lang=$language[0];

        switch($lang) {
            case 'cy': $lang='cy_GB'; break;
            case 'da': $lang='da_DK'; break;
            case 'en': $lang='en_EN'; break;
            case 'es': $lang='es_ES'; break;
            case 'fr': $lang='fr_FR'; break;
            case 'it': $lang='it_IT'; break;
            case 'nl': $lang='nl_NL'; break;
            //case 'nl': $lang='nl_BE'; break; //to do??
        }

        set_lang_by_user($lang);
    }
}

function set_lang_by_user($lang)
{
    //putenv("LC_ALL=$lang");
    putenv("LANG=".$lang);
    //gettext did not work with this change (Upbuntu 13.10 php5)
    setlocale(LC_ALL,$lang.'.UTF-8');
    //Because the locales are relative to execution folder
    //Language settings may be set once
    //a different setting in module is always possible
    $domain = "messages";
    bindtextdomain($domain, 'locale');
    bind_textdomain_codeset($domain, 'UTF-8');
    putenv("LC_ALL=$lang");
    setlocale(LC_ALL,$lang);
}

function set_emoncms_lang($lang)
{
    // If no language defined use the language browser
    if ($lang == ''){
        set_lang(lang_http_accept());
    }
    set_lang_by_user($lang);
}
