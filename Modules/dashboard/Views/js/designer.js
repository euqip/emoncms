/*
 designer.js -  Licence: GNU GPL Affero, Author: Trystan Lea

 The dashboard designer works around the concept of html elements with fixed positions
 and specified widths and heights. Its a box model where each box can hold a widget.
 A box specifies which widget it is by its class. The properties of a widget such as
 the feedid to use is also specified in the html element:

 <div class="dial" feedid="1" style="position:absolute; top:50px; left:50px; width:200px; height:100px;" ></div>

 render.js and associated widget render scripts then inserts the dial javascript into the element specified in the designer.

 The dashboard designer creates a canvas layer above the dashboard html elements layer and uses jquery to get the mouse positions and actions that specify the box position and dimentions.

 The functions: draw_options(box_options, options_type) and widget_buttons() draw the menu and widget options interface.


 Notes:

 3 Dec 2012 - multigraph id selector drop down menu could be refactored for more generic implementation as a drop down
 selector from options potentially specified in the widget lists.

*/
/* jshint undef: true, unused: true */
/* global $,grid_size, widget, widgets, path,redraw, dashid,saved,redraw,feedlist,multigraphs, resize */
"use strict";
var selected_edges = {none : 0, left : 1, right : 2, top : 3, bottom : 4, center : 5};

