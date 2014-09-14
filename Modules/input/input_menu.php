<?php

bindtextdomain($domain, dirname(__FILE__)."/locale");
$menu_left[] = array('name'=> dgettext($domain, "Input"), 'path'=>"input/view" , 'session'=>"write", 'order' => 1 );

