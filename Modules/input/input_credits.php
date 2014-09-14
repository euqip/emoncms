<?php
  $domain = "messages";
  //bindtextdomain($domain, "Modules/admin/locale");
  bindtextdomain($domain, dirname(__FILE__)."/locale");
  //bind_textdomain_codeset($domain, 'UTF-8');
  $txt =  _("No specific code used here. ");
  $logopath = "";
  $logoalt = _("Inputs module tools");
  $targetpath = "http://www.malot.fr/bootstrap-datetimepicker/";
  $module= _("Inputs:");
  $credits[] = array('text'=> dgettext($domain, $txt), 'logopath'=>$logopath , 'logoalt'=>$logoalt, 'targetpath'=> $targetpath, 'module'=>$module);
