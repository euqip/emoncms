<?php

  $domain = "messages";
  //bindtextdomain($domain, "Modules/input/locale");
  //bind_textdomain_codeset($domain, 'UTF-8');
  bindtextdomain($domain, dirname(__FILE__)."/locale");

  $menu_left[] = array('name'=> dgettext($domain, "Node"), 'path'=>"node/list" , 'session'=>"write", 'order' => 0 );

?>
