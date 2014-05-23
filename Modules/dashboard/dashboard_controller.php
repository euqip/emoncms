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
    global $mysqli, $path, $session, $route, $user, $author, $actions;

    require "Modules/dashboard/dashboard_model.php";
    $dashboard = new Dashboard($mysqli);

    // id, userid, content, height, name, alias, description, main, public, published, showdescription

    $result = false; $submenu = '';
    $actions=array(
        'delete' => 'yes',
        'clone'  => 'yes',
        'edit'   => 'yes',
        'draw'   => 'yes',
        'view'   => 'yes',
        'public'        => 'yes',
        'published'     => 'yes',
        'mine'          => 'yes',
        );

    switch ($session['admin']){
    case $author['sysadmin']:
        // sysadmin reads and updates all
        $cond = "1";
        $condrd = "1";
        break;
    case $author['orgadmin']:
        //orgadmin reads and updates all within his organisation
        $cond = "orgid='".$session['orgid']."'";
        $condrd = "orgid='".$session['orgid']."'";
        break;
    case $author['viewer']:
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
    case $author['designer']:
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
        if ($route->action == "list" && $session['write'])
        {
            $result = view("Modules/dashboard/Views/dashboard_list.php",array());
            $menu = $dashboard->build_menu($session['userid'],"view");
            $submenu = view("Modules/dashboard/Views/dashboard_menu.php", array('menu'=>$menu, 'type'=>"view"));
        }

        if ($route->action == "view" && $session['read'])
        {
            if ($route->subaction) $dash = $dashboard->get_from_alias($session['userid'],$route->subaction,false,false);
            elseif (isset($_GET['id'])) $dash = $dashboard->get($cond,get('id'),false,false);
            else $dash = $dashboard->get_main($session['userid']);

            if ($dash) {
              $result = view("Modules/dashboard/Views/dashboard_view.php",array('dashboard'=>$dash));
            } else {
              $result = view("Modules/dashboard/Views/dashboard_list.php",array());
            }

            $menu = $dashboard->build_menu($session['userid'],"view");
            $submenu = view("Modules/dashboard/Views/dashboard_menu.php", array('id'=>$dash['id'], 'menu'=>$menu, 'type'=>"view"));
        }

        if ($route->action == "edit" && $session['write'])
        {
            if ($route->subaction) $dash = $dashboard->get_from_alias($session['userid'],$route->subaction,false,false);
            elseif (isset($_GET['id'])) $dash = $dashboard->get($session['userid'],get('id'),false,false);

            $result = view("Modules/dashboard/Views/dashboard_edit_view.php",array('dashboard'=>$dash));
            $result .= view("Modules/dashboard/Views/dashboard_config.php", array('dashboard'=>$dash));

            $menu = $dashboard->build_menu($session['userid'],"edit");
            $submenu = view("Modules/dashboard/Views/dashboard_menu.php", array('id'=>$dash['id'], 'menu'=>$menu, 'type'=>"edit"));
        }
        if ($route->action == "clone" && $session['write']) {
            //iconlink action is changed to html presentation
            $result = $dashboard->dashclone($cond,$session['userid'], get('id'));
            $result = view("Modules/dashboard/Views/dashboard_list.php",array());

            $menu = $dashboard->build_menu($session['userid'],"view");
            $submenu = view("Modules/dashboard/Views/dashboard_menu.php", array('menu'=>$menu, 'type'=>"view"));
          }

    }

    if ($route->format == 'json' && $session['write'])
    {
        if ($route->action=='list') $result = $dashboard->get_list($session['userid'], $condrd, false, false);

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

