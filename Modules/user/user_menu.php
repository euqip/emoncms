<?php

	bindtextdomain($domain, dirname(__FILE__)."/locale");

    $menu_right[] = array('name'=> dgettext($domain, "Account"), 'path'=>"user/currentuser" , 'session'=>"write");
    $menu_right[] = array('name'=> dgettext($domain, "Logout"), 'path'=>"user/logout" , 'session'=>"write");
