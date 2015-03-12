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

class User extends Model
{

    private $mysqli;
    private $rememberme;
    private $enable_rememberme = false;
    private $redis;
    private $log;
    private $org;
    private $behavior;
    private $usrdata=array();

    public function __construct($mysqli,$redis,$rememberme,$org)
    {
        parent::__construct();
        //copy the settings value, otherwise the enable_rememberme will always be false.
        global $enable_rememberme, $path, $behavior;
        $this->enable_rememberme = $enable_rememberme;

        $this->mysqli = $mysqli;
        $this->rememberme = $rememberme;

        $this->redis = $redis;
        $this->log = new EmonLogger(__FILE__);

        $this->org = $org;
        $this->behavior = $behavior;
        $this->userdate =array();
        $this->useTable = "users";
    }

    //---------------------------------------------------------------------------------------
    // Core session methods
    //---------------------------------------------------------------------------------------

public function apikey_session($apikey_in)
    {
        $apikey_in = $this->mysqli->real_escape_string($apikey_in);
        $session = array();
        //set defaults
        $session['userid'] = '';
        $session['uorgid'] = '';
        $session['read'] = 0;
        $session['write'] = 0;
        $session['admin'] = 0;
        $session['editmode'] = TRUE;
        $session['lang'] = "en_EN";

        //----------------------------------------------------
        // Check for apikey login
        //----------------------------------------------------
        if($this->redis && $this->redis->exists("writeapikey:$apikey_in"))
        {
            $session['userid'] = $this->redis->get("writeapikey:$apikey_in");
            $session['read'] = 1;
            $session['write'] = 1;
            $session['lang'] = $this->redis->get("userlangkey:$apikey_in");
            $session['orgid']= $this->redis->get("userorgid:$apikey_in");
            if ($session['lang']=='' || $session['orgid']==''){
            $sql = $sqlbase." apikey_write ='$apikey_in'";
            $result = $this->mysqli->query($sql);
                if ($result->num_rows == 1)
                {
                    $row = $result->fetch_array();
                    if ($row['id'] != 0)
                    {
                        //session_regenerate_id();
                        $session['lang'] = $row['language'];
                        $session['orgid'] = $row['orgid'];

                        if ($this->redis) $this->redis->set("writeapikey:$apikey_in",$row['id']);
                        if ($this->redis) $this->redis->set("userlangkey:$apikey_in",$row['language']);
                        if ($this->redis) $this->redis->set("userorgid:$apikey_in",$row['orgid']);
                    }

                }
            }
        }
        else
        {
            //$sqlbase= "SELECT id, language, orgid FROM $this->useTable WHERE ";
            //$sql = $sqlbase." apikey_write ='$apikey_in'";
            //$result = $this->mysqli->query($sql);
            $wcond =" apikey_write ='$apikey_in'";
            $result = $this->get_wcond("id, language, orgid", $wcond);
            if ($result->num_rows == 1)
            {
                $row = $result->fetch_array();
                if ($row['id'] != 0)
                {
                    //session_regenerate_id();
                    $session['userid'] = $row['id'];
                    $session['read'] = 1;
                    $session['write'] = 1;
                    $session['lang'] = $row['language'];
                    $session['orgid'] = $row['orgid'];

                    if ($this->redis) $this->redis->set("writeapikey:$apikey_in",$row['id']);
                    if ($this->redis) $this->redis->set("userlangkey:$apikey_in",$row['language']);
                    if ($this->redis) $this->redis->set("userorgid:$apikey_in",$row['orgid']);
                }
            }
            else
            {
            $sql = $sqlbase." apikey_read ='$apikey_in'";
            $result = $this->mysqli->query($sql);
            if ($result->num_rows == 1)
            {
                $row = $result->fetch_array();
                if ($row['id'] != 0)
                {
                    //session_regenerate_id();
                    $session['userid'] = $row['id'];
                    $session['read'] = 1;
                    $session['write'] = 0;
                    $session['admin'] = 0;
                    $session['editmode'] = TRUE;
                    $session['lang'] = $row['language'];
                }
            }
            }
        }

        //----------------------------------------------------
        return $session;
    }
    public function emon_session_start()
    {
        session_start();
        if ($this->enable_rememberme)
        {
            // if php session exists
            if (!empty($_SESSION['userid'])) {
                // if rememberme emoncms cookie exists but is not valid then
                // a valid cookie is a cookie who's userid, token and persistant token match a record in the db
                if(!empty($_COOKIE[$this->rememberme->getCookieName()]) && !$this->rememberme->cookieIsValid($_SESSION['userid'])) {
                    $this->logout();
                }
            } else {
                $loginresult = $this->rememberme->login();
                if ($loginresult)
                {
                    // Remember me login
                    $_SESSION['userid'] = $loginresult;
                    $_SESSION['read'] = 1;
                    $_SESSION['write'] = 1;
                    // There is a chance that an attacker has stolen the login token, so we store
                    // the fact that the user was logged in via RememberMe (instead of login form)
                    $_SESSION['cookielogin'] = true;
                } else {
                    if($this->rememberme->loginTokenWasInvalid()) {
                        // Stolen
                    }
                }
            }
        }

        if (isset($_SESSION['admin'])) $session['admin'] = $_SESSION['admin']; else $session['admin'] = 0;
        if (isset($_SESSION['orgid'])) $session['orgid'] = $_SESSION['orgid']; else $session['orgid'] = 0;
        if (isset($_SESSION['read'])) $session['read'] = $_SESSION['read']; else $session['read'] = 0;
        if (isset($_SESSION['write'])) $session['write'] = $_SESSION['write']; else $session['write'] = 0;
        if (isset($_SESSION['userid'])) $session['userid'] = $_SESSION['userid']; else $session['userid'] = 0;
        if (isset($_SESSION['lang'])) $session['lang'] = $_SESSION['lang']; else $session['lang'] = '';
        if (isset($_SESSION['username'])) $session['username'] = $_SESSION['username']; else $session['username'] = '';
        if (isset($_SESSION['editmode'])) $session['editmode'] = $_SESSION['editmode']; else $session['editmode'] = 0;
        if (isset($_SESSION['csv_field_separator'])) $session['csv_field_separator'] = $_SESSION['csv_field_separator']; else $session['csv_field_separator'] = $this->behavior['csv_parameters']['csv_field_separator'];
        if (isset($_SESSION['csv_decimal_place_separator'])) $session['csv_decimal_place_separator'] = $_SESSION['csv_decimal_place_separator']; else $session['csv_decimal_place_separator'] = $this->behavior['csv_parameters']['csv_decimal_place_separator'];
        if (isset($_SESSION['csv_thousandsepar_separator'])) $session['csv_thousandsepar_separator'] = $_SESSION['csv_thousandsepar_separator']; else $session['csv_thousandsepar_separator'] = $this->behavior['csv_parameters']['csv_thousandsepar_separator'];
        if (isset($_SESSION['csvdate'])) $session['csvdate'] = $_SESSION['csvdate']; else $session['csvdate'] = $this->behavior['csv_parameters']['csv_dateformat'];
        if (isset($_SESSION['csvtime'])) $session['csvtime'] = $_SESSION['csvtime']; else $session['csvtime'] = $this->behavior['csv_parameters']['csv_timeformat'];

        return $session;
    }


