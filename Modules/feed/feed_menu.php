<?php
bindtextdomain($domain, dirname(__FILE__)."/locale");

$menu_left[] = array('name'=> dgettext($domain, "Feeds"), 'path'=>"feed/list" , 'session'=>"write", 'order' => 2 );

