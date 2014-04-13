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
        $sql="INSERT INTO orgs ( orgname, longname, salt ,apikey_read, apikey_write ) VALUES ( '$orgname' , '$longname', '$salt', '$apikey_read', '$apikey_write' );";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when creating organisation"));
        }else{
            return array('success'=>true, 'message'=>_("New organisation ".$longname." created successfuly!"));
        }
    }

    public function list_orgs()
    {
        $result = $this->mysqli->query("SELECT * FROM orgs WHERE 1");
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