    public function register($username, $password, $email)
    {
        // Input validation, sanitisation and error reporting
        if (!$username || !$password || !$email){
            return array('success'=>false, 'message'=>_("Missing username, password or email parameter"));
        }

        if (!ctype_alnum($username)){
            return array('success'=>false, 'message'=>_("Username must only contain a-z and 0-9 characters"));
        }
        $username = $this->mysqli->real_escape_string($username);
        $password = $this->mysqli->real_escape_string($password);

        if ($this->get_id($username) != 0){
            return array('success'=>false, 'message'=>_("Username already exists"));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return array('success'=>false, 'message'=>_("Email address format error"));
        }

        if (strlen($username) < 4 || strlen($username) > 30){
            return array('success'=>false, 'message'=>_("Username length error"));
        }
        if (strlen($password) < 4 || strlen($password) > 30){
            return array('success'=>false, 'message'=>_("Password length error"));
        }

        // If we got here the username, password and email should all be valid

        $hash = hash('sha256', $password);
        $string = md5(uniqid(mt_rand(), true));
        $salt = substr($string, 0, 3);
        $hash = hash('sha256', $salt . $hash);

        $apikey_write = md5(uniqid(mt_rand(), true));
        $apikey_read = md5(uniqid(mt_rand(), true));
        $orgid = 0;
        $admin = 0;
        $crby = $username.' auto register';
        if ((isset($_SESSION['admin']))  && $_SESSION['admin']==3) {
            $orgid=intval($_SESSION['orgid']);
            // define user by default as viewer, the orgadmin is able to update this
            $admin = 5;
        }
        $sql = "INSERT INTO $this->useTable ( username, password, email, salt ,apikey_read, apikey_write, admin, orgid, createdate, createbyname ) VALUES ( '$username' , '$hash', '$email', '$salt', '$apikey_read', '$apikey_write', '$admin', '$orgid', now(), '$crby' );";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error creating user"));
        }

