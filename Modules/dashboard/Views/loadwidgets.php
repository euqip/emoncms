<?php
/*

All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org

*/

define("MODULE_PATH_EXT",MODULE.DS);
define("WIDGETS_PATH",MODULE_PATH_EXT."dashboard".DS."Views".DS."js".DS."widgets");
define("WIDGETS_PATH_EXT",WIDGETS_PATH.DS);

$widgets = array();
$dir = scandir(WIDGETS_PATH);
for ($i=2; $i<count($dir); $i++)
{
    if (is_file(WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_widget.css"))
    {
        echo "<link href='".$path.WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_widget.css' rel='stylesheet'>";
    }
    if (is_file(WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_widget.php"))
    {
        require_once WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_widget.php";
    }
    else if (is_file(WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_render.js"))
    {
        echo "<script type='text/javascript' src='".$path.WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_render.js'></script>";
    }
    $widgets[] = $dir[$i];
}

// Load module specific widgets

//$dir = scandir(MODULE_PATH);
$dir = scandir(MODULE);
for ($i=2; $i<count($dir); $i++)
{
    if (filetype(MODULE_PATH_EXT.$dir[$i])=='dir')
    {
        if (is_file(MODULE_PATH_EXT.$dir[$i].DS."widget".DS.$dir[$i]."_widget.php"))
        {
            require_once MODULE_PATH_EXT.$dir[$i].DS."widget".DS.$dir[$i]."_widget.php";
            $widgets[] = $dir[$i];
        }
        else if (is_file(MODULE_PATH_EXT.$dir[$i].DS."widget".DS.$dir[$i]."_render.js"))
        {
            echo "<script type='text/javascript' src='".$path.MODULE_PATH_EXT.$dir[$i].DS."widget".DS.$dir[$i]."_render.js'></script>";
            $widgets[] = $dir[$i];
        }
    }
}

