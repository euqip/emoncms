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
        //copy the settings value, otherwise the enable_rememberme will always be false.

        $this->mysqli = $mysqli;
    }

    //---------------------------------------------------------------------------------------
    // Core session methods
    //---------------------------------------------------------------------------------------
    public function create_org($orgname)
    {
        $orgname = $this->mysqli->real_escape_string($orgname);
        if ($this->get_id($orgname) != 0) return array('success'=>false, 'message'=>_("this organisation already exists"));
        // If we got here the username, password and email should all be valid

        $hash = hash('sha256', $orgname);
        $string = md5(uniqid(mt_rand(), true));
        $salt = substr($string, 0, 3);
        $hash = hash('sha256', $salt . $hash);

        $apikey_write = md5(uniqid(mt_rand(), true));
        $apikey_read = md5(uniqid(mt_rand(), true));
        if (!$this->mysqli->query("INSERT INTO orgs ( orgname, salt ,apikey_read, apikey_write ) VALUES ( '$orgname' , '$salt', '$apikey_read', '$apikey_write' );")) {
            return array('success'=>false, 'message'=>_("Error when creating organisation"));
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