        // Make the first user an admin
        $id = $this->mysqli->insert_id;
        //if ($userid == 1) $this->mysqli->query("UPDATE users SET admin = 1 WHERE id = '1'");
        if ($id == 1) $this->set_field($id,'1');

        return array('success'=>true, 'userid'=>$id, 'apikey_read'=>$apikey_read, 'apikey_write'=>$apikey_write);
    }
    public function forcenewpwd($id)
    {
        $id = $this->mysqli->real_escape_string($id);
        $this->set_field($id, " changepswd = 1 ");
        $sql="SELECT * FROM $this->useTable  WHERE id = '$id' AND changepswd=1";
        $result = $this->mysqli->query($sql);
        $userData = $result->fetch_object();
        return array('success'=>true, 'userid'=>$userData->id, 'apikey_read'=>$userData->apikey_read, 'apikey_write'=>$userData->apikey_write, 'message'=>_("User will be forced to change password right after next login"));

    }   // end of function

    public function login($username, $password, $remembermecheck)
    {
        $remembermecheck = (int) $remembermecheck;


        // filter out all except for alphanumeric white space and dash
        //if (!ctype_alnum($username))
        $username_out = preg_replace('/[^\w\s-]/','',$username);

        if ($username_out!=$username) return array('success'=>false, 'message'=>_("Username must only contain a-z 0-9 dash and underscore, if you created an account before this rule was in place enter your username without the non a-z 0-9 dash underscore characters to login and feel free to change your username on the profile page."));

        if (!$username || !$password) return array('success'=>false, 'message'=>_("Username or password empty"));
        $username = $this->mysqli->real_escape_string($username);
        $password = $this->mysqli->real_escape_string($password);
        $sql = "SELECT id,username, password,admin,salt,language, changepswd, orgid, csvparam, csvdate, csvtime FROM $this->useTable WHERE username = '$username'";
        $result = $this->mysqli->query($sql);

        if ($result->num_rows < 1) return array('success'=>false, 'message'=>_("Incorrect username - password, if you are sure its correct try clearing your browser cache"));

        $userData = $result->fetch_object();
        $hash = hash('sha256', $userData->salt . hash('sha256', $password));

        if ($hash != $userData->password)
        {
            return array('success'=>false, 'message'=>_("Incorrect username - password, if you are sure its correct try clearing your browser cache"));
        }
        else
        {
            $id=$userData->id;
            $this->set_lastlogin($id);
            $this->org->lastlogin($userData->orgid);
            //if ($userdata->forcenewpwd =1)
            $this->generate_session($userData);
            if ($this->enable_rememberme) {
                if ($remembermecheck==true) {
                    $this->rememberme->createCookie($userData->id);
                } else {
                    $this->rememberme->clearCookie();
                }
            }

            return array('success'=>true, 'message'=>_("Login successful"));
        }
    }

    private function generate_session($userData){
            $dateformats = $this->get_available_dateformats();
            $timeformats = $this->get_available_timeformats();
            $scvseparators = $this->get_available_separators();
            preg_match_all('/\(.\)/',  $scvseparators[$userData->csvparam], $matches, PREG_OFFSET_CAPTURE, 8);

            session_regenerate_id();
            $_SESSION['userid'] = $userData->id;
            $_SESSION['username'] = $userData->username;
            $_SESSION['read'] = 1;
            $_SESSION['write'] = 1;
            $_SESSION['admin'] = $userData->admin;
            $_SESSION['orgid'] = $userData->orgid;
            $_SESSION['lang'] = $userData->language;
            $_SESSION['editmode'] = TRUE;
            $_SESSION['csv_field_separator'] = substr($matches[0][0][0],1,1);
            $_SESSION['csv_decimal_place_separator'] =  substr($matches[0][1][0],1,1);
            $_SESSION['csv_thousandsepar_separator'] =  substr($matches[0][2][0],1,1);
            $_SESSION['csvdate'] = $dateformats[$userData->csvdate];
            $_SESSION['csvtime'] = $timeformats[$userData->csvtime];
            return $userData;
    }

    // Authorization API. returns user write and read apikey on correct username + password
    // This is useful for using emoncms with 3rd party applications

    public function get_apikeys_from_login($username, $password)
    {
        if (!$username || !$password) return array('success'=>false, 'message'=>_("Username or password empty"));
        $username_out = preg_replace('/[^\w\s-]/','',$username);

        if ($username_out!=$username) return array('success'=>false, 'message'=>_("Username must only contain a-z 0-9 dash and underscore"));

        $username = $this->mysqli->real_escape_string($username);
        $password = $this->mysqli->real_escape_string($password);

        $result = $this->mysqli->query("SELECT id,password,admin,salt,language, apikey_write,apikey_read FROM $this->useTable WHERE username = '$username'");

        if ($result->num_rows < 1) return array('success'=>false, 'message'=>_("Incorrect authentication"));

        $userData = $result->fetch_object();
        $hash = hash('sha256', $userData->salt . hash('sha256', $password));

        if ($hash != $userData->password)
        {
            return array('success'=>false, 'message'=>_("Incorrect authentication"));
        }
        else
        {
            return array('success'=>true, 'apikey_write'=>$userData->apikey_write, 'apikey_read'=>$userData->apikey_read);
        }
    }

    public function logout()
    {
        if ($this->enable_rememberme) $this->rememberme->clearCookie(true);
        $_SESSION['userid'] = 0;
        $_SESSION['read'] = 0;
        $_SESSION['write'] = 0;
        $_SESSION['admin'] = 0;
        $_SESSION['orgid'] = 0;
        session_regenerate_id(true);
        session_destroy();
    }



    public function change_password($userid, $old, $new)
    {
        $userid = intval($userid);
        $old = $this->mysqli->real_escape_string($old);
        $new = $this->mysqli->real_escape_string($new);

        if (strlen($old) < 4 || strlen($old) > 30) return array('success'=>false, 'message'=>_("Password length error"));
        if (strlen($new) < 4 || strlen($new) > 30) return array('success'=>false, 'message'=>_("Password length error"));

        // 1) check that old password is correct
        $result = $this->mysqli->query("SELECT password, salt FROM $this->useTable WHERE id = '$userid'");
        $row = $result->fetch_object();
        $hash = hash('sha256', $row->salt . hash('sha256', $old));

        if ($hash == $row->password)
        {
            // 2) Save new password
            $hash = hash('sha256', $new);
            $string = md5(uniqid(rand(), true));
            $salt = substr($string, 0, 3);
            $hash = hash('sha256', $salt . $hash);
            $this->mysqli->query("UPDATE $this->useTable SET password = '$hash', salt = '$salt' WHERE id = '$userid'");
            return array('success'=>true);
        }
        else
        {
            return array('success'=>false, 'message'=>_("Old password incorect"));
        }
    }

    public function passwordreset($username,$email)
    {
        $username_out = preg_replace('/[^\w\s-]/','',$username);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return array('success'=>false, 'message'=>_("Email address format error"));

        $result = $this->mysqli->query("SELECT * FROM $this->useTable WHERE `username`='$username_out' AND `email`='$email'");

        if ($result->num_rows==1)
        {
            $row = $result->fetch_array();
            $username =$row['username'];
            $lang =$row['language'];

            $userid = $row['id'];
            if ($userid>0)
            {
                // Generate new random password
                $newpass = hash('sha256',md5(uniqid(rand(), true)));
                $newpass = substr($newpass, 0, 10);

                // Hash and salt
                $hash    = hash('sha256', $newpass);
                $string  = md5(uniqid(rand(), true));
                $salt    = substr($string, 0, 3);
                $hash    = hash('sha256', $salt . $hash);

                // Save hash and salt
                $this->mysqli->query("UPDATE $this->useTable SET password = '$hash', salt = '$salt' WHERE id = '$userid'");

                //------------------------------------------------------------------------------
                global $enable_password_reset;
                if ($enable_password_reset==true)
                {
                    global $smtp_email_settings, $PHPMailer_settings;

                    // include SwiftMailer. One is the path from a PEAR install,
                    // the other from libphp-swiftmailer.
                    // the swhit mailer library is installe in emoncms directory
                    // use github to cole folder
                    $filepath="PHPMailer/PHPMailerAutoload.php";
                    $PHPMailer = @include_once ($filepath);
                    if (!$PHPMailer) {
                        $this->log->info("Could not include PHPMailer!");
                        return array('success'=>false, 'message'=>_("Could not find PHPMailer - cannot proceed"));
                        die();
                    }

                    $mail = new PHPMailer;

                    $mail->isSMTP();                                       // Set mailer to use SMTP
                    $mail->Host       = $PHPMailer_settings['host'];       // Specify main and backup server
                    $mail->SMTPAuth   = $PHPMailer_settings['auth'];       // Enable SMTP authentication
                    $mail->Username   = $PHPMailer_settings['username'];   // SMTP username
                    $mail->Password   = $PHPMailer_settings['password'];   // SMTP password
                    $mail->SMTPSecure = $PHPMailer_settings['encryption']; // Enable encryption, 'ssl' also accepted
                    $mail->Port       = $PHPMailer_settings['port'];       // Enable encryption, 'ssl' also accepted

                    $mail->From       = $PHPMailer_settings['from'];
                    $mail->FromName   = $PHPMailer_settings['fromname'];
                    $mail->addAddress($email);                             // Add a recipient
                    $mail->addAddress('benoit.pique@base.be');             // Name is optional
                    $mail->addReplyTo($PHPMailer_settings['from'], $PHPMailer_settings['fromname']);
                    $mail->addCC('');
                    $mail->addBCC($PHPMailer_settings['tobcc']);

                    $mail->WordWrap   = 50;                                // Set word wrap to 50 characters
                    $mail->addAttachment('');                              // Add attachments
                    $mail->addAttachment('');                              // Optional name
                    $mail->isHTML(true);                                   // Set email format to HTML

                    // select the user language to build message
                    setlocale( LC_MESSAGES, $lang.'.utf8');
                    mb_internal_encoding('UTF-8');
                    $mail->Subject = mb_encode_mimeheader(_('Emoncms password reset'));

                    $mail->Body    = _('Hi, Your new password to acceed EMONCMS is now set to').' <b>'.$newpass.'</b>';
                    $mail->AltBody = _('Your personnal password is changed to').' : '.$newpass;


                    if(!$mail->send()) {
                        return array('success'=>false, 'message'=>_("Password recovery email not sent!"));
                    } else {
                        // Sent email with $newpass to $email
                        return array('success'=>true, 'message'=>_("Password recovery email sent!"));
                    }
                    $this->log->info("Sent ".$result." email(s)");
                }
                //------------------------------------------------------------------------------
            }
        }
        return array('success'=>false, 'message'=>_("An error occured"));
    }



    //---------------------------------------------------------------------------------------
    // Get by userid methods
    //---------------------------------------------------------------------------------------
    /*
    public function get_convert_status($id)
    {
        $id = intval($id);
        $result = $this->mysqli->query("SELECT `convert` FROM users WHERE id = '$id';");
        $row = $result->fetch_array();
        return array('convert'=>(int)$row['convert']);
    }
*/
    public function get_username($id)
    {
        $this->get($id,'username');
        return $this->usrdata->username;
    }

    public function get_apikey_read($id)
    {
        $this->get($id,'apikey_read');
        return $this->usrdata->apikey_read;
    }

    public function get_apikey_write($id)
    {
        $this->get($id,'apikey_write');
        return $this->usrdata->apikey_write;
    }

    public function get_lang($id)
    {
        $this->get($id,'language');
        return $this->usrdata->language;
    }

    public function get_timezone($id)
    {
        $this->get($id,'timezone');
        return $this->usrdata->timezone;
    }

    public function get_salt($id)
    {
        $this->get($id,'salt');
        return $this->usrdata->salt;
    }

    public function get_partial($id)
    {
        $flds= " id, orgid, username, email, apikey_write, apikey_read, admin, gravatar, name, location, timezone, language, bio, changepswd, csvparam, csvdate, csvtime";
        return $this->get ($id, $flds);
    }
    //---------------------------------------------------------------------------------------
    // Get by other paramater methods
    //---------------------------------------------------------------------------------------

    public function get_id($username)
    {
        if (!ctype_alnum($username)) return false;

        $result = $this->mysqli->query("SELECT id FROM $this->useTable WHERE username = '$username';");
        $row = $result->fetch_array();
        return $row['id'];
    }

    public function get_wcond($flds,$wcond)
    {
        $result = $this->mysqli->query("SELECT $flds FROM $this->useTable WHERE $wcond;");
        return $this->mysqli->query($sql);
    }

    //---------------------------------------------------------------------------------------
    // Set by id methods
    //---------------------------------------------------------------------------------------

    /*
    public function set_convert_status($id)
    {
        $id = intval($id);
        $this->mysqli->query("UPDATE users SET `convert` = '1' WHERE id='$id'");
        return array('convert'=>1);
    }
    */

    public function set_user_lang($id, $lang)
    {
        $this->set_field ($id," language = '".$lang."'");
    }

    public function set_timezone($id,$timezone)
    {
        $this->set_field ($id," timezone = '".intval($timezone)."'");
    }
    public function set_orgid($id,$orgid)
    {
        $this->set_field ($id," orgid = '".intval($orgid)."'");
    }
    public function set_lastlogin($id)
    {
        $this->set_field ($id," lastlogin =now()");
    }

    public function change_email($id, $email)
    {
        if (isset($_SESSION['cookielogin']) && $_SESSION['cookielogin']==true) return array('success'=>false, 'message'=>_("As your using a cookie based remember me login, please logout and log back in to change email"));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return array('success'=>false, 'message'=>_("Email address format error"));
        $this->set_field ($id," email = '".$email."'");

        return array('success'=>true, 'message'=>_("Email updated"));
    }

    public function change_username($id, $username)
    {
        if (isset($_SESSION['cookielogin']) && $_SESSION['cookielogin']==true) return array('success'=>false, 'message'=>_("As your using a cookie based remember me login, please logout and log back in to change username"));

        if (strlen($username) < 4 || strlen($username) > 30) return array('success'=>false, 'message'=>_("Username length error"));

        if (!ctype_alnum($username)) return array('success'=>false, 'message'=>_("Username must only contain a-z and 0-9 characters"));

        // check first that this new username does not exist

        //$result = $this->mysqli->query("SELECT id FROM users WHERE username = '$username'");
        //$row = $result->fetch_array();

        if (!$this->get_id($username))
        {
            $this->set_field ($id," username = '".$username."'");
            return array('success'=>true, 'message'=>_("Username updated"));
        }
        else
        {
            return array('success'=>false, 'message'=>_("Username already exists"));
        }
    }

    // Generates a new random read apikey
    public function new_apikey_read($id)
    {
        $apikey = md5(uniqid(mt_rand(), true));
        $this->set_field ($id," apikey_read = '".$apikey."'");
        return $apikey;
    }

    // Generates a new random write apikey
    public function new_apikey_write($id)
    {
        $apikey = md5(uniqid(mt_rand(), true));
        $this->set_field ($id," apikey_write = '".$apikey."'");
        return $apikey;
    }

    private function set_field($id,$fld)
    {
        $id = intval($id);
        $this->mysqli->query("UPDATE $this->useTable SET $fld WHERE id='$id'");
    }

    //---------------------------------------------------------------------------------------
    // Special methods
    //---------------------------------------------------------------------------------------

    public function get($id, $flds= "*")
    {
        $id = intval($id);
        $tmpdata =array();
        if (!isset($this->usrdata->id) || ($this->usrdata->id<>$id)){
            $sql = "SELECT  * FROM $this->useTable WHERE id=$id";
            $result = $this->mysqli->query($sql);
            $this->usrdata = $result->fetch_object();
        }
        switch ($flds){
            case "*":
                $tmpdata= $this->usrdata;
                break;
            default:
                //explode flds to find out witch field to return
                $flds= str_replace (' ','',$flds);
                $fld = explode (',',$flds);
                foreach ($fld as $v){
                    $tmpdata[$v]=$this->usrdata->$v;
                }
                break;
        }
        return $tmpdata;
    }

    public function set($id,$data)
    {
        // Validation
        //var_dump($data);
        $id   = intval($id);
        $gravatar = '  gravatar      = "'.preg_replace('/[^\w\s-.@]/','',$data->gravatar).'"';
        $name     = ', name          = "'.preg_replace('/[^\s\p{L}]/u','',$data->name).'"';
        $location = ', location      = "'.preg_replace('/[^\s\p{L}]/u','',$data->location).'"';
        $timezone = ', timezone      = "'.intval($data->timezone).'"';
        $language = ', language      = "'.preg_replace('/[^\w\s-.]/','',$data->language).'"';
        $bio      = ', bio           = "'.preg_replace('/[^\w\s-.]/','',$data->bio).'"';
        $orgid    = '';
        $admin    = '';
        //reserved action to system admin
        $orgid    = ', orgid         = "'.intval($data->orgid).'"';
        //reserved action to the orgadmin and system admin
        $admin    = ', admin         = "'.intval($data->admin).'"';
        $csvparam = ', csvparam      = "'.intval($data->csvparam).'"';
        $csvdate  = ', csvdate       = "'.intval($data->csvdate).'"';
        $csvtime  = ', csvtime       = "'.intval($data->csvtime).'"';
        $sql = "UPDATE $this->useTable SET $gravatar
                                 $name
                                 $location
                                 $timezone
                                 $language
                                 $bio
                                 $orgid
                                 $admin
                                 $csvparam
                                 $csvdate
                                 $csvtime
                                 WHERE id = '$id'";
        //change session language only if done by user!
        //check if $id == currentuser
        $lang = preg_replace('/[^\w\s-.]/','',$data->language);
        if($id==intval($_SESSION['userid']) && ($_SESSION['lang']<>$lang)){
            $_SESSION['lang'] = $lang;
        }
        $result   = $this->mysqli->query($sql);
        //refresh session
        $result1 = $this->get($udserid);
        //$sql = "SELECT * from users where id = '$userid'";
        //$result1 = $this->mysqli->query($sql);
        if ($result1->num_rows == 1) {
            $userData = $result1->fetch_object();
            //regenerate the session to inclue all modified params
            $this->generate_session($userData);
        }

    }

