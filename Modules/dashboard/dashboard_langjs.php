<?php
  date_default_timezone_set('UTC');

  // because langjs do not use session, $lang is unavailable
  // changed call to here wyith a lang param in the call
  $lang = "en_US";
  if (isset ($_GET['lang']))  $lang = $_GET['lang'];
  putenv("LANG=".$lang);
  setlocale(LC_ALL,$lang.'.UTF-8');
  //setlocale(LC_ALL,'fr_FR.UTF-8');
  $domain = "messages";
  bindtextdomain($domain, dirname(__FILE__)."/locale");
  bind_textdomain_codeset($domain, 'UTF-8');
?>

// Create a Javascript associative array who contain all sentences from module
var LANG_JS = new Array();

// designer.js
LANG_JS["Changed, press to save"]       = '<?php echo addslashes(_("Changed, press to save")); ?>';

// Common Widgets
LANG_JS["Feed"]                         = '<?php echo addslashes(_("Feed")); ?>';
LANG_JS["Feed value"]                   = '<?php echo addslashes(_("Feed value")); ?>';

LANG_JS["Value"]                        = '<?php echo addslashes(_("Value")); ?>';
LANG_JS["Value to show"]                = '<?php echo addslashes(_("Value to show")); ?>';

LANG_JS["Units"]                        = '<?php echo addslashes(_("Units")); ?>';
LANG_JS["Units to show"]                = '<?php echo addslashes(_("Units to show")); ?>';

LANG_JS["Type"]                         = '<?php echo addslashes(_("Type")); ?>';
LANG_JS["Type to show"]                 = '<?php echo addslashes(_("Type to show")); ?>';

LANG_JS["Max value"]                    = '<?php echo addslashes(_("Max value")); ?>';
LANG_JS["Max value to show"]            = '<?php echo addslashes(_("Max value to show")); ?>';

// button_render.js
LANG_JS["Feed to set, control with caution, make sure device being controlled can operate safely in event of emoncms failure."]
                                        = '<?php echo addslashes(_("Feed to set, control with caution, make sure device being controlled can operate safely in event of emoncms failure.")); ?>';
LANG_JS["Starting value"]               = '<?php echo addslashes(_("Starting value")); ?>';

// cylinder_render.js
LANG_JS["Bottom"]                       = '<?php echo addslashes(_("Bottom")); ?>';
LANG_JS["Top"]                          = '<?php echo addslashes(_("Top")); ?>';
LANG_JS["Bottom feed value"]            = '<?php echo addslashes(_("Bottom feed value")); ?>';
LANG_JS["Top feed value"]               = '<?php echo addslashes(_("Top feed value")); ?>';

// dial_render.js
LANG_JS["Scale"]                        = '<?php echo addslashes(_("Scale")); ?>';
LANG_JS["Scale to show"]                = '<?php echo addslashes(_("Scale to show")); ?>';


// vis_render.js
LANG_JS["Fill"]                         = '<?php echo addslashes(_("Fill")); ?>';
LANG_JS["Fill value"]                   = '<?php echo addslashes(_("Fill value")); ?>';
LANG_JS["Currency"]                     = '<?php echo addslashes(_("Currency")); ?>';
LANG_JS["Currency to show"]             = '<?php echo addslashes(_("Currency to show")); ?>';
LANG_JS["Kwh price"]                    = '<?php echo addslashes(_("Kwh price")); ?>';
LANG_JS["Set kwh price"]                = '<?php echo addslashes(_("Set kwh price")); ?>';
LANG_JS["kwhd"]                         = '<?php echo addslashes(_("kwhd")); ?>';
LANG_JS["kwhd source"]                  = '<?php echo addslashes(_("kwhd source")); ?>';
LANG_JS["Power"]                        = '<?php echo addslashes(_("Power")); ?>';
LANG_JS["Power to show"]                = '<?php echo addslashes(_("Power to show")); ?>';
LANG_JS["Threshold A"]                  = '<?php echo addslashes(_("Threshold A")); ?>';
LANG_JS["Threshold B"]                  = '<?php echo addslashes(_("Threshold B")); ?>';
LANG_JS["Threshold A used"]             = '<?php echo addslashes(_("Threshold A used")); ?>';
LANG_JS["Threshold B used"]             = '<?php echo addslashes(_("Threshold B used")); ?>';
LANG_JS["Consumption"]                  = '<?php echo addslashes(_("Consumption")); ?>';
LANG_JS["Solar"]                        = '<?php echo addslashes(_("Solar")); ?>';
LANG_JS["Consumption feed value"]       = '<?php echo addslashes(_("Consumption feed value")); ?>';
LANG_JS["Solar feed value"]             = '<?php echo addslashes(_("Solar feed value")); ?>';
LANG_JS["Ufac"]                         = '<?php echo addslashes(_("Ufac")); ?>';
LANG_JS["Ufac value"]                   = '<?php echo addslashes(_("Ufac value")); ?>';
LANG_JS["Mid"]                          = '<?php echo addslashes(_("Mid")); ?>';
LANG_JS["Mid value"]                    = '<?php echo addslashes(_("Mid value")); ?>';

