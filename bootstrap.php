<?php
$ltime = microtime(true);

define('EMONCMS_EXEC', 1);

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');


/**
 * Application defines
 */
if (!defined('DS')) {
    define('DS', '/');
}
define('ROOT', dirname(__FILE__) . DS);
define('CORE', ROOT . 'Core' . DS);
define('LIB', CORE . 'Lib' . DS);
define('MODULE','Modules');


define('REGEX_SERVER_NAME' , '/\/[a-zA-Z0-9-+.]*\.php/');
define('REGEX_STRING','/[^\w\s-]/');
//                    '/[^\w\s-:()]/'  '/[^\w\s-:]/'
define('REGEX_STRING_ACCENT','/[^\s\p{L}0-9]-\'/u');
//                            /[^\s\p{L}0-9]-\'/u
define('REGEX_EXPRESSION','/[^\/\|\,\w\s-:]/');
define('REGEX_CURRENCY','/[^\w\s£$€¥]/');

define('REGEX_UNITS','/[^\w\s-°]/');
define('REGEX_ALPHA_NUM','/[^.\/A-Za-z0-9-=_]/');
define('REGEX_NUMERIC','/[^.\/0-9,;.=_]/');

//define('REGEX_ALPHA_NUM_ACCENT','/[^\p{L}.\/A-Za-z0-9-=_]/');

//set default parameters, to avoid missing ones
//these parameters will be overwritten with the settings.php file content
//
    $behavior=array(
        'multiorg'=>true,
        'min_usernamelen'=>3,
        'max_usernamelen'=>30,
        'min_orgnamelen'=>3,
        'max_orgnamelen'=>10,
        'min_pwdlen'=>3,
        'max_pwdlen'=>30,

        'userlist_expanded'=>FALSE,
        'usergoup'=> "letter",
        //usergroup may be '', than no groups are made, it is used in users list view
        'userletter'=> "UCASE(LEFT(username,1)) as letter",
        //'userletter'=> "1 as letter",
        //userletter MUST be present but may be a constant, the letter field is used in ORDER BY directive
        //'userletter'=> "UCASE(LEFT(email,1)) as letter",
        // grouping by email first letter is an other option
        'orglist_expanded'=>FALSE,
        'orggroup'=> "letter",
        //orggroup may be '', than no groups are made, it is used in organisations list view
        'orgletter'=> "ucase(LEFT(orgname,1)) as letter",
        //orgletter MUST be present but may be a constant
        // see userletter  above and apply same rules, on countries for example
        //'orgletter'=> "country as letter" will group organisations by countries
        'inputgroup'=> "nodeid",
        'inputinterval'=> 5000,
        'inputlistexpanded'=>TRUE,

        'feedgroup'=> "tag",
        'feedinterval'=> 5000,
        'feedlistexpanded'=>1,

        'dashlist_expanded'=>true,
        'dashgroup'=> "letter",
        //orggroup may be '', than no groups are made, it is used in organisations list view
        'dashletter'=> "ucase(LEFT(name,1)) as letter",

        'csv_parameters'=> array(
          'csvdownloadlimit_mb' => 10,
          'csv_field_separator'=>";",
          'csv_decimal_place_separator'=>",",
          'csv_thousandsepar_separator'=>"",
          'csv_dateformat'=>"Y-m-d",
          'csv_timeformat'=>"H:i:s"
          ),

        );



/**
 * Load up required libs
 */
require_once CORE . 'Utility' . DS          . 'Configure.php';
require_once LIB  . 'Enum.php';

require_once ROOT . 'process_settings.php';
require_once LIB  . 'core.php';

require_once LIB  . 'route.php';
require_once LIB  . 'locale.php';
require_once CORE . 'Model' . DS            . 'ConnectionManager.php';
require_once CORE . 'Model' . DS            . 'Model.php';

if (defined('EMON_TEST_ENV') && EMON_TEST_ENV) {
	require_once CORE . 'TestSuite' . DS . 'EmonTestCase.php';
}