/**
 * [get_available_roles description]
 * @return [array] [possible roles for the logged in user]
 */
    public function get_available_roles()
    {
        $roles= array();
        if (isset($_SESSION['admin'])){
            switch (intval($_SESSION['admin'])){
                case 1: // sysstem administrator
                    $roles=array(
                        '0'=>_('lambda'),
                        '1'=>_('System administrator'),
                        '3'=>_('organisation admin'),
                        '4'=>_('designer'),
                        '5'=>_('viewer')
                        );
                    break;
                case 3: // organisation administrator
                    $roles=array(
                        '3'=>_('organisation admin'),
                        '4'=>_('designer'),
                        '5'=>_('viewer')
                        );
                    break;
                default: // simple user with design or view authorasations
                    $roles=array(
                        intval($_SESSION['admin']) =>_('user')
                        );
                    break;
            }
        }
        return $roles;
    }
/**
 * [get_available_date formats]
 * @return [array] [possible date formats]
 */
    public function get_available_dateformats()
    {
        $dateformats=array(
            '0'=>'%d/%m/%Y',
            '1'=>'%m/%d/%Y',
            '3'=>'%Y%m%d',
            '4'=>'%D, %d %M %Y',
            );
        return $dateformats;
    }
/**
 * [get_available_time formats]
 * @return [array] [possible date formats]
 */
    public function get_available_timeformats()
    {
        $timeformats=array(
            '0'=>'%H:%i:%s',
            '1'=>'%H%i%s',
            '2'=>'%H:%i',
           );
        return $timeformats;
    }
/**
 * [get_available_separators]
 * @return [array] [possible separators kit]
 */
    public function get_available_separators()
    {
        $separators=array(
            '0'=>_('European').' -> '._('semicolumn').' (;) - '._('comma').' (,) - '._('dot').' (.)',
            '1'=>_('American').' -> '._('comma').' (,) - '._('dot').' (.) - '._('space').' ( )',
            '2'=>_('Hybrid').' -> '._('vertical bar').' (|) - '._('dot').' (.) - '._('comma').' (,)',
           );
        return $separators;
    }
    public function toggle_user($userid,$username,$wcond){
        $sql = "SELECT delflag from $this->useTable WHERE ".$wcond;
        $result = $this->mysqli->query($sql);
        $data = $result->fetch_object();
        $flag = ($data->delflag<>0) ?0:1;
        $sql = "update $this->useTable set delflag = ".$flag.", delbyid = ".$userid.", delbyname = '".$username."', deldate =now() where ".$wcond;
        $this->mysqli->query($sql);
        return $sql;
    }
}
