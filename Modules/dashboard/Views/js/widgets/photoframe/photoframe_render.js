/*
   All emon_widgets code is released under the GNU General Public License v3.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    Author: Trystan Lea: trystan.lea@googlemail.com
    If you have any questions please get in touch, try the forums here:
    http://openenergymonitor.org/emon/forum
 */

// Global variables
var img = null,
	needle = null;
	
function photoframe_widgetlist()
{
  var widgets = {
    "photoframe":
    {
      "offsetx":0,"offsety":0,"width":160,"height":60,
      "menu":"Widgets",
      "itemname":_Tr("Photo Frame"),
      "options":["feed", "min", "max","unit", "cautionLimit","warningLimit"],
      "optionstype":["feed","value","value","value","value","value"],
      "optionsname":[_Tr("Feed"),_Tr("Min value"),_Tr("Max value"),_Tr("Displayed units"),_Tr("Caution level"),_Tr("Warning level")],
      "optionshint":[_Tr("Feed"),_Tr("Min value to show"),_Tr("Max value to show"),_Tr("the engineer units on panel"),_Tr("Above caution in Amber"),_Tr("Above Warning is red")],
      "helptext":_Tr("Green panel led display will change colour to AMBER when caution value is reached, or RED when warning is reached.  Behavior depends on values given to warning and caution thresholds. <br /> going up when Caution < Warning <br /> going down when Caution > Warning")

    }
  }
  return widgets;
}

function photoframe_init()
{
	setup_widget_canvas('photoframe');

}




function photoframe_draw()
{
}
function photoframe_slowupdate()
{
  photoframe_draw();
}

function photoframe_fastupdate()
{
	photoframe_draw();
}