//dashboard menu
// widgetlist.js
LANG_JS["Text"]                         = '<?php echo addslashes(_("Text")); ?>';
LANG_JS["Containers"]                   = '<?php echo addslashes(_("Containers")); ?>';
LANG_JS["paragraph"]                    = '<?php echo addslashes(_("paragraph")); ?>';
LANG_JS["heading"]                    = '<?php echo addslashes(_("heading")); ?>';
LANG_JS["heading-center"]                    = '<?php echo addslashes(_("heading-center")); ?>';
LANG_JS["html-list"]                    = '<?php echo addslashes(_("html-list")); ?>';
LANG_JS["html-image"]                    = '<?php echo addslashes(_("html-image")); ?>';
LANG_JS["Container-Grey"]                    = '<?php echo addslashes(_("Container-Grey")); ?>';
LANG_JS["Container-White"]                    = '<?php echo addslashes(_("Container-White")); ?>';
LANG_JS["Container-BlueLine"]                    = '<?php echo addslashes(_("Container-BlueLine")); ?>';
LANG_JS["Container-Black"]                    = '<?php echo addslashes(_("Container-Black")); ?>';

//spred into the different widgets renderers
LANG_JS["Widgets"]                    = '<?php echo addslashes(_("Widgets")); ?>';
LANG_JS["button"]                    = '<?php echo addslashes(_("button")); ?>';
LANG_JS["cylinder"]                    = '<?php echo addslashes(_("cylinder")); ?>';
LANG_JS["feedvalue"]                    = '<?php echo addslashes(_("feedvalue")); ?>';
LANG_JS["dial"]                    = '<?php echo addslashes(_("dial")); ?>';
LANG_JS["jgauge"]                    = '<?php echo addslashes(_("jgauge")); ?>';
LANG_JS["led"]                    = '<?php echo addslashes(_("led")); ?>';


LANG_JS["Visualizations"]                    = '<?php echo addslashes(_("Visualizations")); ?>';
LANG_JS["realtime"]                    = '<?php echo addslashes(_("realtime")); ?>';
LANG_JS["rawdata"]                    = '<?php echo addslashes(_("rawdata")); ?>';
LANG_JS["bargraph"]                    = '<?php echo addslashes(_("bargraph")); ?>';
LANG_JS["timestoredaily"]                    = '<?php echo addslashes(_("timestoredaily")); ?>';
LANG_JS["zoom"]                    = '<?php echo addslashes(_("zoom")); ?>';
LANG_JS["simplezoom"]                    = '<?php echo addslashes(_("simplezoom")); ?>';
LANG_JS["histgraph"]                    = '<?php echo addslashes(_("histgraph")); ?>';
LANG_JS["threshold"]                    = '<?php echo addslashes(_("threshold")); ?>';
LANG_JS["orderthreshold"]                    = '<?php echo addslashes(_("orderthreshold")); ?>';
LANG_JS["orderbars"]                    = '<?php echo addslashes(_("orderbars")); ?>';
LANG_JS["stacked"]                    = '<?php echo addslashes(_("stacked")); ?>';
LANG_JS["stackedsolar"]                    = '<?php echo addslashes(_("stackedsolar")); ?>';
LANG_JS["smoothie"]                    = '<?php echo addslashes(_("smoothie")); ?>';
LANG_JS["multigraph"]                    = '<?php echo addslashes(_("multigraph")); ?>';




function _Tr(key)
{
    // will return the default value if LANG_JS[key] is not defined.
    return LANG_JS[key] || key;
}