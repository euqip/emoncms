<?php
  $domain = "messages";
  //bindtextdomain($domain, "Modules/admin/locale");
  bindtextdomain($domain, dirname(__FILE__)."/locale");
  //bind_textdomain_codeset($domain, 'UTF-8');
  $txt =  _("This project is a fork of bootstrap-datetimepicker project which doesn't include Time part. ");
  $txt .= _(" Some others parts has been improved as for example the load process which now accepts the ISO-8601 format.");
  $logopath = "Modules/feed/date-time-picker.png";
  $logoalt = _("DateTime Picker");
  $targetpath = "http://www.malot.fr/bootstrap-datetimepicker/";
  $module= _("Feed:");
  $credits[] = array('text'=> dgettext($domain, $txt), 'logopath'=>$logopath , 'logoalt'=>$logoalt, 'targetpath'=> $targetpath, 'module'=>$module);
