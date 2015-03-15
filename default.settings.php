<?php

/*
  redirect to index.php when calling an existing file, salme as non existing file
 */
if (!defined('EMONCMS_EXEC')){
    // works with APACHE
  $redir =  $_SERVER['SERVER_NAME'].preg_replace(REGEX_SERVER_NAME, '/index.php', $_SERVER['REQUEST_URI']);
  header ('Location:'.$redir);
}
   // no direct access
defined('EMONCMS_EXEC') or die('Restricted access');
    /*

    Database connection settings

    */

    $username = "_DB_USER_";
    $password = "_DB_PASSWORD_";
    $server   = "localhost";
    $database = "emoncms";

    $redis_enabled = true;

    // Enable this to try out the experimental MQTT Features:
    // - updated to feeds are published to topic: emoncms/feed/feedid
    $mqtt_enabled = false;

    $feed_settings = array(

        'enable_mysql_all'=>true,

        'timestore'=>array(
            'adminkey'=>"_TS_ADMINKEY_"
        ),

        'graphite'=>array(
            'port'=>0,
            'host'=>0
        ),

        // The default data directory is /var/lib/phpfiwa,phpfina,phptimeseries on windows or shared hosting you will likely need to specify a different data directory.
        // Make sure that emoncms has write permission's to the datadirectory folders

        'phpfiwa'=>array(
            'datadir'=>'/var/lib/phpfiwa/'
        ),
        'phpfina'=>array(
            'datadir'=>'/var/lib/phpfina/'
        ),
        'phptimeseries'=>array(
            'datadir'=>'/var/lib/phptimeseries/'
        ),
        'phptimestore'=>array(
            'datadir'=>'/var/lib/phptimestore/'
        )
    );

    // (OPTIONAL) Used by password reset feature
    $smtp_email_settings = array(
      'host'=>"_SMTP_HOST_",
      'username'=>"_SMTP_USER_",
      'password'=>"_SMTP_PASSWORD_",
      'from'=>array('_SMTP_EMAIL_ADDR_' => '_SMTP_EMAIL_NAME_')
    );

/** set the PHPMailer parameters
**  PHPMailer is to be found here : https://github.com/PHPMailer/PHPMailer
**  avoid to use accentued characters in real name, it will not be handled has UTF-8
**  SWIFTMailer did not send any mail!


gmail example:

      $PHPMailer_settings = array(
      'host'=>'smtp.gmail.com',
      'port'=> '587',
      'auth'=> true,
      'encryption' =>'tls',
      'username'=>'gmailusername',
      'password'=>'gmailpassword',
      'from'=>'sent from or reply address',
      'fromname'=>'fromrealname',
      'tobcc'=>'fill it if you want to have mail content feedbackfeedback'
    );
**/

      $PHPMailer_settings = array(
      'host'=>'smtp.gmail.com',
      'port'=> '587',
      'auth'=> true,
      'encryption' =>'tls',
      'username'=>'gmailusername',
      'password'=>'gmailpassword',
      'from'=>'sent from or reply address',
      'fromname'=>'fromrealname',
      'tobcc'=>'fill it if you want to have mail content feedbackfeedback'
    );


    // To enable / disable password reset set to either true / false
    // default value of " _ENABLE_PASSWORD_RESET_ " required for .deb only
    // uncomment 1 of the 2 following lines & comment out the 3rd line.
    // $enable_password_reset = true;
    // $enable_password_reset = false;
    $enable_password_reset = false;
    // Checks for limiting garbage data?
    $max_node_id_limit = 32;

    /*

    Default router settings - in absence of stated path

    */

    // Default controller and action if none are specified and user is anonymous
    $default_controller = "user";
    $default_action = "login";

    // Default controller and action if none are specified and user is logged in
    $default_controller_auth = "user";
    $default_action_auth = "view";

    // Public profile functionality
    $public_profile_enabled = TRUE;
    $public_profile_controller = "dashboard";
    $public_profile_action = "view";

    /*

    Other

    */

    // Theme location
    $theme = "basic";

    // Error processing
    $display_errors = TRUE;

    // Allow user register in emoncms
    $allowusersregister = TRUE;

    // Enable remember me feature - needs more testing
    $enable_rememberme = TRUE;

    // Skip database setup test - set to false once database has been setup.
    $dbtest = TRUE;

    // Log4PHP configuration
    $log4php_configPath = '/etc/emoncms/emoncms_log4j.xml';
    // interfaces behavior when running with multi organisations and multi users
    $behavior=array(
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
    $author=array(
      'lamba'    => 0,
      'sysadmin' => 1,
      'orgadmin' => 3,
      'viewer'   => 4,
      'designer' => 5
      );
