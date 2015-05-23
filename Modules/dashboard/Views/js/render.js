/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org

  render.js goes through all the dashboard html elements that specify the dashboard widgets
  and inserts the dials, visualisations to be displayed inside the element.
  see designer.js for more information on the html element widget box model.

  render.js calls the render scripts of all the widgets which is where all the
  individual widget render code is located.
*/

// Global page vars definition

// Array that holds ID's of feeds of associative key
// 
/* jshint undef: true, unused: true */
/* global $, widget,redraw, path, userid, redraw */
"use strict";
var feedids = [];
// Array for exact values
var assoc = [];
// Array for smooth change values - creation of smooth dial widget
var assoc_curve = [];
var widgetcanvas = {};

var dialrate = 0.02;
var browserVersion = 999;

var Browser =  {
    Version : function()
    {
      var version = 999;
      if (navigator.appVersion.indexOf("MSIE") != -1)
        version = parseFloat(navigator.appVersion.split("MSIE")[1]);
      return version;
    }
  };

function show_dashboard()
{
  browserVersion = Browser.Version();
  if (browserVersion < 9) dialrate = 0.2;

  for (var z = 0; z < widget.length; z++) {
    var fn = window[widget[z]+"_init"];
    fn();
  }

  update();
}

// update function
function update()
{
  var z;
  var query = path + "feed/list.json?userid="+userid;
  $.ajax(
  {
    url : query,
    dataType : 'json',
    success : function(data)
    {

      for (z = 0; z < data.length; z++) {
        var newstr = data[z].name.replace(/\s/g, '-');
        var value = parseFloat(data[z].value);
        $("." + newstr).html(value);
        assoc[newstr] = value * 1;
        feedids[newstr] = data[z].id;
      }

      for (z = 0; z < widget.length; z++) {
        var fn = window[widget[z]+"_slowupdate"];
        fn();
      }
    }
  });
}

function fast_update()
{
  var  z,  fn;
  if (redraw)
  {
    for (z = 0; z < widget.length; z++) {
      fn = window[widget[z]+"_init"];
      fn();
    }

  }

  for (z = 0; z < widget.length; z++) {
    fn = window[widget[z]+"_fastupdate"];
    fn();
  }
    redraw = 0;
}

function curve_value(feed,rate)
{
  var val = 0;
  if (feed) {
    if (!assoc_curve[feed]) assoc_curve[feed] = 0;
    assoc_curve[feed] = assoc_curve[feed] + ((parseFloat(assoc[feed]) - assoc_curve[feed]) * rate);
    val = assoc_curve[feed] * 1;
  }
  return val;
}

function setup_widget_canvas(elementclass)
{
  //$('.'+elementclass).each(function(index)
  $('.emon_widget').each(function(index)
  {
    var widgetId = $(this).attr("id");

    var width = $(this).width();
    var height = $(this).height();
    var canvas = $(this).children('canvas');

    var canvasid = "can-"+widgetId;
    // 1) Create canvas if it does not exist
    if (!canvas[0])
    {
      $(this).html('<canvas id="'+canvasid+'" width="'+width+'" height="'+height+'"></canvas>');
    }

    // 2) Resize canvas if it needs resizing
    if (canvas.attr("width") != width) canvas.attr("width", width);
    if (canvas.attr("height") != height) canvas.attr("height", height);

    canvas = document.getElementById(canvasid);
    if (browserVersion != 999)
    {
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      if ( typeof G_vmlCanvasManager != "undefined") G_vmlCanvasManager.initElement(canvas);
    }
    // 3) Get and store the canvas context
    widgetcanvas[canvasid] = canvas.getContext("2d");
  });
}
