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



class Input
{
    private $mysqli;
    private $feed;
    private $redis;

    public function __construct($mysqli,$redis,$feed)
    {
        $this->mysqli = $mysqli;
        $this->feed = $feed;

        $this->redis = $redis;
    }
    // Check a nodeID against the current node-ID limits to see if it's valid
    // True = Valid
    // False = not valid
    public function check_node_id_valid($nodeid)
    {
        global $max_node_id_limit;

        // As highlighted by developer:fake-name PHP's doesnt have a function
        // for checking if a string will cast to a valid integer.
        //
        // is_numeric is the closest function but it allows input of:
        // Octal, e-notation (+0123.45e6) & Hex.
        //
        // Casting with (int) will convert input such as Array({stuff}) to 1
        // whereas NAN would be a more appropriate result.
        //
        // Other languages such as Python will return an error if you try and
        // cast a variable in this way.
        //
        // checking against isNumeric will probably catch *most*
        // of the potential issues for now but it may be good look at catching
        // non-integer numbers at some point

        /*
        if (!is_numeric ($nodeid))
        {
            return false;
        }

        $nodeid = (int) $nodeid;

        if (!isset($max_node_id_limit))
        {
            $max_node_id_limit = 32;    // Default to 32 if not overridden
        }

        if ($nodeid<$max_node_id_limit)
        {
            return true;
        }
        else
        {
            return false;
        }
        */
        return true;

    }
    // USES: redis input & user  +++++ add orgid in tables *****
    public function create_input($userid, $orgid, $nodeid, $name)
    {
        global $max_node_id_limit;
        $userid = (int) $userid;
        $nodeid = (int) $nodeid;
        $orgid = (int) $orgid;

        $name = preg_replace('/[^\w\s-.]/','',$name);
        $sql = "INSERT INTO input (userid,orgid,name,nodeid) VALUES ('$userid','$orgid','$name','$nodeid')";
        $this->mysqli->query($sql);

        $id = $this->mysqli->insert_id;

        if ($this->redis) {
            $this->redis->sAdd("user:inputs:$userid", $id);
            $this->redis->sAdd("org:inputs:$orgid", $id);
            $this->redis->hMSet("input:$id",array('id'=>$id,'nodeid'=>$nodeid,'userid'=>$userid,'orgid'=>$orgid,'name'=>$name,'description'=>"", 'processList'=>""));
        }
        return $id;

    }

