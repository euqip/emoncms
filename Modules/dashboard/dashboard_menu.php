<?php

bindtextdomain($domain, dirname(__FILE__)."/locale");

$menu_left[] = array('name'=> dgettext($domain, "Dashboard"), 'path'=>"dashboard/view" , 'session'=>"write", 'order' => 4 );
