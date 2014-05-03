<?php

    /*

    Database connection settings

    */

    $username = "_DB_USER_";
    $password = "_DB_PASSWORD_";
    $server   = "_DB_HOST_";
    $database = "_DATABASE_";

    $redis_enabled = true;

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
            //'datadir'=>'/home/username/emoncmsdata/phpfiwa/'
        ),
        'phpfina'=>array(
            //'datadir'=>'/home/username/emoncmsdata/phpfina/'
        ),
        'phptimeseries'=>array(
            //'datadir'=>'/home/username/emoncmsdata/phptimeseries/'
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


    $enable_password_reset = _ENABLE_PASSWORD_RESET_;

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
        'orgletter'=> "ucase(LEFT(orgname,1)) as letter"
        //orgletter MUST be present but may be a constant
        // see userletter  above and apply same rules, on countries for example
        //'orgletter'=> "country as letter" will group organisations by countries

        );
