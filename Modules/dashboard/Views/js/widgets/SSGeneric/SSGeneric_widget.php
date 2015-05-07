<?php
    if (is_file(WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_render.js"))
    {
        echo "<script type='text/javascript' src='".$path.WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_render.js'></script>";
    }
    $filename = "steelseries-min.js";
    if (is_file(WIDGETS_PATH_EXT.$dir[$i].DS."steelseries.js"))
    {
        echo "<script type='text/javascript' src='".$path.WIDGETS_PATH_EXT.$dir[$i].DS.$filename."'></script>";
    }
    $filename = "tween.js";
    if (is_file(WIDGETS_PATH_EXT.$dir[$i].DS.$dir[$i]."_render.js"))
    {
        echo "<script type='text/javascript' src='".$path.WIDGETS_PATH_EXT.$dir[$i].DS.$filename."'></script>";
    }
?>
