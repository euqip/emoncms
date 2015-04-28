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
	oldval = null;

function leddisplay_widgetlist()
{
  var widgets = {
    "leddisplay":
    {
      "offsetx":0,"offsety":0,"width":160,"height":50,
      "menu":"Widgets",
      "itemname":_Tr("Led Display"),
      "options":["feed", "min", "max","unit", "cautionLimit","warningLimit"],
      "optionstype":["feed","value","value","value","value","value"],
      "optionsname":[_Tr("Feed"),_Tr("Min value"),_Tr("Max value"),_Tr("Displayed units"),_Tr("Caution level"),_Tr("Warning level")],
      "optionshint":[_Tr("Feed"),_Tr("Min value to show"),_Tr("Max value to show"),_Tr("the engineer units on panel"),_Tr("Above caution in Amber"),_Tr("Above Warning is red")],
      "helptext":_Tr("Green panel led display will change colour to AMBER when caution value is reached, or RED when warning is reached.  Behavior depends on values given to warning and caution thresholds. <br /> going up when Caution < Warning <br /> going down when Caution > Warning")

    }
  }
  return widgets;
}

function leddisplay_init()
{
	setup_widget_canvas('leddisplay');

}




function leddisplay_draw()
{
  $('.leddisplay').each(function(index)
  {
    var digitcount = 4;
    var decimal = 2;
    var feed = $(this).attr("feedname");
    var caution = $(this).attr("cautionLimit");
    var warning = $(this).attr("warningLimit");
    var unit = $(this).attr("unit");
    if (feed==undefined) feed = $(this).attr("feed");
    if (unit==undefined) unit = '--';

    var units = $(this).attr("units");
    var val = assoc[feed];
    var negative=false

    if (feed==undefined) val = 0;
    if (units==undefined) units = '';
    if (val==undefined) val = 0;
    if(val !=oldval) {
      //check for negative value
      if(val <0) negative = true;
      //check for value overflow
      var maxval = "999999999999";
      maxval = maxval.slice(0,digitcount-1);
      var ol = parseInt (maxval);
      var color = "green";

      var goingup=true;
        if (val  > caution) color = "amber";
        if (val  > warning) color = "red";
      if (warning <caution){
        goingup =false;
        color = "green";
        if (val  < caution) color = "amber";
        if (val  < warning) color = "red";
      }

      if (val=="NaN") val="0";
      var oustring= "0000000"+Math.abs(val);

      /*split value to characters
      */
      var parts = oustring.split(".");
      var len=parts[0].length-digitcount;
      var num = parts[0].slice(len,len+digitcount);
      var res = num.split("");
      var notzero = false;
  		var html='';
      html +='<div class = "digit-display" >';
      if (Math.abs(val)<ol){
          var index;
          var dot = "";
          for (index = 0; index < res.length; ++index) {
              if(index==digitcount-1) dot = " dot";
              if (res[index]>"0") notzero = true;
              if (notzero==false) res[index] = "blank";
              if(negative) res[0]='-';
              html +='<span class="sprites sprites-sm '+color+ dot+' num'+res[index]+'"></span>';
          }
          if (parts[1]==undefined) parts[1]="0"
          parts[1]=parts[1]+"0000000";
          var num = parts[1].slice(0,decimal);
          var res = num.split("");
          for (index = 0; index < decimal; ++index) {
              html +='<span class="sprites sprites-sm '+color+' num'+res[index]+'"></span>';
          }

      }else{
          html +='<span class="sprites sprites-sm green numblank" ></span>';
          html +='<span class="sprites sprites-sm green numblank" ></span>';
          html +='<span class="sprites sprites-sm green numo" ></span>';
          html +='<span class="sprites sprites-sm green numl" ></span>';
          html +='<span class="sprites sprites-sm green numblank" ></span>';
          html +='<span class="sprites sprites-sm green numblank" ></span>';
      }
      html +='<span class="panelunit">'+unit+'</span>';
      html +='<div class="panelfeed">'+feed+'</div>';

      html +='</div>';
      $(this).html(html);
    }
  });
}

function leddisplay_slowupdate()
{
  leddisplay_draw();
}

function leddisplay_fastupdate()
{
	leddisplay_draw();
}

