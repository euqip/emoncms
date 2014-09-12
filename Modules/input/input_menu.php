<?php

$domain = "messages";
//bindtextdomain($domain, "Modules/input/locale");
//bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, dirname(__FILE__)."/locale");
$menu_left[] = array('name'=> dgettext($domain, "Input"), 'path'=>"input/view" , 'session'=>"write", 'order' => 1 );

