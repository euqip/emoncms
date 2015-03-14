<?php

bindtextdomain($domain, dirname(__FILE__)."/locale");
$menu_right[] = array('name'=> dgettext($domain, "Schedule"), 'path'=>"schedule/view" , 'session'=>"write" );