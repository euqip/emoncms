<?php
  $domain = "messages";
  bindtextdomain($domain, dirname(__FILE__)."/locale");
  $txt =  _("No specific code used here. ");
  $logopath = "";
  $logoalt = _("Inputs module tools");
  $targetpath = "http://emoncms.org/";
  $module= _("Inputs:");
  $credits[] = array('text'=> dgettext($domain, $txt), 'logopath'=>$logopath , 'logoalt'=>$logoalt, 'targetpath'=> $targetpath, 'module'=>$module);