var designer = {

    'grid_size':10,
    'page_width':960,
    'page_height':500,

    'cnvs':null,
    'canvas':null,
    //'designer.ctx':null,
    'widgets':null,

    'boxlist': {},
    'resize': {},

    'selected_box': null,
    'selected_edge': selected_edges.none,
    'edit_mode': true,
    'create': null,

    'boxi': 0,

    'mousedown': false,

    'init': function()
    {
        //designer.cnvs = document.getElementById("can");
        //designer.ctx = designer.cnvs.getContext("2d");
        designer.canvas = "#can";
        designer.grid_size = grid_size;
        designer.widgets = widgets;

        $("#when-selected").hide();
        designer.scan();
        $("#page-container").css("height",designer.page_height);
        $("#can").attr("height",designer.page_height);
        designer.draw();
        designer.widget_buttons();
        designer.add_events();
    },


    'snap': function(pos) {
        return Math.round(pos/designer.grid_size)*designer.grid_size;
    },

    'modified': function() {
        $("#save-dashboard").attr('class','btn btn-warning').text(tobesaved);
    },

    'onbox': function(x,y)
    {
        var z;
        var oldbox = designer.selected_box;
        var box = null;
        // each click on a box will refresh the selected box
        // if ghost is visible and the same box is selected then do nothing, except if an other box can be selected
        if(designer.selected_box){
            designer.unsurround();
        }
        //for (z = 0; z < designer.boxlist.length; z++) {
        for (z in designer.boxlist) {
            if (x>designer.boxlist[z].left && x<(designer.boxlist[z].left+designer.boxlist[z].width)) {
                if (y>designer.boxlist[z].top && y<(designer.boxlist[z].top+designer.boxlist[z].height)) {
                    //console.log("possible boxes:"+z)
                    box = z;
                    //if (box >oldbox) return box;
                }
            }
        }
        return box;
    },

    'zindex': function(move){
        var zindex;
        move = parseInt(move);
        if (designer.selected_box!==null){
            var x = $('#'+designer.selected_box);
            if (x.css("z-index")==="auto"){
                zindex= 0;
            } else{
                zindex= x.css("z-index");
            }
            zindex = parseInt(zindex)+parseInt(move);
            if ((zindex <= $("#can").css("z-index")-1) && (zindex>0)){
                x.css("z-index",zindex);
            }
        }
        return true;
    },

    'scan': function()
    {
        //for (var z = 0; z < widget.length; z++) {
        for (var z in widgets)        {
            // make sure the different boxes does not overflow container
            // if it is the case, the the container box is made larger
            // Why not the same for thee width?
            // Avoid to use a function within a for loop
            $("."+z).each(function(){
                var id = 1*($(this).attr("id"));
                if (id>designer.boxi) designer.boxi = id;
                designer.boxlist[id] = {
                    'top':parseInt($(this).css("top")),
                    'left':parseInt($(this).css("left")),
                    'width':parseInt($(this).css("width")),
                    'height':parseInt($(this).css("height"))
                };

                if ((designer.boxlist[id].top + designer.boxlist[id].height)>designer.page_height) designer.page_height = (designer.boxlist[id].top + designer.boxlist[id].height);
            });
        }
    },

    'delwidget': function(){
        if (designer.selected_box) {
            delete designer.boxlist[designer.selected_box];
            $("#"+designer.selected_box).remove();
            designer.selected_box = 0;
            //designer.draw();
            designer.modified();
            $("#when-selected").hide();
            $("#ghost").hide();
        }

    },

    'draw': function(){
        designer.page_width = parseInt($('#dashboardpage').width());
        $('#can').width($('#dashboardpage').width());
        redraw = 1;
    },

    'draw_options': function(widget)
    {
        var box_options = widgets[widget].options;
        var options_type = widgets[widget].optionstype;
        var options_name = widgets[widget].optionsname;
        var optionshint = widgets[widget].optionshint;

        // Used for defining data to be pre-loaded into the relevant selector
        var optionsdata = widgets[widget].optionsdata;
        var helptext = widgets[widget].helptext;
        var i,z;
        if  (helptext=== undefined){
           helptext = '';
        }
            // Build options table html
        var options_html = "<table>";
        //for (z = 0; z < box_options.length; z++) {
        for (z in box_options)        {
            // look into the designer DOM to extract the div parameters from the selected widget.
            var selected = "";
            var val = $("#"+designer.selected_box).attr(box_options[z]);

            if (val === undefined) val="";

            options_html += "<tr><td class = 'option_name'>"+options_name[z]+":</td>";

            if (options_type && options_type[z] == "feed")
            {
                options_html += "<td><select id='"+box_options[z]+"'' class='form-control options' >";
                selected = "";
                //for (i = 0; i < feedlist.length; i++) {
                for (i in feedlist) {
                    if (val == feedlist[i].name.replace(/\s/g, '-'))
                        selected = "selected";
                    options_html += "<option value='"+feedlist[i].name.replace(/\s/g, '-')+"' "+selected+" >"+feedlist[i].name+"</option>";
                }
            }

            else if (options_type && options_type[z] == "feedid")
            {
                options_html += "<td><select id='"+box_options[z]+"' class='form-control options' >";
                selected = "";
                //for (i = 0; i < feedlist.length; i++) {
                for (i in feedlist) {
                    if (val == feedlist[i].id)
                        selected = "selected";
                    options_html += "<option value='"+feedlist[i].id+"' "+selected+" >"+feedlist[i].id+": "+feedlist[i].name+"</option>";
                }
            }

            else if (options_type && options_type[z] === "multigraph")
            {
                options_html += "<td><select id='"+box_options[z]+"' class='form-control options' >";
                selected = "";
                //for (i = 0; i < multigraphs.length; i++) {
                 for (i in multigraphs)                {
                    if (val == multigraphs[i].id)
                        selected = "selected";
                    options_html += "<option value='"+multigraphs[i].id+"' "+selected+" >"+multigraphs[i].id+": "+multigraphs[i].name+"</option>";
                }
            }

            else if (options_type && options_type[z] === "html") {
                val = $("#"+designer.selected_box).html();
                options_html += "<td><textarea class='form-control options' id='"+box_options[z]+"' >"+val+"</textarea>";
            }

            // Combobox for selecting options
             // Check we have optionsdata before deciding to draw a combobox
            else if (options_type && options_type[z] == "dropbox" && optionsdata[z]){
                options_html += "<td><select id='"+box_options[z]+"' class='form-control options' >";
                //for (i = 0; i < optionsdata[z].length; i++) {
                for (i in optionsdata[z])       {
                    selected = "";
                    if (val == optionsdata[z][i][0]){
                          selected = "selected";
                    }
                    options_html += "<option "+selected+" value=\""+optionsdata[z][i][0]+"\">"+optionsdata[z][i][1]+"</option>";
                }
            }

            else if (options_type && options_type[z] == "colour_picker") {
                 options_html += "<td><input  type='color' class='form-control options' id='"+box_options[z]+"'  value='#"+val+"'/ >";
            }


            // // Radio-buttons for selecting options
            // // It was a bit confusing to use, so it's disabled until I get a change to revisit and style it better (Fake-name)
            // else if (options_type && options_type[z] == "toggle" && optionsdata[z])  // Check we have optionsdata before deciding to draw a combobox
            // {
            //  options_html += "<td>";
            //  for (i in optionsdata[z])
            //  {
            //      var selected = "";
            //      if (val == optionsdata[z][i][0])
            //          selected = "checked";

            //      options_html += "<input type='radio' name='"+box_options[z]+"' value='0' style='vertical-align: baseline; padding: 5px; margin: 5px;' "+selected+">"+optionsdata[z][i];+"<br>"
            //  }
            // }

            else {
                options_html += "<td><input class='form-control options' id='"+box_options[z]+"' type='text' value='"+val+"'/ >";
            }

            options_html += "</td><td><small><p class='muted'>"+optionshint[z]+"</p></small></td></tr>";

        }
        var x = 1/0;

        options_html += "</table>";
        options_html += "<p>"+helptext+"</p>";

        // Fill the modal configuration window with options
        //$("#widget_options_body").html(options_html);
        return options_html;
    },

     'widget_buttons': function()    {
        var widget_html = "";
        var select = [];
        var z;

        //for (z = 0; z < widgets.length; z++) {
        for (z in widgets) {
            var menu = widgets[z].menu;
            var displayname = (widgets[z].itemname===undefined)?z:widgets[z].itemname;
            if (typeof select[menu] === "undefined") select[menu]="";
            select[menu] += "<li><a id='"+z+"' class='widget-button'>"+displayname+"</a></li>";
            }

        //for (z = 0; z < select.length; z++) {
        for (z in select){
            widget_html += "<div class='btn-group'><button class='btn dropdown-toggle widgetmenu' data-toggle='dropdown'>"+z+"&nbsp<span class='caret'></span></button>";
            widget_html += "<ul class='dropdown-menu' name='d'>"+select[z]+"</ul>";
        }
        $("#widget-buttons").html(widget_html);

        $(".widget-button").click(function(event) {
            designer.create = $(this).attr("id");
            designer.edit_mode = false;
        });
    },

    'add_widget': function(mx,my,type)    {
        designer.boxi++;
        var html = widgets[type].html;
        if (html === undefined) html = "";
        $("#page").append('<div id="'+designer.boxi+'" class="'+type+'" style="position:absolute; margin: 0; top:'+designer.snap(my+widgets[type].offsety)+'px; left:'+designer.snap(mx+widgets[type].offsetx)+'px; width:'+widgets[type].width+'px; height:'+widgets[type].height+'px;" >'+html+'</div>');

        designer.scan();
        redraw = 1;
        designer.edit_mode = true;
    },
    'savedashboard': function(){
        //recalculate the height so the page_height is shrunk to the minimum but still wrapping all components
        //otherwise a user can drag a component far down then up again and a too high value will be stored to db.
        designer.page_height = 0;
        //adjust page height
        designer.scan();
        // store the HTML content
        $.ajax({
          type: "POST",
          url :  path+"dashboard/setcontent.json",
          data : "&id="+dashid+'&content='+encodeURIComponent($("#page").html())+'&height='+designer.page_height,
          dataType: 'json',
          success : function(data) { console.log(data); if (data.success===true) $("#save-dashboard").attr('class','btn btn-success').text(saved);
          }
        });
    },
    "surround" : function(boxid){
        var ghost= $("#ghost");
        var mybox = $("#"+boxid);
        ghost.css("top",mybox.css("top"));
        ghost.css("left",mybox.css("left"));
        ghost.css("width",mybox.css("width"));
        ghost.css("height",mybox.css("height"));
        //link ghost to mybox to drag & resize them together use options
        //$( ".selector" ).resizable( "option", "alsoResize", "#mirror" );
        var str = mybox.attr("class");
        if (str.match(/html/)){
            $("#ghost").resizable( "option", "alsoResize", "#"+boxid );
            $("#ghost").resizable( "option", "aspectRatio", false );
        } else{
            $("#ghost").resizable( "option", "alsoResize", "#can-"+boxid );
        }
        $("#ghost").draggable( "option", "alsoDrag", "#"+boxid );
        ghost.show();
    },
    'unsurround' : function(boxid){
        var ghost = $("#ghost");
        var mybox = $("#"+boxid);
        var can   = $("#can-"+boxid);
        var to    = designer.snap(parseInt(ghost.css("top")))+'px';
        var le    = designer.snap(parseInt(ghost.css("left")))+'px';
        var wi    = designer.snap(parseInt(ghost.css("width")));
        var he    = designer.snap(parseInt(ghost.css("height")));
        //jquery UI changes style, not width & height, it's not canvas convenient so:
        can.attr({
            width  : wi,
            height : he,
            style  : ""
        });
        mybox.css({
            "top"    : to,
            "left"   : le,
            "width"  : wi+'px',
            "height" : he+'px',
        });
        designer.modified();
        designer.selected_box = null;
        ghost.hide();
        designer.scan();
        if (mybox.attr('class')===undefined){
        } else {
            var fname = mybox.attr('class')+"_repaint";
            //var param = mybox.attr('generictype');
            var param = mybox.attr('id');
            //var fn = fname;
            try {
                var fn = window[fname];
                fn(param);
            } catch (err){
                console.log (err);
            }
        }

        //repaint steelseries widgets to avoid flickering
        //steelseries.clock.repaint();
        /*
            var fname = widget[z]+"_fastupdate";
            var fn = window[fname];
            fn();

         */
    },

    'add_events': function() {
        // Click to select
        $(this.canvas).click(function(event) {

            var mx = 0, my = 0;
            if(event.offsetX===undefined)
            {
                mx = (event.pageX - $(event.target).offset().left);
                my = (event.pageY - $(event.target).offset().top);
            } else {
                mx = event.offsetX;
                my = event.offsetY;
            }
            var oldbox = designer.selected_box;
            if (designer.edit_mode) designer.selected_box = designer.onbox(mx,my);
            if (!designer.selected_box){
                $("#when-selected").hide();
                designer.unsurround(oldbox);
            } else {
            // designer.draw was used to redraw grid and get widgets dims
                designer.draw();
                designer.surround (designer.selected_box);
                $("#when-selected").show();
            }

        });
        // will have to be replaced with jquery resize and drag api
        $(this.canvas).mousedown(function(event) {
            designer.mousedown = true;
            var mx = 0, my = 0;
            if(event.offsetX===undefined) // this works for Firefox
            {
                mx = (event.pageX - $(event.target).offset().left);
                my = (event.pageY - $(event.target).offset().top);
            } else {
                mx = event.offsetX;
                my = event.offsetY;
            }
            if (designer.create)
            {
                designer.add_widget(mx,my,designer.create);
                designer.create = null;
                //  $('option:selected', 'select').removeAttr('selected');
                //  $('option[title=1]').attr('selected','selected');
                $("#when-selected").show();
            }
        });

/*
        $(this.canvas).mouseup(function(event) {
            designer.mousedown = false;
            selected_edge = selected_edges.none;
        });
        $(this.canvas).mousemove(function(event) {
            // On resize
            if (designer.mousedown && designer.selected_box && selected_edge){

                var mx = 0, my = 0;
                if(event.offsetX===undefined) // this works for Firefox
                {
                    mx = (event.pageX - $(event.target).offset().left);
                    my = (event.pageY - $(event.target).offset().top);
                } else {
                    mx = event.offsetX;
                    my = event.offsetY;
                }

                var rightedge = resize.left+resize.width;
                var bottedge = resize.top+resize.height;

                switch(selected_edge)
                {
                    case selected_edges.right:
                        designer.boxlist[designer.selected_box].width = (designer.snap(mx)-resize.left);
                        break;
                    case selected_edges.left:
                        designer.boxlist[designer.selected_box].left = (designer.snap(mx));
                        designer.boxlist[designer.selected_box].width = rightedge - designer.snap(mx);
                        break;
                    case selected_edges.bottom:
                        designer.boxlist[designer.selected_box].height = (designer.snap(my)-resize.top);
                        break;
                    case selected_edges.top:
                        designer.boxlist[designer.selected_box].top = (designer.snap(my));
                        designer.boxlist[designer.selected_box].height = bottedge - designer.snap(my);
                        break;
                    case selected_edges.center:
                        designer.boxlist[designer.selected_box].left = (designer.snap(mx-designer.boxlist[designer.selected_box].width/2));
                        designer.boxlist[designer.selected_box].top = (designer.snap(my-designer.boxlist[designer.selected_box].height/2));
                        break;
                }

                if (bottedge>parseInt($("#page-container").css("height")))
                {
                    $("#page-container").css("height",bottedge);
                    $("#can").attr("height",bottedge);
                    designer.page_height = bottedge;
                }

                designer.draw();
            }
        });
*/
    },
};

        // On save click  save function is located in dashboard_edit_view.php
        $("#options-save").click(function()
        {
            $(".options").each(function() {
                if ($(this).attr("id")=="html")
                {
                    $("#"+designer.selected_box).html($(this).val());
                }
                else if ($(this).attr("id")=="colour")
                {
                    // Since colour values are generally prefixed with "#", and "#" isn't valid in URLs, we strip out the "#".
                    // It will be replaced by the value-checking in the actual plot function, so this won't cause issues.
                    var colour = $(this).val();
                    colour = colour.replace("#","");
                    $("#"+designer.selected_box).attr($(this).attr("id"), colour);
                }
                else
                {
                    $("#"+designer.selected_box).attr($(this).attr("id"), $(this).val());
                }
            });
            $('#widget_options').modal('hide');
            redraw = 1;
            reloadiframe = designer.selected_box;
            $("#state").html("Changed");
        });

         $("#delete-button").click(function(event) {
            designer.delwidget();
        });

        $("#options-button").click(function(event) {
            if (designer.selected_box){
                designer.draw_options($("#"+designer.selected_box).attr("class"));
            }
        });


