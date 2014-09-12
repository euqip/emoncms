<?php

/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

global $path, $session, $menu, $menu;
if (!isset($session['profile'])) $session['profile'] = 0;

$menu_left = $menu['left'];
$menu_right = $menu['right'];
$menu_dropdown = $menu['dropdown'];

if ($session['write']) $menu_right[] = array('name'=>"<b>Docs</b>", 'path'=>"site/docs", 'order' => 0 );
if (!$session['write']) $menu_right[] = array('name'=>"Log In", 'path'=>"user/login", 'order' => -1 );
?>


<ul class="nav navbar-nav">
    <?php
    foreach ($menu_left as $item)
    {
        if (isset($item['session'])) {
            if (isset($session[$item['session']]) && (($session[$item['session']]==1) || ($session[$item['session']]==3))) {
                echo "<li><a href=\"".$path.$item['path']."\">".$item['name']."</a></li>";
            }
        } else {
            echo "<li><a href=\"".$path.$item['path']."\">".$item['name']."</a></li>";
        }
    }
    ?>
</ul>

<?php
//dropdown menu items

if (count($menu_dropdown) && $session['read']) { ?>
<ul class="nav navbar-nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Extras <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <?php foreach ($menu_dropdown as $item) { ?>
            <?php if (isset($session[$item['session']]) && (($session[$item['session']]==1) || ($session[$item['session']]==3))) { ?>
            <li><a href="<?php echo $path.$item['path']; ?>"><?php echo $item['name']; ?></a></li>
            <?php } ?>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
</ul>



<ul class="nav navbar-nav navbar-right">
    <?php
    foreach ($menu_right as $item)
    {
        if (isset($item['session'])) {
            if (isset($session[$item['session']]) && (($session[$item['session']]==1) || ($session[$item['session']]==3))) {
                echo "<li><a href=".$path.$item['path']." >".$item['name']."</a></li>";
            }
        } else {
            echo "<li><a href=".$path.$item['path']." >".$item['name']."</a></li>";
        }
    }
    ?>
</ul>
