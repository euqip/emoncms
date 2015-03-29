/*

  multigraph_edit.js - Licence: GNU GPL Affero, Author: Trystan Lea

  draws multigraph feedlist table and handles feedlist table dynamic
  actions such as add a feed, left, right, fill.

*/

var multigraph_id = 0;
var multigraph_feedlist = [];
var multigraphs=[];
var multigraphs_name=[];

// This is used with multigraph.js to tell it to call a save request in multigraph_edit.js
// when the multigraph time window is changed.
var multigraph_editmode = true;

var movingtime = 0;

var baseElement = "#box-options";

// Multigraph new and selector interface
function multigraphGUI()
{
  $("#viewbtn").hide();
  $(baseElement).html(multigraphDropdown());
  load_events();
}

function multigraphDropdown()
{
  multigraphs_name = [];
  multigraphs = multigraph.getlist();
  // console.log("Multigraphs = ", multigraphs);
  var options = "";
  for (z in multigraphs)
  {
    multigraphs_name[multigraphs[z]['id']] = multigraphs[z]['name'];
    // console.log("item[z]", multigraphs[z]);
    options +="<option value='"+multigraphs[z]['id']+"'>"+multigraphs[z]['id']+": "+multigraphs[z]['name']+"</option>";
  }

  var out = "<div class='alert'>No multigraphs created yet, click new to create one:</div>";
  if (options)
  {
    out = "<select id='multigraph-selector' style='width:160px'><option>Select multigraph:</option>"+options+"</select>";
  }
  return out+"<button id='multigraph-new-button' class='btn btn-info' style='float:right'>New multigraph</button><div id='feedtable' ></div>";
}

