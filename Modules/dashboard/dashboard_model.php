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

/*
 * Create a new user dashboard
 *
 */
class Dashboard
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function create($userid)
    {
        $userid = (int) $userid;
        $this->mysqli->query("INSERT INTO dashboard (`userid`,`alias`) VALUES ('$userid','')");
        return $this->mysqli->insert_id;
    }

    public function delete($id,$cond)
    {
        //* dashboard deletion only available to owner, system admin, org admin
        $id = (int) $id;
        $sql = "DELETE FROM dashboard WHERE id = $id and $cond";
        $result = $this->mysqli->query($sql);
        logitem ($admin. '   '.$sql);
        return $result;
    }

    public function dashclone($userid, $oid, $id)
    {
        $userid = (int) $userid;
        $id = (int) $id;

        // Get content, name and description from origin dashboard
        $result = $this->mysqli->query("SELECT content,name,description,orgid FROM dashboard WHERE userid = '$userid' AND id='$id'");
        $row = $result->fetch_array();

        // Name for cloned dashboard
        $name = $row['name']._(' clone');
        //$orgid = $session['orgid'];

        $this->mysqli->query("INSERT INTO dashboard (`userid`, `orgid`,`content`,`name`,`description`) VALUES ('$userid','$oid','{$row['content']}','$name','{$row['description']}')");

        return $this->mysqli->insert_id;
    }

    public function get_list($userid, $orgid, $public, $published)
    {
        $userid = (int) $userid;
        // need to change conditions to be able to filter public and published

        $qB = ""; $qC = "";$oid='';
        if ($public==true) $qB = " and public=1";
        if ($published==true) $qC = " and published=1";
        if ($orgid==true) $oid = " or (orgid=$orgid and published=1)";
        $owner = "IF(userid=".$userid.",true,false) as mine";
        $sql="SELECT id, name, alias, description, main, published, public, showdescription,".$owner." FROM dashboard WHERE userid='$userid'".$qB.$qC.$oid;
logitem ($sql);
        $result = $this->mysqli->query($sql);
        $list = array();
        while ($row = $result->fetch_object())
        {
        $list[] = array (
            'id' => (int) $row->id,
            'name' => $row->name,
            'alias' => $row->alias,
            'showdescription' => (bool) $row->showdescription,
            'description' => $row->description,
            'main' => (bool) $row->main,
            'published'=> (bool) $row->published,
            'public'=> (bool) $row->public,
            'mine'=> (bool) $row->mine
        );
        }
        return $list;
    }

    public function set_content($userid, $id, $content, $height)
    {
        $userid = (int) $userid;
        $id = (int) $id;
        $height = (int) $height;
        $content = $this->mysqli->real_escape_string($content);

        //echo $content;

        $result = $this->mysqli->query("SELECT * FROM dashboard WHERE userid = '$userid' AND id='$id'");
        $row = $result->fetch_array();
        if ($row) $this->mysqli->query("UPDATE dashboard SET content = '$content', height = '$height' WHERE userid='$userid' AND id='$id'");

        return array('success'=>true);
    }

    public function set($userid,$id,$fields)
    {
        $userid = (int) $userid;
        $id = (int) $id;
        $fields = json_decode(stripslashes($fields));

        $array = array();

        // content, height, name, alias, description, main, public, published, showdescription
        // Repeat this line changing the field name to add fields that can be updated:

        if (isset($fields->height)) $array[] = "`height` = '".intval($fields->height)."'";
        if (isset($fields->content)) $array[] = "`content` = '".preg_replace('/[^\w\s-.#<>?",;:=&\/%~]/','',$fields->content)."'";

        if (isset($fields->name)) $array[] = "`name` = '".preg_replace('/[^\w\s-]/','',$fields->name)."'";
        if (isset($fields->alias)) $array[] = "`alias` = '".preg_replace('/[^\w\s-]/','',$fields->alias)."'";
        if (isset($fields->description)) $array[] = "`description` = '".preg_replace('/[^\w\s-]/','',$fields->description)."'";

        if (isset($fields->main))
        {
            $main = (bool)$fields->main;
            if ($main) $this->mysqli->query("UPDATE dashboard SET main = FALSE WHERE userid='$userid' and id<>'$id'");
            $array[] = "`main` = '".$main ."'";
        }

        if (isset($fields->public)) $array[] = "`public` = '".((bool)$fields->public)."'";
        if (isset($fields->published)) $array[] = "`published` = '".((bool)$fields->published)."'";
        if (isset($fields->showdescription)) $array[] = "`showdescription` = '".((bool)$fields->showdescription)."'";
        // Convert to a comma seperated string for the mysql query
        $fieldstr = implode(",",$array);

        $this->mysqli->query("UPDATE dashboard SET ".$fieldstr." WHERE userid='$userid' and `id` = '$id'");

        if ($this->mysqli->affected_rows>0){
            return array('success'=>true, 'message'=>_('Field updated'));
        } else {
            return array('success'=>false, 'message'=>_('Field could not be updated'));
        }
    }

    // Return the main dashboard from $userid
    public function get_main($userid)
    {
        $userid = (int) $userid;
        $result = $this->mysqli->query("SELECT * FROM dashboard WHERE userid='$userid' and main=TRUE");
        return $result->fetch_array();
    }

    public function get($userid, $id, $public, $published)
    {
        $userid = (int) $userid;
        $id = (int) $id;
        $qB = ""; if ($public==true) $qB = " and public=1";
        $qC = ""; if ($published==true) $qC = " and published=1";

        $result = $this->mysqli->query("SELECT * FROM dashboard WHERE userid='$userid' and id='$id'".$qB.$qC);
        return $result->fetch_array();
    }

    // Returns the $id dashboard from $userid
    public function get_from_alias($userid, $alias, $public, $published)
    {
        $userid = (int) $userid;
        $alias = preg_replace('/[^\w\s-]/','',$alias);
        $qB = ""; if ($public==true) $qB = " and public=1";
        $qC = ""; if ($published==true) $qC = " and published=1";

        $result = $this->mysqli->query("SELECT * FROM dashboard WHERE userid='$userid' and alias='$alias'".$qB.$qC);
        return $result->fetch_array();
    }

    public function build_menu($userid,$location)
    {
        global $path, $session;
        $userid = (int) $userid;

        $public = 0; $published = 0;

        if (isset($session['profile']) && $session['profile']==1) {
            $dashpath = $session['username'];
            $public = !$session['write'];
            $published = 1;
        } else {
            $dashpath = 'dashboard/'.$location;
        }

        $dashboards = $this->get_list($userid, $session['orgid'], $public, $published);
        $topmenu="";
        foreach ($dashboards as $dashboard)
        {
            // Check show description
            if ($dashboard['showdescription']) {
                    $desc = ' title="'.$dashboard['description'].'"';
            } else {
                    $desc = '';
            }

                // Set URL using alias or id
            if ($dashboard['alias']) {
                $aliasurl = "/".$dashboard['alias'];
            } else {
                $aliasurl = '&id='.$dashboard['id'];
            }

                // Build the menu item
            $topmenu.='<li><a href="'.$path.$dashpath.$aliasurl.'"'.$desc.'>'.$dashboard['name'].'</a></li>';
        }
        return $topmenu;
    }
    public function upgradedasboards($userid)
    {
        $userid = (int) $userid;
        $uid = "";
        if ($userid==true) $uid = " and users.userid=".$useerid;
        $sql = "update  `users`, `dashboard` SET `dashboard`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `dashboard`.`userid` =`users`.`id`".$uid;
        $this->mysqli->query($sql);
        if ($this->mysqli->affected_rows>0){
            return array('success'=>true, 'message'=>$this->mysqli->affected_rows.' '._('Dashboards are assigned to organisations'));
        } else {
            return array('success'=>false, 'message'=>_('No dashboard were assigned to organisations'));
        }


        //$sql = "update  `users`, `input` SET `input`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `input`.`userid` =`users`.`id`".$uid;
        //$sql = "update  `users`, `feeds` SET `feeds`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `feeds`.`userid` =`users`.`id`".$uid;
        //$sql = "update  `users`, `myelectric` SET `myelectric`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `myelectric`.`userid` =`users`.`id`".$uid;

    }

}function logitem($str){
    $handle = fopen("/home/bp/emoncmsdata/db_log.txt", "a");
    fwrite ($handle, $str."\n");
    fclose ($handle);
}
