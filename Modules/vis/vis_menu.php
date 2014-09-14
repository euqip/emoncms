<?php
  bindtextdomain($domain, dirname(__FILE__)."/locale");
  $menu_left[] = array('name'=> dgettext($domain, "Vis"), 'path'=>"vis/list" , 'session'=>"write", 'order' => 3 );

?>
