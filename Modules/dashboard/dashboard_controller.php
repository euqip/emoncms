<?php

/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/

// dashboard/new						New dashboard
// dashboard/delete 				POST: id=			Delete dashboard
// dashboard/clone					POST: id=			Clone dashboard
// dashboard/thumb 					List dashboards
// dashboard/list         	List mode
// dashboard/view?id=1			View and run dashboard (id)
// dashboard/edit?id=1			Edit dashboard (id) with the draw editor
// dashboard/ckeditor?id=1	Edit dashboard (id) with the CKEditor
// dashboard/set POST				Set dashboard
// dashboard/setconf POST 	Set dashboard configuration

defined('EMONCMS_EXEC') or die('Restricted access');

function dashboard_controller()
{
    global $mysqli, $path, $session, $route, $user, $actions;
    $modulename = "dashboard";
    $basedir = MODULE.DS.$modulename.DS;
    require $basedir.$modulename."_model.php";
    $dashboard = new Dashboard($mysqli);

    // id, userid, content, height, name, alias, description, main, public, published, showdescription

    $result = false; $submenu = '';
    $actions=array(
        'delete'    => 'yes',
        'clone'     => 'yes',
        'edit'      => 'yes',
        'draw'      => 'yes',
        'view'      => 'yes',
        'public'    => 'yes',
        'published' => 'yes',
        'mine'      => 'yes',
        );
/*
      const LAMBDA   = 0;
      const SYSADMIN = 1;
      const ORGADMIN = 3;
      const VIEWER   = 4;
      const DESIGNER = 5;
*/

    switch ($session['admin']){
    case Role::SYSADMIN:
        // sysadmin reads and updates all
        $cond = "1";
        $condrd = "1";
        break;
    case Role::ORGADMIN:
        //orgadmin reads and updates all within his organisation
        $cond = "orgid='".$session['orgid']."'";
        $condrd = "orgid='".$session['orgid']."'";
        break;
    case Role::VIEWER:
        //viewer sees all withi organisation but is not allowed to update
        $cond = "orgid=0 and userid='".$session['userid']."'";
        $condrd = "orgid='".$session['orgid']."'";
            $actions['delete']= 'no';
            $actions['edit']= 'no';
            $actions['draw']= 'no';
            $actions['public']= 'no';
            $actions['published']= 'no';
            $actions['mine']= 'no';
        break;
    case Role::DESIGNER:
        //designer updates own and reads all (dashbords)
        $cond = "orgid='".$session['orgid']."' and userid='".$session['userid']."'";
        $condrd = "orgid='".$session['orgid']."'";
        $actions=array(
            );
        break;
    default:
        //default is equivalent to now organisations the user is only able to read ans update his own dashboards
        $cond = "userid='".$session['userid']."'";
        break;
        }

    if ($route->format == 'html')
    {
        $viewpath = $basedir."Views".DS.$modulename."_";
        if ($route->action == "list" && $session['write'])
        {
            $result = view($viewpath."list.php",array());
            $menu = $dashboard->build_menu($session['userid'],"view");
            $submenu = view($viewpath."menu.php", array('menu'=>$menu, 'type'=>"view"));
        }

        if ($route->action == "view" && $session['read'])
        {
            if (isset($_GET['id'])) $dash = $dashboard->get($condrd ,get('id'),false,false);
            //elseif ($route->subaction) $dash = $dashboard->get_from_alias($session['userid'],$route->subaction,false,false);
            else $dash = $dashboard->get_main($session['userid']);

            if ($dash) {
              $result = view($viewpath."view.php",array('dashboard'=>$dash));
            } else {
              $result = view($viewpath."list.php",array());
            }

            $menu = $dashboard->build_menu($session['userid'],"view");
            $submenu = view($viewpath."menu.php", array('id'=>$dash['id'], 'menu'=>$menu, 'type'=>"view"));
        }

        if ($route->action == "edit" && $session['write']  and ($session['admin']<>Role::VIEWER))
        {
            //if ($route->subaction) $dash = $dashboard->get_from_alias($session['userid'],$route->subaction,false,false);
            if (isset($_GET['id'])) $dash = $dashboard->get($session['userid'],get('id'),false,false);

            $result = view($viewpath."edit_view.php",array('dashboard'=>$dash));
            $result .= view($viewpath."config.php", array('dashboard'=>$dash));

            $menu = $dashboard->build_menu($session['userid'],"edit");
            $submenu = view($viewpath."menu.php", array('id'=>$dash['id'], 'menu'=>$menu, 'type'=>"edit"));
        }
        if ($route->action == "clone" && $session['write']) {
            //iconlink action is changed to html presentation
            $result = $dashboard->dashclone($cond,$session['userid'], get('id'));
            $result = view($viewpath."list.php",array());

            $menu = $dashboard->build_menu($session['userid'],"view");
            $submenu = view($viewpath."menu.php", array('menu'=>$menu, 'type'=>"view"));
          }

    }
    if ($route->format == 'json' && $session['write'])
    {
        if ($route->action=='list') $result = $dashboard->get_list($session['userid'], $session['orgid'], $condrd, false, false);

        if ($route->action=='set') $result = $dashboard->set($session['userid'], $cond, get('id'),get('fields'));
        if ($route->action=='setcontent') $result = $dashboard->set_content($session['userid'],post('id'),$cond, post('content'),post('height'));
        if ($route->action=='delete') $result = $dashboard->delete(get('id'),$cond);

        if ($route->action=='create') $result = $dashboard->create($session['userid']);
        if ($route->action=='clone') {
            // this action will return the record id only see html response , it will redraw the list
            // with new duplicated dasboard
            $result = $dashboard->dashclone($cond,$session['userid'], get('id'));
        }
    }

    // $result = $dashboard->get_main($session['userid'])

    return array('content'=>$result, 'submenu'=>$submenu);
}
