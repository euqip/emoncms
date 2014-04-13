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

class Org
{
    private $mysqli;

    public function __construct($mysqli,$redis,$rememberme)
    {
        $this->mysqli = $mysqli;
    }

    //---------------------------------------------------------------------------------------
    // Core session methods
    //---------------------------------------------------------------------------------------
    public function create_org($userid,$data)
    {
        $orgname = $this->mysqli->real_escape_string($data['orgname']);
        $longname = $this->mysqli->real_escape_string($data['longname']);
        if ($this->get_id($orgname) != 0) return array('success'=>false, 'message'=>_("This organisation already exists"));
        if ($this->get_id($longname) != 0) return array('success'=>false, 'message'=>_("This organisation already exists"));
        // If we got here the username, password and email should all be valid
        $hash = hash('sha256', $orgname);
        $string = md5(uniqid(mt_rand(), true));
        $salt = substr($string, 0, 3);
        $hash = hash('sha256', $salt . $hash);

        $apikey_write = md5(uniqid(mt_rand(), true));
        $apikey_read = md5(uniqid(mt_rand(), true));
        $sql="INSERT INTO orgs ( orgname, longname, salt ,apikey_read, apikey_write, createbyid, createdate ) VALUES ( '$orgname' , '$longname', '$salt', '$apikey_read', '$apikey_write', $userid, now() );";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when creating organisation"));
        }else{
            return array('success'=>true, 'message'=>_("New organisation ".$longname." created successfuly!"));
        }
    }
    public function delete_org($userid,$id)
    {
        $apikey_write = md5(uniqid(mt_rand(), true));
        $apikey_read = md5(uniqid(mt_rand(), true));
        $sql="UPDATE  orgs SET  delflag = true, deldate= now(), delbyid='$userid', apikey_write='' WHERE ID='$id' ;";
        logitem($sql);
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when deleting organisation"));
        }else{
            return array('success'=>true, 'message'=>_("Organisation "." deleted successfuly!"));
        }
    }

    public function list_orgs()
    {
        $result = $this->mysqli->query("SELECT * FROM orgs WHERE delflag=0");
        $row = $result->fetch_object();
    }


    public function get_org_apikey_read($orgid)
    {
        $orgid = intval($orgid);
        $result = $this->mysqli->query("SELECT `apikey_read` FROM orgs WHERE `id`='$orgid'");
        $row = $result->fetch_object();
        return $row->apikey_read;
    }

    public function get_org_apikey_write($orgid)
    {
        $orgid = intval($orgid);
        $result = $this->mysqli->query("SELECT `apikey_write` FROM orgs WHERE `id`='$orgid'");
        $row = $result->fetch_object();
        return $row->apikey_write;
    }

    public function get_id($orgname)
    {
        if (!ctype_alnum($orgname)) return false;
        $result = $this->mysqli->query("SELECT `id` FROM orgs WHERE `orgname`='$orgname'");
        $row = $result->fetch_array();
        return $row['id'];
    }
}

function logitem($str){
    $handle = fopen("/home/bp/emoncmsdata/db_log.txt", "a");
    fwrite ($handle, $str);
    fclose ($handle);

}
