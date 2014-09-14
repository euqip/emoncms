<?php

  bindtextdomain($domain, dirname(__FILE__)."/locale");

  $menu_dropdown[] = array('name'=> dgettext($domain, "Node"), 'path'=>"node/list" , 'session'=>"write", 'order' => 0 );

?>
