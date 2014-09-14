<?php
	$domain="messages";
  bindtextdomain($domain, dirname(__FILE__)."/locale");

  $menu_right[] = array('name'=> dgettext($domain, "Admin"), 'path'=>"admin/view" , 'session'=>"admin");

