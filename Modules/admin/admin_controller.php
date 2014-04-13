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
    global $mysqli,$session, $user, $org, $route, $updatelogin;

    // Allow for special admin session if updatelogin property is set to true in settings.php
    // Its important to use this with care and set updatelogin to false or remove from settings
    // after the update is complete.
    // 
    // result and sessionadmin need to be set to avoid errors when session is expired.
    // 
    $result='not authoraized';
    $sessionadmin= ($updatelogin || $session['admin'])? true:false;
    //when not authorized, redirect to login form (to be done)


    if ($sessionadmin)
    {
        if ($route->action == 'view') $result = view("Modules/admin/admin_main_view.php", array());

        if ($route->action == 'db')
        {
            $applychanges = get('apply');
            if (!$applychanges) $applychanges = false;
            else $applychanges = true;

            require "Modules/admin/update_class.php";
            require_once "Lib/dbschemasetup.php";

            $update = new Update($mysqli);

            $updates = array();
            $updates[] = array(
                'title'=>"Database schema",
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

            $result = view("Modules/admin/update_view.php", array('applychanges'=>$applychanges, 'updates'=>$updates));
        }
// q is the arry providing the data
        if ($session['write'] && $session['admin']){
            $username= $user->get_username([$session['userid']]);

            switch ($route->action){
                case 'users':
                    $result = view("Modules/admin/userlist_view.php", array());
                    break;
                case 'orgs':
                    $result = view("Modules/admin/orglist_view.php", array());
                    break;
                case 'orglist':
                    $data = array();
                    $result = $mysqli->query("SELECT id, orgname, longname, country, language, timezone, lastuse FROM orgs WHERE delflag=0");
                    while ($row = $result->fetch_object()) $data[] = $row;
                    $result = $data;
                    break;
                case 'userlist':
                    $data = array();
                    $result = $mysqli->query("SELECT id,username,email,language,lastlogin FROM users WHERE 1");
                    while ($row = $result->fetch_object()) $data[] = $row;
                    $result = $data;
                    break;
                case 'setuser':
                    $_SESSION['userid'] = intval(get('id'));
                    header("Location: ../user/view");
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
                    }
                    break;
                }
            }
            return array('content'=>$result);
        }else{
        header("Location: /");
        }
    }


