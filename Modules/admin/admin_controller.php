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

function admin_controller()
{
    $modulename = "admin";
    $basedir = MODULE.DS.$modulename.DS;
    global $mysqli,$session, $user, $org, $route, $updatelogin, $param, $path;

    // Allow for special admin session if updatelogin property is set to true in settings.php
    // Its important to use this with care and set updatelogin to false or remove from settings
    // after the update is complete.
    //
    // result and sessionadmin need to be set to avoid errors when session is expired.
    //

    if ($route->action == 'credits') {
        $credits = load_credits();
        $result = view($basedir."credits_view.php", array());
        return array('content'=>$result);
    } else {
        $result= _('not authorized');
        $sessionadmin= ($updatelogin || $session['admin'])? true:false;
        //when not authorized, redirect to login form (to be done)
        if ($sessionadmin)
        {
            if ($route->action == 'view') $result = view($basedir."admin_main_view.php", array());

            if ($route->action == 'db')
            {
                $applychanges = get('apply');
                if (!$applychanges) $applychanges = false;
                else $applychanges = true;

                require $basedir."update_class.php";
                require_once CORE."Model".DS."dbschemasetup.php";

                $update = new Update($mysqli);

                $updates = array();
                $updates[] = array(
                    'title'=> _("Database schema"),
                    'description'=>"",
                    'operations'=>db_schema_setup($mysqli,load_db_schema(),$applychanges)
                );

                if (!$updates[0]['operations']) {

                // In future versions we could check against db version number as to what updates should be applied
                $updates[] = $update->u0001($applychanges);
                //$updates[] = $update->u0002($applychanges);
                $updates[] = $update->u0003($applychanges);
                $updates[] = $update->u0004($applychanges);

                }

                $result = view($basedir."update_view.php", array('applychanges'=>$applychanges, 'updates'=>$updates));
            }
    // q is the array providing the data
            if ($session['write'] && $session['admin']){
                $username= $user->get_username([$session['userid']]);
                //echo "action ".$route->action." - subaction ".$route->subaction;
                $data = array();

                switch ($route->action){
                    case 'users':
                        $result = view($basedir."userlist_view.php", array());
                        break;
                    case 'orgs':
                        $result = view($basedir."orglist_view.php", array());
                        break;
                    case 'orglist':
                        $flds = "id,".$param['orgletter'].", orgname, longname, country, language, timezone, lastuse";
                        $result = $org->get_wcond($flds,"delflag=0  ORDER By letter");
                        while ($row = $result->fetch_object()) $data[] = $row;
                        $result = $data;
                        break;
                    case 'setorg':
                        // by setting the $_session['userid'], the MyAccount function becomes unavailable
                        /*
                        $_SESSION['userid'] = intval(get('id'));
                         */
                        $_SESSION['showorgid'] = intval(get('id'));
                        header("Location: ../org/view/".intval(get('id')));
                        break;
                    case 'userlist':
                        $wherecond = "0";
                        if (isset ($_SESSION['admin'])){
                            switch (intval($_SESSION['admin'])){
                                case 1:
                                    $wherecond ="1";
                                    break;
                                case 3:
                                    $wherecond = "orgid = ".intval($_SESSION['orgid']);
                                    break;
                                default:
                                    $whercond = "0";
                                    break;
                            }
                        }
                        $flds = "id, ".$param['userletter'].", username,email,language,lastlogin, orgid, not delflag as active";
                        $result = $user->get_wcond($flds,$wherecond." ORDER By letter");
                        while ($row = $result->fetch_object()) $data[] = $row;
                        $result = $data;
                        break;
                    case 'setuser':
                        // by setting the $_session['userid'], the MyAccount function becomes unavailable
                        /*
                        $_SESSION['userid'] = intval(get('id'));
                         */
                        $_SESSION['showuserid'] = intval(get('id'));
                        header("Location: ../user/view/".intval(get('id')));
                        break;
                    case 'user':
                        // avoid deletion if not authoriszed user
                        $id=intval(get('id'));
                        $wcond = "id = -1";
                        if (isset($_SESSION['admin'])){
                            switch (intval($_SESSION['admin'])){
                                case 1: // sysstem administrator
                                    //able to delete any user
                                    $wcond = "id = ".$id;
                                    break;
                                case 3: // organisation administrator
                                    //enable to delete its own organisation users
                                    $wcond = "id = ".$id." and orgid  = ".$_SESSION['orgid'];
                                    break;
                                default: // simple user with design or view authorasations
                                    // no deletions set impossible where condition
                                    $wcond = "id = ".'-1'." and orgid  = ".$_SESSION['orgid'];
                                   break;
                            }
                        }
                        switch ($route->subaction){
                            case 'toggle':
                                if (isset($_GET['id'])){
                                    $id = get('id');
                                } else if (isset($_POST['id'])){
                                    $id = post('id');
                                } else {
                                    return array('success'=>false, 'message'=>_("No id provided"));
                                }
                                $result = $user->toggle_user($session['userid'], $username, $wcond);
                                break;
                            }
                            break;

                    case 'org':
                        switch ($route->subaction){
                            case 'create':
                                switch ($route->format){
                                    case 'json':
                                        if (isset($_GET['orgfields'])){
                                             $orgfields = json_decode(get('orgfields'));
                                        } else if (isset($_POST['orgfields'])){
                                            $orgfields = json_decode(post('orgfields'),true);
                                        } else {
                                            return array('success'=>false, 'message'=>_("No orgfields provided"));
                                        }
                                        $result = $org->create_org($session['userid'],$username,$orgfields);
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'delete':
                                if (isset($_GET['id'])){
                                    $id = get('id');
                                } else if (isset($_POST['id'])){
                                    $id = post('id');
                                } else {
                                    return array('success'=>false, 'message'=>_("No id provided"));
                                }
                                $result = $org->delete_org($session['userid'], $username, $id);
                                break;
                            case 'update':
                                if (isset($_GET['fields'])){
                                    $id = get('orgid');
                                    $fields = get('fields');
                                } else if (isset($_POST['id'])){
                                    $id = post('orgid');
                                    $fields = post('fields');
                                } else {
                                    return array('success'=>false, 'message'=>_("Update no done"));
                                }
                                $result = $org->update_org($session['userid'], $username, $id, $fields);
                                break;
                            default:
                                return array('success'=>false, 'message'=>_("Unknown command!"));
                        }
                        break;
                    }
                }
                return array('content'=>$result);
            }else{
            header("Location: ".$path);
            }
        }
    }