// Multigraph editor interface 
function draw_multigraph_feedlist_editor()
{
  if (!multigraph_feedlist) multigraph_feedlist = [];
  if (typeof multigraph_feedlist[0] !== 'undefined' && multigraph_feedlist[0]['end'] == 0)
    movingtime=0;
  else
    movingtime=1;

  console.log("Moving time start: "+movingtime);
  var out = "<table class='table' style='table-layout:fixed; width:300px;' >";
  out += "<tr><th style='width:130px;' >Feed</th><th style='text-align: center;'>Left</th><th style='text-align: center;'>Right</th><th style='text-align: center;'>Fill</th><th style='padding:0px; width:30px;'></th></tr>";

  var publicfeed = 1;
  for (z in multigraph_feedlist)
  {
    out += "<tr>";

    out += "<td style='vertical-align:middle;word-wrap:break-word;'>"+multigraph_feedlist[z]['name']+"</td>";

    if (!multigraph_feedlist[z]['left'] && !multigraph_feedlist[z]['right'])  multigraph_feedlist[z]['left'] = true; // Default is left
    
    var checked = ""; if (multigraph_feedlist[z]['left']) checked = "checked";
    out += "<td style='text-align: center;'><input listid='"+z+"' class='left' type='checkbox' "+checked+" / ></td>";

    var checked = ""; if (multigraph_feedlist[z]['right']) checked = "checked";
    out += "<td style='text-align: center;'><input listid='"+z+"' class='right' type='checkbox' "+checked+" / ></td>";

    var checked = ""; if (multigraph_feedlist[z]['fill']) checked = "checked";
    out += "<td style='text-align: center;'><input listid='"+z+"' class='fill' type='checkbox' "+checked+" / ></td>";

    out += "<td><a class='close'><i listid='"+z+"' id='multigraph-feed-remove-button' class='icon-remove'></i></a></td>";
    
    out += "</tr>";
    
    var setColour = ""; if (multigraph_feedlist[z]['lineColour']) setColour = multigraph_feedlist[z]['lineColour'];
    out += "<tr>";
    out += "<td style='vertical-align:middle;border-color:transparent;'>Line Colour:</td>";
    out += "<td colspan='4' style='vertical-align:middle;border-color:transparent;'>  <input listid='"+z+"' style='width:110px' id='lineColour' type='color' value='#"+setColour+"'></td>";

    out += "</tr>";

    if (publicfeed == 1) publicfeed = (get_feed_public(multigraph_feedlist[z]['id']));
  }
  out += "<tr><td><select id='feedselect' style='width:220px;'>";
  
  var visurl = path+"vis/"+"multigraph?mid="+multigraph_id;
  if (publicfeed == 1) $("#embedcode").val('<iframe style="width:580px; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+visurl+'&embed=1"></iframe>'); else $("#embedcode").val('Some of the feeds selected are not public, to embed a visualisation publicly first make the feeds that you want to use public.\n\nTo embed privately:\n\n<iframe style="width:580px; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+visurl+'&embed=1&apikey='+apikey+'"></iframe>');

  for (z in feedlist)
  {
    if (feedlist[z]['datatype']==1 || feedlist[z]['datatype']==2)
    {
      out += "<option value='"+feedlist[z]['id']+"' >"+feedlist[z]['id']+" :"+feedlist[z]['tag']+":"+feedlist[z]['name'] + "</options>";
    }
  }
  
  out += "</select></td>";
  out += "<td></td>";
  out += "<td></td>";
  out += "<td><input id='add' type='button' class='btn' value='Add'/ ></td>";
  out += "<td></td>";
  out += "</tr>";

  out += "<tr><td>Floating time</strong></td>";
  var checked = ""; if (typeof multigraph_feedlist[0] !== 'undefined' && multigraph_feedlist[0]['end'] == 0) checked = "checked";
  out += "<td><input id='movingtime' type='checkbox' "+checked+" / ></td>";
  out += "<td></td>";
  out += "<td></td>";
  out += "<td></td></tr>";

  out += "</table>";
  
  var name = "<div class='input-prepend'><span class='add-on' style='width: 70px; text-align: right;'>Name</span><input class='options' id='multigraph-name' value='"+multigraphs_name[multigraph_id]+"' type='text'></div>";
  
  out += name+"<button id='delete-multigraph-button' class='btn btn-danger'><i class='icon-trash'></i> Delete multigraph</button>";
  out += "<button id='save-multigraph-button' class='btn btn-primary' style='float:right'>Save</button>";
  out += "<div id='saved' style='float:right; margin-top:5px; margin-right:10px;'>Saved</div>";

  $("#feedtable").html(out);
}

function get_feed_name(id)
{
  for (z in feedlist)
  {
    if (feedlist[z]['id'] == id) return feedlist[z]['name'];
  }
}

function get_feed_datatype(id)
{
  for (z in feedlist)
  {
    if (feedlist[z]['id'] == id) return feedlist[z]['datatype'];
  }
}

function get_feed_public(id)
{
  for (z in feedlist)
  {
    if (feedlist[z]['id'] == id) return feedlist[z]['public'];
  }
}


// Events
function load_events()
{
  $(baseElement).unbind();

  $(baseElement).on("change","#multigraph-selector",function(event){
    multigraph_id = $(this).val();
    multigraph_feedlist = multigraph.get(multigraph_id);
    // Draw multigraph feedlist editor
    draw_multigraph_feedlist_editor();
    // Draw multigraph
    multigraph_init("#visiframe");
    vis_feed_data();
  });

  $(baseElement).on("click","#multigraph-new-button",function(event){
    multigraph_id = multigraph.new();
    $(baseElement).html(multigraphDropdown());            // Reload all
    $(baseElement + ' #multigraph-selector').val(multigraph_id);  // Refresh
    $(baseElement + ' #multigraph-selector').change();            // 
  });

  $(baseElement).on("click","#add",function(event){
    var feedid = $("#feedselect").val();
    multigraph_feedlist.push({'id':feedid,'name':get_feed_name(feedid),'datatype':get_feed_datatype(feedid),'left':false,'right':false,'fill':false });
    draw_multigraph_feedlist_editor();
    vis_feed_data();
    $("#saved").hide();
  });

  $(baseElement).on("click","#movingtime",function(event){
    if($(this)[0].checked) movingtime = 0;
    else movingtime=1;
    vis_feed_data();
    $("#saved").hide();
  });
  // Event for every change event in the lineColour input for each line in the plot.
  $(baseElement).on("input","#lineColour",function(event){
    var z = $(this).attr('listid');
    multigraph_feedlist[z]["lineColour"] = $(this)[0].value;

    // Not feeding data into the visualization on change since you can type fast enough that doing so makes it feel slow.
    // vis_feed_data();

    $("#saved").hide();
  });

  // This only fires when the user either deselects the lineColour text-box, or hits enter
  // THEN we update the plot.
  $(baseElement).on("change","#lineColour",function(event){
    vis_feed_data();
    $("#saved").hide();
  });

  $(baseElement).on("click",".left",function(event){
    console.log("Click left:");
    console.log($(this)[0].checked);

    var z = $(this).attr('listid');
    multigraph_feedlist[z]['left'] = $(this)[0].checked;
    if (multigraph_feedlist[z]['left'] == true && multigraph_feedlist[z]['right'] == true)
      multigraph_feedlist[z]['right'] = false;
    $(".right[listid="+z+"]").attr("checked",false);

    vis_feed_data();
    console.log(multigraph_feedlist);
    $("#saved").hide();
  });

  $(baseElement).on("click",".right",function(){
    console.log("Click right:");
    console.log($(this).attr("checked"));

    var z = $(this).attr('listid');
    multigraph_feedlist[z]['right'] = $(this)[0].checked;
    if (multigraph_feedlist[z]['left'] == true && multigraph_feedlist[z]['right'] == true)
      multigraph_feedlist[z]['left'] = false;
    $(".left[listid="+z+"]").attr("checked",false);
    vis_feed_data();
    $("#saved").hide();
  });

  $(baseElement).on("click",".fill",function(){
    var z = $(this).attr('listid');
    multigraph_feedlist[z]['fill'] = $(this)[0].checked;
    vis_feed_data();
    $("#saved").hide();
  });

  $(baseElement).on("click","#multigraph-feed-remove-button",function(){
    var z = $(this).attr('listid');
    multigraph_feedlist.splice(z,1);
    draw_multigraph_feedlist_editor();
    vis_feed_data();
    $("#saved").hide();
  });

  $(baseElement).on("click","#delete-multigraph-button",function(){
    multigraph.remove(multigraph_id);
    $(baseElement).html(multigraphDropdown());            // Reload all
  });

  
  $(baseElement).on("input propertychange paste","#multigraph-name",function(){
    $("#saved").hide();
  });
  
  $(baseElement).on("click","#save-multigraph-button",function(event){
    $("#saved").hide();
    // Save multigraph view start and end time to feedlist array
    multigraph_feedlist[0].timeWindow = view.end - view.start;

    if (movingtime == 0)
      multigraph_feedlist[0].end = 0;
    else
      multigraph_feedlist[0].end = view.end;

    console.log(multigraph_feedlist[0].timeWindow);
    console.log(movingtime);
    console.log(multigraph_feedlist[0].end);
    
    var new_name=$("#multigraph-name").val();
    if(new_name=="") new_name="No name";

    multigraph.set(multigraph_id,multigraph_feedlist,new_name);
    $(baseElement).html(multigraphDropdown());                    // Reload all
    $(baseElement + ' #multigraph-selector').val(multigraph_id);  // Refresh
    $(baseElement + ' #multigraph-selector').change();            // 
    $("#saved").show();
  });
}