    public function exists($inputid)
    {
        $inputid = (int) $inputid;
        $sql = "SELECT id FROM input WHERE `id` = '$inputid'";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows == 1) return true; else return false;
    }

    // USES: redis input
    public function set_timevalue($id, $time, $value)
    {
        $id = (int) $id;
        $time = (int) $time;
        $value = (float) $value;

        if ($this->redis) {
            $this->redis->hMset("input:lastvalue:$id", array('value' => $value, 'time' => $time));
        } else {
            $time = date("Y-n-j H:i:s", $time);
            $sql = "UPDATE input SET time='$time', value = '$value' WHERE id = '$id'";
            $this->mysqli->query($sql);
        }
    }

    // used in conjunction with controller before calling another method
    //more generic function
    public function belongs_to($whatid,$idval, $inputid)
    {
        $idval = (int) $idval;
        $inputid = (int) $inputid;
        $sql = "SELECT id FROM input WHERE $whatid = '$idval' AND id = '$inputid'";
        $result = $this->mysqli->query($sql);
        if ($result->fetch_array()) return true; else return false;
    }
    public function belongs_to_user($userid, $inputid)
    {
        $userid = (int) $userid;
        $inputid = (int) $inputid;

        $result = $this->mysqli->query("SELECT id FROM input WHERE userid = '$userid' AND id = '$inputid'");
        if ($result->fetch_array()) return true; else return false;
    }

    public function belongs_to_org($orgid, $inputid)
    {
        $userid = (int) $userid;
        $inputid = (int) $inputid;

        $result = $this->mysqli->query("SELECT id FROM input WHERE orgid = '$orgid' AND id = '$inputid'");
        if ($result->fetch_array()) return true; else return false;
    }

    // USES: redis input
    private function set_processlist($id, $processlist)
    {
        // CHECK REDIS
        if ($this->redis) $this->redis->hset("input:$id",'processList',$processlist);
        $this->mysqli->query("UPDATE input SET processList = '$processlist' WHERE id='$id'");

    }
    // USES: redis input
    public function set_fields($id,$fields)
    {
        $id = intval($id);
        $fields = json_decode(stripslashes($fields));

        $array = array();

        // Repeat this line changing the field name to add fields that can be updated:
        if (isset($fields->description)) $array[] = "`description` = '".preg_replace(REGEX_STRING,'',$fields->description)."'";
        if (isset($fields->name)) $array[] = "`name` = '".preg_replace(REGEX_STRING,'',$fields->name)."'";
        // Convert to a comma seperated string for the mysql query
        $fieldstr = implode(",",$array);
        $this->mysqli->query("UPDATE input SET ".$fieldstr." WHERE `id` = '$id'");

        // CHECK REDIS?
        // UPDATE REDIS
        if (isset($fields->name) && $this->redis) $this->redis->hset("input:$id",'name',$fields->name);
        if (isset($fields->description) && $this->redis) $this->redis->hset("input:$id",'description',$fields->description);

        if ($this->mysqli->affected_rows>0){
            return array('success'=>true, 'message'=>_('Field updated'));
        } else {
            return array('success'=>false, 'message'=>_('Field could not be updated'));
        }
    }

    // USES: redis input
    public function add_process($process_class,$userid,$inputid,$processid,$arg)
    {
        $userid = (int) $userid;
        $inputid = (int) $inputid;
        $processid = (int) $processid;                                    // get process type (ProcessArg::)

        $process = $process_class->get_process($processid);
        $processtype = $process[1];                                       // Array position 1 is the processtype: VALUE, INPUT, FEED
        $datatype = $process[4];                                          // Array position 4 is the datatype
        switch ($processtype) {
            case ProcessArg::VALUE:                                       // If arg type value
                if ($arg == '') return array('success'=>false, 'message'=>_('Argument must be a valid number greater or less than 0.'));

                $arg = (float)$arg;
                $arg = str_replace(',','.',$arg); // hack to fix locale issue that converts . to ,

                break;
            case ProcessArg::INPUTID:                                     // If arg type input
                $arg = (int) $arg;
                if (!$this->exists($arg)) return array('success'=>false, 'message'=>_('Input does not exist!'));
                break;
            case ProcessArg::FEEDID:                                      // If arg type feed
                $arg = (int) $arg;
                if (!$this->feed->exist($arg)) return array('success'=>false, 'message'=>_('Feed does not exist!'));
                break;
            case ProcessArg::NONE:                                        // If arg type none
                $arg = 0;
                break;
            case ProcessArg::TEXT:                                       // If arg type TEXT
                $arg = $arg;
                break;

        }

        $list = $this->get_processlist($inputid);
        if ($list) $list .= ',';
        $list .= $processid . ':' . $arg;
        $this->set_processlist($inputid, $list);

        return array('success'=>true, 'message'=>_('Process added'));
    }

    /******
    * delete input process by index
    ******/
    // USES: redis input
    public function delete_process($inputid, $index)
    {
        $inputid = (int) $inputid;
        $index = (int) $index;

        $success = false;
        $index--; // Array is 0-based. Index from process page is 1-based.

        // Load process list
        $array = explode(",", $this->get_processlist($inputid));

        // Delete process
        if (count($array)>$index && $array[$index]) {unset($array[$index]); $success = true;}

        // Save new process list
        $this->set_processlist($inputid, implode(",", $array));

        return $success;
    }

    /******
    * move_input_process - move process up/down list of processes by $moveby (eg. -1, +1)
    ******/
    // USES: redis input
    public function move_process($id, $index, $moveby)
    {
        $id = (int) $id;
        $index = (int) $index;
        $moveby = (int) $moveby;

        if (($moveby > 1) || ($moveby < -1)) return false;  // Only support +/-1 (logic is easier)

        $process_list = $this->get_processlist($id);
        $array = explode(",", $process_list);
        $index = $index - 1; // Array is 0-based. Index from process page is 1-based.

        $newindex = $index + $moveby; // Calc new index in array
        // Check if $newindex is greater than size of list
        if ($newindex > (count($array)-1)) $newindex = (count($array)-1);
        // Check if $newindex is less than 0
        if ($newindex < 0) $newindex = 0;

        $replace = $array[$newindex]; // Save entry that will be replaced
        $array[$newindex] = $array[$index];
        $array[$index] = $replace;

        // Save new process list
        $this->set_processlist($id, implode(",", $array));
        return true;
    }

    // USES: redis input
    public function reset_process($id)
    {
        $id = (int) $id;
        $this->set_processlist($id, "");
    }

    public function get_inputs($userid,$orgid)
    {
        if ($this->redis) {
            return $this->redis_get_inputs($userid, $orgid);
        } else {
            return $this->mysql_get_inputs($userid, $orgid);
        }
    }

    // USES: redis input & user
    public function redis_get_inputs($userid, $orgid)
    {
        $userid = (int) $userid;
        $orgid = (int) $orgid;
        if (!$this->redis->exists("org:inputs:$orgid")) $this->load_to_redis_org($orgid);
        if (!$this->redis->exists("user:inputs:$userid")) $this->load_to_redis_user($userid);

        $dbinputs = array();
        $inputids = $this->redis->sMembers("user:inputs:$userid");
        foreach ($inputids as $id)
        {
            $row = $this->redis->hGetAll("input:$id");
            if ($row['nodeid']==null) $row['nodeid'] = 0;
            if (!isset($dbinputs[$row['nodeid']])) $dbinputs[$row['nodeid']] = array();
            $dbinputs[$row['nodeid']][$row['name']] = array('id'=>$row['id'], 'processList'=>$row['processList']);
        }

        return $dbinputs;
    }

    public function mysql_get_inputs($userid, $orgid)
    {
        $userid = (int) $userid;
        $orgid = (int) $orgid;
        $uid= "if(userid = ".$userid.", true, false) as myown ";
        $org= "if(orgid = ".$orgid.", true, false) as myorg ";
        $sql = "SELECT *, ".$uid.", ".$org." FROM input WHERE `orgid` = '$orgid'";
        $dbinputs = array();
        $result = $this->mysqli->query($sql);
        while ($row = (array)$result->fetch_object())
        {
            if ($row['nodeid']==null) $row['nodeid'] = 0;
            if (!isset($dbinputs[$row['nodeid']])) $dbinputs[$row['nodeid']] = array();
            $dbinputs[$row['nodeid']][$row['name']] = array('id'=>$row['id'], 'processList'=>$row['processList']);
        }
        return $dbinputs;
    }

    //-----------------------------------------------------------------------------------------------
    // This public function gets a users input list, its used to create the input/list page
    //-----------------------------------------------------------------------------------------------
    // USES: redis input & user & lastvalue

    public function getlist($userid,$orgid,$cond)
    {
        if ($this->redis) {
            return $this->redis_getlist($userid,$orgid,$cond);
        } else {
            return $this->mysql_getlist($userid,$orgid,$cond);
        }
    }

    public function redis_getlist($userid,$orgid,$cond)
    {
        $userid = (int) $userid;
        $orgid = (int) $orgid;
        // transfer items from MySQL to redis, if not yet existing in redis
        if (!$this->redis->exists("user:inputs:$userid")) $this->load_to_redis_user($userid);
        if (!$this->redis->exists("org:inputs:$orgid")) $this->load_to_redis_org($orgid);

        $inputs = array();
        //read all inputs
        //$inputids = $this->redis->keys('input:*');
        //print_r ($inputids);
        //read all inputs from a spacified user
        $inputids = $this->redis->sMembers("user:inputs:$userid");
        //read all inputs from a specified organisation
        //$inputids = $this->redis->sMembers("org:inputs:$orgid");
        foreach ($inputids as $id)
        {
            $row = $this->redis->hGetAll("input:$id");
            if (isset($row)){
                $lastvalue = $this->redis->hmget("input:lastvalue:$id",array('time','value'));
                $row['time'] = $lastvalue['time'];
                $row['value'] = $lastvalue['value'];
                $row['myown']=false;
                $row['myorg']=false;
                if (isset($row['userid'])){
                    $row['myown'] = ($row['userid']==$userid) ? true : false;
                    $row['myorg'] = ($row['orgid']==$orgid) ? true : false;
                }
                $inputs[] = $row;
            }
        }
        return $inputs;
    }

    public function mysql_getlist($userid,$orgid,$cond)
    {
        $userid = (int) $userid;
        $orgid = (int) $orgid;
        $uid= "if(userid = ".$userid.", true, false) as myown ";
        $org= "if(orgid = ".$orgid.", true, false) as myorg ";
        $dbinputs = array();
        $sql = "SELECT *, ".$uid.", ".$org." FROM input WHERE '$cond'";
        $inputs = array();
        //$sql = "SELECT * FROM input WHERE $cond";
        $result = $this->mysqli->query($sql);
        while ($row = (array)$result->fetch_object())
        {
            $row['time'] = strtotime($row['time']);
            $inputs[] = $row;
        }
        return $inputs;
    }

    // USES: redis input
    public function get_name($id)
    {
        // LOAD REDIS
        $id = (int) $id;

        if ($this->redis) {
            if (!$this->redis->exists("input:$id")) $this->load_input_to_redis($id);
            return $this->redis->hget("input:$id",'name');
        } else {
            $sql = "SELECT name FROM input WHERE `id` = '$id'";
            $result = $this->mysqli->query($sql);
            $row = $result->fetch_array();
            return $row['name'];
        }
    }

    // USES: redis input
    public function get_processlist($id)
    {
        // LOAD REDIS
        $id = (int) $id;

        if ($this->redis) {
            if (!$this->redis->exists("input:$id")) $this->load_input_to_redis($id);
            return $this->redis->hget("input:$id",'processList');
        } else {
            $result = $this->mysqli->query("SELECT processList FROM input WHERE `id` = '$id'");
            $row = $result->fetch_array();
            if (!$row['processList']) $row['processList'] = "";
            return $row['processList'];
        }
    }

    public function get_last_value($id)
    {
        $id = (int) $id;

        if ($this->redis) {
            return $this->redis->hget("input:lastvalue:$id",'value');
        } else {
            $sql = "SELECT value FROM input WHERE `id` = '$id'";
            $result = $this->mysqli->query($sql);
            $row = $result->fetch_array();
            return $row['value'];
        }
    }


    //-----------------------------------------------------------------------------------------------
    // Gets the inputs process list and converts id's into descriptive text
    //-----------------------------------------------------------------------------------------------
    /*
    // USES: redis input
    public function get_processlist_desc($process_class,$id)
    {
        $id = (int) $id;
        $process_list = $this->get_processlist($id);
        // Get the input's process list

        $list = array();
        if ($process_list)
        {
            $array = explode(",", $process_list);
            // input process list is comma seperated
            $index = 0;
            foreach ($array as $row)// For all input processes
            {
                $row = explode(":", $row);
                // Divide into process id and arg
                $processid = $row[0];
                $arg = $row[1];
                // Named variables
                $process = $process_class->get_process($processid);
                // gets process details of id given

                $processDescription = $process[0];
                // gets process description
                if ($process[1] == ProcessArg::INPUTID) {
                    $arg = $this->get_name($arg);
                // if input: get input name
                } elseif ($process[1] == ProcessArg::FEEDID){
                    $arg = $this->feed->get_field($arg,'name');

                    // Delete process list if feed does not exist
                    if (isset($arg['success']) && !$arg['success']) {
                      $this->delete_process($id, $index+1);
                      $arg = "Feed does not exist!";
                    }

                }
                // if feed: get feed name

                $list[] = array(
                    $processDescription,
                    $arg
                );
                // Populate list array

                $index++;
            }
        }
        return $list;
    }
    */

    // USES: redis input & user
    public function delete($userid, $inputid)
    {
        $userid = (int) $userid;
        $inputid = (int) $inputid;
        // Inputs are deleted permanentely straight away rather than a soft delete
        // as in feeds - as no actual feed data will be lost
        $sql = "DELETE FROM input WHERE userid = '$userid' AND id = '$inputid'";
        $this->mysqli->query($sql);

        if ($this->redis) {
            $this->redis->del("input:$inputid");
            $this->redis->srem("user:inputs:$userid",$inputid);
            $this->redis->srem("org:inputs:$userid",$inputid);
        }
    }

    public function clean($userid)
    {
        $result = "";
        $sql = "SELECT * FROM input WHERE `userid` = '$userid'";
        $qresult = $this->mysqli->query($sql);
        while ($row = $qresult->fetch_array())
        {
            $inputid = $row['id'];
            if ($row['processList']==NULL || $row['processList']=='')
            {
                $result = $this->mysqli->query("DELETE FROM input WHERE userid = '$userid' AND id = '$inputid'");

                if ($this->redis) {
                    $this->redis->del("input:$inputid");
                    $this->redis->srem("user:inputs:$userid",$inputid);
                    $this->redis->srem("org:inputs:$userid",$inputid);
                }
                $result .= "Deleted input: $inputid <br>";
            }
        }
        return $result;
    }

    // Redis cache loaders

    private function load_input_to_redis($inputid)
    {
        $sql = "SELECT * FROM input WHERE `id` = '$inputid'";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_object();

        $this->redis->sAdd("user:inputs:$userid", $row->id);
        $this->redis->hMSet("input:$row->id",array(
                'id'          => $row->id,
                'nodeid'      => $row->nodeid,
                'userid'      => $row->nodeid,
                'orgid'       => $row->nodeid,
                'name'        => $row->name,
                'description' => $row->description,
                'processList' => $row->processList
        ));
    }
    private function load_to_redis_user($userid)
    {
        //copy from Mysql to REDIS the data related to the defined usr
        $sql="SELECT * FROM input WHERE `userid` = '$userid'";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_object())
        {
            $this->redis->sAdd("user:inputs:$userid", $row->id);
            $this->redis->hMSet("input:$row->id",array(
                'id'          => $row->id,
                'nodeid'      => $row->nodeid,
                'userid'      => $row->nodeid,
                'orgid'       => $row->nodeid,
                'name'        => $row->name,
                'description' => $row->description,
                'processList' => $row->processList
            ));
        }
    }

