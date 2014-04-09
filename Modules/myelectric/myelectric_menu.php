<?php

$domain = "messages";
//bindtextdomain($domain, "Modules/admin/locale");
bindtextdomain($domain, dirname(__FILE__)."/locale");
//bind_textdomain_codeset($domain, 'UTF-8');

global $session, $user;

if ($session['write']) $apikey = "?apikey=".$user->get_apikey_write($session['userid']); else $apikey = "";

$menu_left[] = array('name'=>dgettext($domain,"My Electric"), 'path'=>"myelectric".$apikey , 'session'=>"write", 'order' => -2 );



?>
