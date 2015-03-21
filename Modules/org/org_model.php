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

class Org extends Model
{
    private $mysqli;
    private $tbl = "orgs";
    private $modeldata=array();

    public function __construct($mysqli,$redis,$rememberme)
    {
        $this->mysqli = $mysqli;
        $this->tbl = "orgs";
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
        $flds= "delflag = true, deldate= now(), delby= '$username', delbyid='$userid', apikey_write='xxx'";
        if ($this->set($flds,$id)){
            return array('success'=>true, 'message'=>_("Organisation "." deleted successfuly!"));
        } else {
            return array('success'=>false, 'message'=>_("Error when deleting organisation"));
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
    public function update_org($userid, $username, $id, $data)
    {
        $id = $this->mysqli->real_escape_string($id);

        $data = json_decode(stripslashes($data));
        $array = array();
        $reg= REGEX_STRING;

        // Repeat this line changing the field name to add data that can be updated:
        if (isset($data->longname)) $array[] = "`longname` = '".preg_replace($reg,'',$data->longname)."'";
        if (isset($data->orgname)) $array[]  = "`orgname`  = '".preg_replace($reg,'',$data->orgname)."'";
        if (isset($data->country)) $array[]  = "`country`  = '".preg_replace($reg,'',$data->country)."'";
        if (isset($data->language)) $array[] = "`language` = '".preg_replace($reg,'',$data->language)."'";
        if (isset($data->logo)) $array[]     = "`logo`     = '".preg_replace($reg,'',$data->logo)."'";
        if (isset($data->address)) $array[]  = "`address`  = '".preg_replace($reg,'',$data->address)."'";
        if (isset($data->zip)) $array[]      = "`zip`      = '".preg_replace($reg,'',$data->zip)."'";
        if (isset($data->city)) $array[]     = "`city`     = '".preg_replace($reg,'',$data->city)."'";
        if (isset($data->state)) $array[]    = "`state`    = '".preg_replace($reg,'',$data->state)."'";
        if (isset($data->location)) $array[] = "`location` = '".preg_replace($reg,'',$data->location)."'";
        if (isset($data->timezone)) $array[] = "`timezone` = '".preg_replace($reg,'',$data->timezone)."'";
        // Convert to a comma seperated string for the mysql query
        $fieldstr = implode(",",$array);
        if ($this->set($fieldstr,$id)){
            return array('success'=>true, 'message'=>_("Organisation "." updated successfuly!"));
        }else{
            return array('success'=>false, 'message'=>_("Error when updating organisation"));
        }


        /*
        $sql = "UPDATE ".$this->tbl." SET ".$fieldstr." WHERE `id` = '$id'";
        if (!$this->mysqli->query($sql)) {
            return array('success'=>false, 'message'=>_("Error when updating organisation"));
        }else{
            return array('success'=>true, 'message'=>_("Organisation "." updated successfuly!"));
        }
        */
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
        if((isset($_SESSION['admin'])) && ($_SESSION['admin']==1)){
            $result = $this->get_wcond('id, orgname as toshow','delflag=0');
        } else {
            $result = $this->get_wcond('id, orgname as toshow','delflag=0 and id ='.$this->modeldata->id);
        }
        while ($row = $result->fetch_object()) $data[] = $row;
        return $data;
    }
    public function list_orgs()
    {
        $result = $this->get_wcond('id, orgname','delflag=0');
        $row = $result->fetch_object();
    }


    public function get_org_apikey_read($orgid)
    {
        return $this->get($id,'apikey_read');
    }

    public function get_org_apikey_write($orgid)
    {
        return $this->get($id,'apikey_write');
    }

    public function lastlogin($id)
    {
        return $this->set('lastuse = now()',$id);
    }

    public function updaterecord($userid,$data)
    {
        // Validation
        //var_dump($data);
        $userid   = intval($userid);  // id is user id
        $id    = intval($data->id);
        $lang  = preg_replace('/[^\w\s-.]/','',$data->language);
        $flds  = '';
        $flds .= '  logo      = "' .preg_replace('/[^\w\s-.@]/','',$data->logo).'"';
        $flds .= ', orgname   = "' .preg_replace(REGEX_STRING_ACCENT,'',$data->orgname).'"';
        $flds .= ', longname  = "' .preg_replace(REGEX_STRING_ACCENT,'',$data->longname).'"';
        $flds .= ', street    = "' .preg_replace(REGEX_STRING_ACCENT,'',$data->street).'"';
        $flds .= ', zip       = "' .preg_replace(REGEX_ALPHA_NUM,'',$data->zip).'"';
        $flds .= ', city      = "' .preg_replace(REGEX_STRING_ACCENT,'',$data->city).'"';
        $flds .= ', country   = "' .preg_replace(REGEX_STRING_ACCENT,'',$data->country).'"';
        $flds .= ', state     = "' .preg_replace(REGEX_STRING_ACCENT,'',$data->state).'"';
        $flds .= ', location  = "' .preg_replace(REGEX_NUMERIC,'',$data->location).'"';
        $flds .= ', timezone  = "' .intval($data->timezone).'"';
        $flds .= ', language  = "' .$lang.'"';
        //reserved action to the orgadmin and system admin
        $flds .= ', csvparam  = "' .intval($data->csvparam).'"';
        $flds .= ', csvdate   = "' .intval($data->csvdate).'"';
        $flds .= ', csvtime   = "' .intval($data->csvtime).'"';
        //change session language only if done by user!
        //check if $id == currentuser
        if($id==intval($_SESSION['userid']) && ($_SESSION['lang']<>$lang)){
            $_SESSION['lang'] = $lang;
        }
        $this->set($flds,$id);
        //refresh session
        $result1 = $this->get($id);
    }

    private function set ($flds, $id){
        $id = intval($id);
        $sql = "UPDATE $this->tbl SET $flds WHERE id = '$id'";
        //var_dump($sql);
        $result =  $this->mysqli->query($sql);
        $this->stamp_record($id);
        return $result;
    }

    private function stamp_record($id){
        if(isset($_SESSION['userid'])){
        $sql = "UPDATE $this->tbl SET updtbyid = '".$_SESSION['userid']."', updtbyname = '".$_SESSION['username']."', updtdate= now() WHERE id='$id'";
        $this->mysqli->query($sql);
        }
    }
    public function get_partial($id)
    {
        $flds= " id, orgname, apikey_write, apikey_read, logo, longname, street, zip, city, state, country, location, timezone, language,csvparam,csvdate,csvtime";
        return $this->get ($id, $flds);
    }



    private function get($id, $flds= "*")
    {
        $id = intval($id);
        $tmpdata =array();
        if (!isset($this->modeldata->id) || ($this->modeldata->id<>$id)){
            $sql = "SELECT  *, id as modelid  FROM ".$this->tbl." WHERE id=$id";
            $result = $this->mysqli->query($sql);
            $this->modeldata = $result->fetch_object();
        }
        switch ($flds){
            case "*":
                $tmpdata= $this->modeldata;
                break;
            default:
                //explode flds to find out witch field to return
                $flds= str_replace (' ','',$flds);
                $fld = explode (',',$flds);
                foreach ($fld as $v){
                    $tmpdata[$v]=$this->modeldata->$v;
                }
                // when reading only one field dont not return an array but the field
                if (count($tmpdata)==1) return $tmpdata[$flds];
                break;
        }
        return $tmpdata;
    }

    public function get_id($name)
    {
        if (!ctype_alnum($name)) return false;
        $result = $this->get_wcond('id',"orgname = '$name'");
        $result = $result->fetch_object();
        if (!is_null($result)){
            return $result->id;
        }
        return false;
    }

    public function get_wcond($flds,$wcond)
    {
        $sql="SELECT $flds FROM $this->tbl WHERE $wcond;";
        //echo $sql;
        $result =  $this->mysqli->query($sql);
        return $result;
    }

}