//check if necessary
    private function load_to_redis_org($orgid)
    {
        //copy from Mysql to REDIS the data related to the defined organisation
        $sql = "SELECT * FROM input WHERE `orgid` = '$orgid'";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_object())
        {
            $this->redis->sAdd("org:inputs:$orgid", $row->id);
            $this->redis->hMSet("input:$row->id",array(
                'id'          => $row->id,
                'nodeid'      => $row->nodeid,
                'userid'      => $row->nodeid,
                'orgid'       => $row->nodeid,
                'name'        => $row->name,
                'description' => $row->description,
                'processList' => $row->processList
            ));
        }
    }


    public function upgradeinputs($userid)
    {
        $userid = (int) $userid;
        $uid = "";
        if ($userid==true) $uid = " and users.userid=".$useerid;
        $sql = "update  `users`, `input` SET `input`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `input`.`userid` =`users`.`id`".$uid;
        //$sql = "update  `users`, `dashboard` SET `dashboard`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `dashboard`.`userid` =`users`.`id`".$uid;
        $this->mysqli->query($sql);
        if ($this->mysqli->affected_rows>0){
            return array('success'=>true, 'message'=>$this->mysqli->affected_rows.' '._('Inputs are assigned to organisations'));
        } else {
            return array('success'=>false, 'message'=>_('No input were assigned to organisations'));
        }
        //$sql = "update  `users`, `input` SET `input`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `input`.`userid` =`users`.`id`".$uid;
        //$sql = "update  `users`, `feeds` SET `feeds`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `feeds`.`userid` =`users`.`id`".$uid;
        //$sql = "update  `users`, `myelectric` SET `myelectric`.`orgid` = `users`.`orgid` WHERE `users`.`orgid`<>0 and `myelectric`.`userid` =`users`.`id`".$uid;
    }
}