// found here  :  https://forum.jquery.com/topic/dragging-a-group-of-items-alsodrag-like-alsoresize
$.ui.plugin.add("draggable", "alsoDrag", {
    start: function() {
        var that = $(this).data("ui-draggable"),
            o = that.options,
            _store = function (exp) {
                $(exp).each(function() {
                    var el = $(this);
                    el.data("ui-draggable-alsoDrag", {
                        top: parseInt(el.css("top"), 10),
                        left: parseInt(el.css("left"), 10)
                    });
                });
            };

        if (typeof(o.alsoDrag) === "object" && !o.alsoDrag.parentNode) {
            if (o.alsoDrag.length) { o.alsoDrag = o.alsoDrag[0]; _store(o.alsoDrag); }
            else { $.each(o.alsoDrag, function (exp) { _store(exp); }); }
        }else{
            _store(o.alsoDrag);
        }
    },
    drag: function () {
        var that = $(this).data("ui-draggable"),
            o = that.options,
            //os = that.originalSize,
            op = that.originalPosition,
            delta = {
                top: (that.position.top - op.top) || 0,
                left: (that.position.left - op.left) || 0
            },

            _alsoDrag = function (exp, c) {
                $(exp).each(function() {
                    var el = $(this), start = $(this).data("ui-draggable-alsoDrag"), style = {},
                        css = ["top", "left"];

                    $.each(css, function (i, prop) {
                        var sum = (start[prop]||0) + (delta[prop]||0);
                        style[prop] = sum || null;
                    });

                    el.css(style);
                });
            };

        if (typeof(o.alsoDrag) === "object" && !o.alsoDrag.nodeType) {
            $.each(o.alsoDrag, function (exp, c) { _alsoDrag(exp, c); });
        }else{
            _alsoDrag(o.alsoDrag);
        }
    },
    stop: function() {
        $(this).removeData("draggable-alsoDrag");
    }
});
