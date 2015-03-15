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
    private $tbl = "orgs";

    public function __construct($mysqli,$redis,$rememberme)
    {
        $this->mysqli = $mysqli;
    }

    //---------------------------------------------------------------------------------------
    // Core session methods
    //---------------------------------------------------------------------------------------
/**
 * [create_org description]
 * @param  integer $userid   the user ID
 * @param  string  $username the user real name
 * @param  array   $data     organisation mandatory data
 * @return array             creation result
 */
    public function create_org($userid, $username, $data)
    {
        $orgname = $this->mysqli->real_escape_string($data['orgname']);
        $longname = $this->mysqli->real_escape_string($data['longname']);
        if ($this->get_id($orgname) != 0) return array('success'=>false, 'message'=>$orgname._(" organisation already exists"));
        if ($this->get_id($longname) != 0) return array('success'=>false, 'message'=>$longname._(" organisation already exists"));
        // If we got here the username, password and email should all be valid
        $hash = hash('sha256', $orgname);
        $string = md5(uniqid(mt_rand(), true));
        $salt = substr($string, 0, 3);
        $hash = hash('sha256', $salt . $hash);

        $apikey_write = md5(uniqid(mt_rand(), true));
        $apikey_read = md5(uniqid(mt_rand(), true));
        $sql="INSERT INTO ".$this->tbl." ( orgname, longname, salt ,apikey_read, apikey_write, createbyid, createbyname, createdate ) VALUES ( '$orgname' , '$longname', '$salt', '$apikey_read', '$apikey_write', $userid, '$username', now() );";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when creating organisation")." ".$orgname);
        }else{
            return array('success'=>true, 'message'=>_("New organisation ".$orgname." - ".$longname." created successfuly!"));
        }
    }
    /**
     * [delete_org description]
     * @param  integer $userid   the user ID
     * @param  string  $username the user real name
     * @param  integer $id       organisation ID to delete
     * @return array             creation result
    */
    public function delete_org($userid, $username, $id)
    {
        $id = $this->mysqli->real_escape_string($id);
        $sql="UPDATE  ".$this->tbl." SET  delflag = true, deldate= now(), delby= '$username', delbyid='$userid', apikey_write='' WHERE ID='$id' ;";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when deleting organisation"));
        }else{
            return array('success'=>true, 'message'=>_("Organisation "." deleted successfuly!"));
        }
    }

    /**
     * [update_org description]
     * @param  integer $userid   the user ID
     * @param  string  $username the user real name
     * @param  integer $id       organisation ID to update
     * @param  array   $id       organisation fields to update
     * @return array             update result
    */
    public function update_org($userid, $username, $id, $fields)
    {
        $id = $this->mysqli->real_escape_string($id);

        $fields = json_decode(stripslashes($fields));
        $array = array();
        $reg= REGEX_STRING;

        // Repeat this line changing the field name to add fields that can be updated:
        if (isset($fields->longname)) $array[] = "`longname` = '".preg_replace($reg,'',$fields->longname)."'";
        if (isset($fields->orgname)) $array[] = "`orgname` = '".preg_replace($reg,'',$fields->orgname)."'";
        if (isset($fields->country)) $array[] = "`country` = '".preg_replace($reg,'',$fields->country)."'";
        if (isset($fields->language)) $array[] = "`language` = '".preg_replace($reg,'',$fields->language)."'";
        if (isset($fields->logo)) $array[] = "`logo` = '".preg_replace($reg,'',$fields->logo)."'";
        if (isset($fields->address)) $array[] = "`address` = '".preg_replace($reg,'',$fields->address)."'";
        if (isset($fields->zip)) $array[] = "`zip` = '".preg_replace($reg,'',$fields->zip)."'";
        if (isset($fields->city)) $array[] = "`city` = '".preg_replace($reg,'',$fields->city)."'";
        if (isset($fields->state)) $array[] = "`state` = '".preg_replace($reg,'',$fields->state)."'";
        if (isset($fields->location)) $array[] = "`location` = '".preg_replace($reg,'',$fields->location)."'";
        if (isset($fields->timezone)) $array[] = "`timezone` = '".preg_replace($reg,'',$fields->timezone)."'";
        // Convert to a comma seperated string for the mysql query
        $fieldstr = implode(",",$array);
        $sql = "UPDATE ".$this->tbl." SET ".$fieldstr." WHERE `id` = '$id'";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when updating organisation"));
        }else{
            return array('success'=>true, 'message'=>_("Organisation "." updated successfuly!"));
        }
    }

/**
 * list_organisations gives the list of available organisations
 * the list is only available to the system administrators,
 * other users receive only their own organisation
 * @return json list of not deleted organisations
 */
    public function list_orgnames()
    {
        $data=array();
        $sql = "SELECT id, orgname as toshow FROM orgs WHERE delflag=0";
        if((isset($_SESSION['admin'])) && ($_SESSION['admin']==1)){
            $result = $this->mysqli->query($sql);
            while ($row = $result->fetch_object()) $data[] = $row;
        } else {
            $sql = $sql." and id =".intval($_SESSION['orgid']);
            $result = $this->mysqli->query($sql);
            while ($row = $result->fetch_object()) $data[] = $row;
        }
        return $data;
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
    public function lastlogin($orgid)
    {
            $result = $this->mysqli->query("UPDATE orgs SET lastuse =now() WHERE id = '$orgid'");
            return $result;
    }

}