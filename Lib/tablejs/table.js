/*
table.js is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/
/* jshint undef: true, unused: true */
/* global $, path, list */
var table =
{
    'data':0,
    'groupshow':{},
    'eventsadded':false,
    'deletedata':true,
    'sortfield':null,
    'sortorder':null,
    'sortable':true,

    'groupprefix':"",
    'expanded':true,
    'collapse':false,
    'expand':false,
    'state':0,
    'tablegrpidshow':false,
    'collapsetext':"Collapse group",
    'expandtext':"Expand group",
    'minus':'',
    'plus' :'',

    'draw':function()
    {
        table.minus='<span class="glyphicon glyphicon-minus-sign" title="'+table.collapsetext+'"></span>';
        table.plus='<span class="glyphicon glyphicon-plus-sign" title="'+table.expandtext+'"></span>';
        if (table.data && table.sortable && table.sortfield) {
                table.data.sort(function(a,b) {
                var x=a[table.sortfield];
                var y=b[table.sortfield];
                x=(x===null)? Number.POSITIVE_INFINITY:x;
                y=(y===null)? Number.POSITIVE_INFINITY:y;
                if ((x===true) || (x===false)) x=(x===false)? 0:1;
                if ((y===true) || (y===false)) y=(y===false)? 0:1;
                if ($.isNumeric(x) && $.isNumeric(y)){
                    var numa=parseFloat(x);
                    var numb=parseFloat(y);
                    return (table.sortorder==1) ? (numa-numb) : numb-numa;
                } else {
                     if (typeof x == 'string') x=x.toUpperCase().replace(" ", "");
                     if (typeof y == 'string') y=y.toUpperCase().replace(" ", "");
                }
                if (table.sortorder==1){
                    return x<y ? -1:1;
                } else{
                    return x>y ? -1:1;
                }
            });
        }
        var group_num = 0, group;
        var groups = {};
        //for (z = 0; z < box_options.length; z++) {
        for (var row =0 ;row < table.data.length; row ++)
        //for (var row in table.data)
        {
            group = table.data[row][table.groupby];
            if (!group) group = 'NoGroup';
            if (!groups[group]) {groups[group] = ""; group_num++;}
            groups[group] += table.draw_row(row);

            // when a collapse or an expand are triggered set it for each group

        }
        // translate icon titles
        for (group in groups) {
            if (table.collapse===true) {
                table.groupshow[group]=false;
            }
            if (table.expand===true) {
                table.groupshow[group]=true;
            }
        }
        var html = "";
        table.collapse=false;
        table.expand=false;

        for (group in groups)
        {
            //groups presentation is tri state
            //2: no groups to enable full table sort
            //0: collapsed grous to  reduce amont of information on screen
            //1: expanded groups like original prsentation
            // Minimized group persistance, see lines: 4,92,93
            //var minus='<span class="glyphicon glyphicon-minus-sign"></span>';
            //var plus ='<span class="glyphicon glyphicon-plus-sign"></span>';
            var visible = '', symbol = table.minus, field;
            if (table.groupshow[group]===undefined) table.groupshow[group]=table.expanded;
            if (table.groupshow[group]===false) {symbol = table.plus; visible = "display:none";}
            if (group_num>1) {
                html += "<tr class='groupheader'><th colspan='4'><a class='MINMAX' group='"+group+"' >"+symbol+table.groupprefix+group+"</a> </th>";
                for (field in table.fields) html += "<th></th>";          // Add th padding
                html += "</tr>";
                }
            html += "<tbody id='"+group+"' style='"+visible+"'><tr>";
            for (field in table.fields)
            {
                var fld = table.fields[field];
                var title = (fld.title !== undefined) ? fld.title : field;
                var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
                var colwidth = (fld.colwidth !== undefined) ? fld.colwidth : '';
                var display = (fld.display !== undefined) ? fld.display : 'yes';
                if ((display =="yes")||((display=="dynamic") && (table.tablegrpidshow===true))){
                    html += "<th><a type='sort' field='"+field+"' title='"+tooltip+"' "+colwidth+">"+title+"</a></th>";
                }
            }
            html += "</tr>";
            html += groups[group];
            html += "</tbody>";
        }
        $(table.element).html("<table class='table table-hover'>"+html+"</table>");
        if (table.eventsadded===false) {
            table.add_events();
            table.eventsadded = true;
        }
        $(table.element).trigger("onDraw");
    },
    'draw_row': function(row)
    {
        var html = "<tr uid='"+row+"' >";
        //insert here the icon tooltips for Edit and delete
        for (var field in table.fields) {
            var fld = table.fields[field];
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            var colwidth = (fld.colwidth !== undefined) ? fld.colwidth : '';
            var display = (fld.display !== undefined) ? fld.display : 'yes';
            //var tooltip = ''; if (fld.tooltip!==undefined) tooltip = fld.tooltip;
            //var colwidth = ''; if (fld.colwidth!==undefined) colwidth = ""+fld.colwidth+"";
            //var display = 'yes'; if (fld.display!==undefined) display = fld.display;
            if ((display =="yes")||((display=="dynamic") && (table.tablegrpidshow===true))){
                html += "<td row='"+row+"' field='"+field+"'"+colwidth+" title='"+tooltip+"' >";
                html += table.fieldtypes[fld.type].draw(row,field)+"</td>";
            }
        }
        return html + "</tr>";
    },
    'update':function(row,field,value)
    {
        table.data[row][field] = value;
        var type = table.fields[field].type;
        if(typeof table.fieldtypes[type].draw === 'function') {
            $("[row="+row+"][field="+field+"]").html(table.fieldtypes[type].draw(row,field));
        }
    },
    'remove':function(row)
    {
        table.data.splice(row,1);
        $("tr[uid="+row+"]").remove();
    },
    'sort':function(field,dir)
    {
        table.sortorder = (table.sortfield == field) ?-table.sortorder : 1;
        /*
        if(table.sortfield == field){
            table.sortorder = -table.sortorder;
        }else{
            table.sortorder = 1;
        }
        */
        table.sortfield = field;
        //table.sortorder = dir;
        table.draw();
    },
    'add_events':function() {
        // Event: minimise or maximise group
        $(table.element).on('click', '.MINMAX', function() {
            var group = $(this).attr('group');
            var state = table.groupshow[group];
            if (state ===true) { $("#"+group).hide(); $(this).html(table.plus+table.groupprefix+group); table.groupshow[group] = false; }
            if (state ===false) { $("#"+group).show(); $(this).html(table.minus+table.groupprefix+group); table.groupshow[group] = true; }
        });
        // Event: sort by field
        $(table.element).on('click', 'a[type=sort]', function() {
            var field = $(this).attr('field');
            var dir = $(this).attr('sortorder');
            if (dir === undefined) dir=1;
            table.sort(field,dir);
        //console.log(field);
        });
        // Event: delete row
        $(table.element).on('click', 'div[type=delete]', function() {
        //if (table.deletedata) table.remove( $(this).attr('row') );
        $(table.element).trigger("onDelete",[$(this).attr('uid'),$(this).attr('row')]);
        });
        // Event: inline edit
        $(table.element).on('click', 'div[type=edit]', function() {
            var mode = $(this).attr('mode');
            var row = $(this).attr('row');
            var uid = $(this).attr('uid');

            // Trigger events
            if (mode=='edit') $(table.element).trigger("onEdit");

            var fields_to_update = {};

            for (var field in table.fields)
            {
                var value;
                var type = table.fields[field].type;

                if (mode == 'edit' && typeof table.fieldtypes[type].edit === 'function') {
                    $("[row="+row+"][field="+field+"]").html(table.fieldtypes[type].edit(row,field));
                }

                if (mode == 'save' && typeof table.fieldtypes[type].save === 'function') {
                  value = table.fieldtypes[type].save(row,field);
                  if (table.data[row][field] != value) fields_to_update[field] = value; // only update db if value has changed
                  table.update(row,field,value);    // but update html table because this reverts back from <input>
                }
            }
            if (mode == 'save' && typeof table.fieldtypes[type].save === 'function') {
                value = table.fieldtypes[type].save(row,field);
                if (table.data[row][field] != value) fields_to_update[field] = value;   // only update db if value has changed
                table.update(row,field,value);  // but update html table because this reverts back from <input>
            }

        // Call onSave event only if there are fields to be saved
        if (mode == 'save' && !$.isEmptyObject(fields_to_update)) {
            $(table.element).trigger("onSave",[uid,fields_to_update]);
            if (fields_to_update[table.groupby]!==undefined) table.draw();
        }
        //toggle the Edit / save icons and funtions depending on node state
        if (mode == 'edit') {$(this).attr('mode','save'); $(this).html("<span class='glyphicon glyphicon-floppy-save' title='"+($(this).attr('alt'))+"'></span>");}
        if (mode == 'save') {$(this).attr('mode','edit'); $(this).html("<span class='glyphicon glyphicon-pencil' title='"+($(this).attr('title'))+"'></span>");}
        });
        // Check if events have been defined for field types.
        for (var i in table.fieldtypes) {
            if (typeof table.fieldtypes[i].event === 'function') table.fieldtypes[i].event();
        }
    },
    /*
    Field type space
    */
    'fieldtypes':
    {
        'fixed':
        {
            'draw': function (row,field) { return table.data[row][field]; }
        },
        'text':
        {
            'draw': function (row,field) { return "<span>"+table.data[row][field] +"</span>";},
    //'edit': function (row,field) { return "<input type='text' style='width:100px;' value='"+table.data[row][field]+"' / >" },
    'edit': function (row,field) {
        var html = "";
        html+= "<input type='text'  class='form-control' value='"+table.data[row][field]+"' / >";
        return html;},
        'save': function (row,field) { return $("[row="+row+"][field="+field+"] input").val(); },
    },
    'textlink':
    {
        'draw': function (row,field) { return "<a href='"+table.fields[field].link+table.data[row].id+"' >"+table.data[row][field]+"</a>" ;},
    //'edit': function (row,field) { return "<input type='text' value='"+table.data[row][field]+"' / >" },
    'edit': function (row,field) {
        var html = "";
        html+= "<input type='text' class='form-control' value='"+table.data[row][field]+"' / >";
        return html;},
        'save': function (row,field) { return $("[row="+row+"][field="+field+"] input").val(); },
    },
    'select':
    {
        'draw': function (row,field) { return table.fields[field].options[table.data[row][field]]; },
        'edit': function (row,field) {
            var options = "", option, html= '';
            for (option in table.fields[field].options)
            {
                var selected = ''; if (option==table.data[row][field]) selected = 'selected';
                options += "<option value='"+option+"' "+selected+" >"+table.fields[field].options[option]+"</option>";
            }
            html+= "<select class='form-control'>"+options+"</select>";
            return html;
        },
        'save': function (row,field) { return $("[row="+row+"][field="+field+"] select").val(); },
    },
    'fixedselect':
    {
        'draw': function (row,field) { return table.fields[field].options[table.data[row][field]]; }
    },
    'tblselect':
    {
    /**
    **  This type of field will get its selection in a table provided by a model
    **  The model returned data are an ID and a display text
    **/
    'draw':function(value)    {
        for (var i in list.fields[field].options)
        {
            var fld= list.fields[field].options[i];
            if (fld.id==value){
                return fld.toshow;
            }
        }
        return "";
    },
    'edit':function(field,value)    {
        var options = '';
        for (var i in list.fields[field].options)
        {
            var fld= list.fields[field].options[i];
            var selected = ""; if (fld.id == value) selected = 'selected';
            options += "<option value="+fld.id+" "+selected+">"+fld.toshow+"</option>";
        }
        return "<select class='form-control'>"+options+"</select>";
    },
    'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] select').val();}
    },

    'checkbox':    {
        'draw': function (row,field) {
            var result = '';
            var fld=table.fields[field];
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            //var tooltip = '';if (fld.tooltip) tooltip = fld.tooltip;
            var icon = 'glyphicon-flag';if (fld.icon) icon = fld.icon;
            if (table.data[row][field]) {result="<div class='iconbutton' title='"+tooltip+"'><span class='glyphicon "+icon+"'> </span></div>";}
            return result;
        },
        'edit': function (row,field) { return "<input type='checkbox'  class='form-control'>"; },
        'save': function (row,field) { return $("[row="+row+"][field="+field+"] input").prop('checked');},
    },
    'fixedcheckbox':    {
        'draw': function (row,field) {
            var result = '';
            var fld=table.fields[field];
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            //var tooltip = '';if (fld.tooltip) tooltip = fld.tooltip;
            var icon = 'glyphicon-flag';if (fld.icon) icon = fld.icon;
            if (table.data[row][field]) {result="<div class='iconbutton' title='"+tooltip+"'><span class='glyphicon "+icon+"'> </span></div>";}
            return result;
        },
    },
    'delete':    {
        'draw': function (row,field) {
            var fld=table.fields[field];
            var title= (table.fields['delete-action']);
            return "<div type='delete' class='iconbutton'  title='"+title.tooltip+"' "+" row='"+row+"' uid='"+table.data[row].id+"' ><span class='glyphicon glyphicon-trash' ></span></div>";
        }
    },
    'edit':    {
        'draw': function (row,field) {
            field= (table.fields['edit-action']);
            return "<div type='edit'  class='iconbutton' title='"+field.tooltip+"' action='edit' alt='"+field.alt+"' row='"+row+"' uid='"+table.data[row].id+"' mode='edit'><span class='glyphicon glyphicon-pencil' ></span></div>";
        }
    },
    'save':    {
        'draw': function (row,field) {
            field= (table.fields['save-action']);
            return "<div type='save'  class='iconbutton' title='"+field.tooltip+"' action='save' row='"+row+"' uid='"+table.data[row].id+"' mode='save'><span class='glyphicon glyphicon-floppy-save' ></span></div>";
        }
    },
    //icon is used for a boolean data field
    'icon':    {
        'draw': function(row,field)
        {
            var fld = table.fields[field];
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            var action = (fld.iconaction !== undefined) ? fld.iconaction : '';
            var icon = (fld.icon !== undefined) ? fld.icon : '';
            //var tooltip = '';if (fld.tooltip) tooltip = fld.tooltip;
            //var action =''; if (fld.iconaction) action = fld.iconaction;
            //var icon =''; if (fld.icon) icon = fld.icon;
            var val = 'true';
            if (fld.trueicon) icon = fld.trueicon;
            if (table.data[row][field] ===false){
                icon=fld.falseicon;
                val = 'false';
            }
            //if field is set, then the action is not performed
            return "<div href='#' title='"+tooltip+"' class='iconbutton' action='"+action+"' val='"+val+"' row='"+row+"' uid='"+table.data[row].id+"'><span class='"+icon+"' ></span></div>";
//            return "<div href='#' title='"+tooltip+"' class='iconbutton' action='"+action+"' field='"+field+"' row='"+row+"' uid='"+table.data[row].id+"'><span class='"+icon+"' ></span></div>";
        //if (table.data[row][field] == true) return "<a class='"+fld.trueicon+"' type='input' title='"+fld.tooltip+"'></a>";
        //if (table.data[row][field] ===false) return "<a class='"+fld.falseicon+"' type='input' title='"+fld.tooltip+"'></a>";
        },
        'event': function()
        {
        // Event code for clickable switch state icon's
        $(table.element).on('click', '.iconbutton', function(e) {
            e.preventDefault();
            var row = $(this).attr("row");
            var uid = $(this).attr("uid");
            var field = ''; if ($(this).attr("field")!==undefined) {field=$(this).attr("field");}
            var action = ''; if ($(this).attr("action")!==undefined) {action=$(this).attr("action");}
            var myhref = ''; if ($(this).attr("href")!==undefined) {myhref=$(this).attr("href");}
        // check if Myhref = '#'
        if (myhref==='#'){myhref='';}
        // perform href if defined
        if (myhref!==''){
            window.location.assign (myhref);
            return false;
        }
        //console.log('row= '+row+' - field= '+field+' - uid= '+uid+' - iconaction= '+action+' - href= '+myhref);
        //each standard icon action like view, delete, edit is dne here
        if (field!==''){
        //toggle icon field
        table.data[row][field] = !table.data[row][field];
        var fields = {};
        fields[field] = table.data[row][field];
        $(table.element).trigger("onSave",[table.data[row].id,fields]);
        update();
        }else{
            switch(action) {
                case "delete":
                case "edit":
                    break;
                default:
                    //each unknown action is traznsfered to the module code
                    module_event(e,$(this),row,uid,action);
                    break;
            }
        }
        });
        }
    },
    'updated':
    {
        'draw': function (row,field) { return list_format_updated(table.data[row][field]);}
    },
    'value':
    {
        'draw': function (row,field) { return list_format_value(table.data[row][field]); }
    },
    'processlist':
    {
        'draw': function (row,field) {
            var processlist = table.data[row][field];
            if (!processlist) return "";
            var processPairs = processlist.split(",");
            var out = "";
            for (var z in processPairs)
            {
                var keyvalue = processPairs[z].split(":");
                var key = parseInt(keyvalue[0]);
                var type = "";
                var color = "";
                switch(key)
                {
                    case 1:
                        key = 'log'; type = 2; break;
                    case 2:
                        key = 'x'; type = 0; break;
                    case 3:
                        key = '+'; type = 0; break;
                    case 4:
                        key = 'kwh'; type = 2; break;
                    case 5:
                        key = 'kwhd'; type = 2; break;
                    case 6:
                        key = 'x inp'; type = 1; break;
                    case 7:
                        key = 'ontime'; type = 2; break;
                    case 8:
                        key = 'kwhinckwhd'; type = 2; break;
                    case 9:
                        key = 'kwhkwhd'; type = 2; break;
                    case 10:
                        key = 'update'; type = 2; break;
                    case 11:
                        key = '+ inp'; type = 1; break;
                    case 12:
                        key = '/ inp'; type = 1; break;
                    case 13:
                        key = 'phaseshift'; type =2; break;
                    case 14:
                        key = 'accumulate'; type = 2; break;
                    case 15:
                        key = 'rate'; type = 2; break;
                    case 16:
                        key = 'hist'; type = 2; break;
                    case 17:
                        key = 'average'; type = 2; break;
                    case 18:
                        key = 'flux'; type = 2; break;
                    case 19:
                        key = 'pwrgain'; type = 2; break;
                    case 20:
                        key = 'pulsdiff'; type = 2; break;
                    case 21:
                        key = 'kwhpwr'; type = 2; break;
                    case 22:
                        key = '- inp'; type = 1; break;
                    case 23:
                        key = 'kwhkwhd'; type = 2; break;
                    case 24:
                        key = '> 0'; type = 3; break;
                    case 25:
                        key = '< 0'; type = 3; break;
                    case 26:
                        key = 'unsign'; type = 3; break;
                    case 27:
                        key = 'max'; type = 2; break;
                    case 28:
                        key = 'min'; type = 2; break;
                    case 29:
                        key = '+ feed'; type = 4; break;
                    case 30:
                        key = '- feed'; type = 4; break;
                    case 31:
                        key = 'x feed'; type = 4; break;
                    case 32:
                        key = '/ feed'; type = 4; break;
                    case 33:
                        key = '= 0'; type = 3; break;
                        // from chaveiro/emoncms merge 20150126
                    case 34:
                        key = 'whacc'; type = 2; break;
                    case 35:
                        key = 'MQTT'; type = 5; break;
                    case 36:
                        key = 'null'; type = 3; break;
                    case 37:
                        key = 'ori'; type = 3; break;
                     case 38:
                        key = '!sched 0'; type = 6; break;
                    case 39:
                        key = '!sched N'; type = 6; break;
                    case 40:
                        key = 'sched 0'; type = 6; break;
                    case 41:
                        key = 'sched N'; type = 6; break;
                    case 42:
                        key = '0? skip'; type = 3; break;
                    case 43:
                        key = '!0? skip'; type = 3; break;
                    case 44:
                        key = 'N? skip'; type = 3; break;
                    case 45:
                        key = '!N? skip'; type = 3; break;
                    case 46:
                        key = '>? skip'; type = 0; break;
                    case 47:
                        key = '>=? skip'; type = 0; break;
                    case 48:
                        key = '<? skip'; type = 0; break;
                    case 49:
                        key = '<=? skip'; type = 0; break;
                    case 50:
                        key = '=? skip'; type = 0; break;
                    case 51:
                        key = '!=? skip'; type = 0; break;
                    case 52:
                        key = 'GOTO'; type = 0; break;
                }
                value = keyvalue[1];
                switch(type) {
                    case 0:
                        type = 'value: '; color = 'important';
                        break;
                    case 1:
                        type = 'input: '; color = 'warning';
                        break;
                    case 2:
                        type = 'feed: '; color = 'info';
                        break;
                    case 3:
                        type = ''; color = 'important';
                        value = ''; // Argument type is NONE, we don't mind the value
                        break;
                    case 4:
                        type = 'feed: '; color = 'warning';
                        break;
                }
                if (type == 'feed: ') {
                    out += "<a target='_blank' href='"+path+"vis/auto?feedid="+value+"'<span class='label label-"+color+"' title='"+type+value+"' style='cursor:pointer'>"+key+"</span></a> ";
                    //out += "<a href='"+path+"vis/auto?feedid="+value+"'<span class='label label-"+color+"' title='"+type+value+"' style='cursor:pointer'>"+key+"</span></a> ";
                } else {
                    out += "<span class='label label-"+color+"' title='"+type+value+"' style='cursor:default'>"+key+"</span> ";
                }
            }
            return out;
        }
    },
    'iconlink':
    {
        'draw': function (row,field) {
            var fld=table.fields[field];
            var icon = 'glyphicon glyphicon-eye-open'; if (fld.icon) icon = fld.icon;
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            var colwidth = (fld.colwidth !== undefined) ? fld.colwidth : '';
            //var tooltip = '';if (fld.tooltip) tooltip = fld.tooltip;
            //var colwidth = ''; if (fld.colwidth) colwidth = fld.colwidth;
            return "<div href='"+fld.link+table.data[row].id+"' class='iconbutton' type='iconlink' title='"+tooltip+"'  row='"+row+"' uid='"+table.data[row].id+"'"+colwidth+"><span class='"+icon+"'></span></div>";
        }
    },
    'iconbasic':
    {
        'draw': function(row,field)
        {
            var fld=table.fields[field];
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            var colwidth = (fld.colwidth !== undefined) ? fld.colwidth : '';
            var action = (fld.icon_action !== undefined) ? fld.icon_action : '';
            var icon = (fld.icon !== undefined) ? fld.icon : '';
            //var tooltip = '';if (fld.tooltip) tooltip = fld.tooltip;
            //var action = '';if (fld.icon_action) action = fld.icon_action;
            //var icon = '';if (fld.icon) icon = fld.icon;
            return "<div title='"+tooltip+"' class='iconbutton' action='"+action+"' row='"+row+"' uid='"+table.data[row].id+"'><span class='"+icon+"' ></span></div>";
        }
    },
    'tzone':
    {
        'draw': function(row,field)
        {
            var fld=table.fields[field];
            var value= table.data[row][field];
            var tooltip = (fld.tooltip !== undefined) ? fld.tooltip : '';
            var colwidth = (fld.colwidth !== undefined) ? fld.colwidth : '';
            var action = (fld.icon_action !== undefined) ? fld.icon_action : '';
            //var tooltip = '';if (fld.tooltip) tooltip = fld.tooltip;
            //var action = '';if (fld.icon_action) action = fld.icon_action;
            var sign = value >= 0 ? '+' : '';
            return "UTC "+sign+(value||0)+":00";
        },
        'edit':function(row,field)
        {
            var fld=table.fields[field];
            var value= table.data[row][field];
            var select = $('<select class="form-control"/>'),
            selectedIndex = null,
            sign;
            for (var i=-12; i<=14; i++) {
                var selected = "";
                if (value == i) {
                    selected = 'selected';
                    selectedIndex = i;
                }
                sign = i >= 0 ? '+' : '';
                select.append("<option value="+i+" "+selected+">UTC "+sign+i+":00</option>");
                }
                //If no selected index were set, then default to 0
                if ( selectedIndex === null ) {
                    select.find("option[value='0']").attr('selected', 'selected');
                }
            return select.wrap('<p>').parent().html();  //return HTML-string
        },
        'save': function (row,field) { return $("[row="+row+"][field="+field+"] select").val(); },
        }
    }
};
    // Calculate and color updated time
    function list_format_updated(time)
    {
        time = time * 1000;
        var now = (new Date()).getTime();
        var update = (new Date(time)).getTime();
        var lastupdate = (now-update)/1000;
        var secs = (now-update)/1000;
        var mins = secs/60;
        var hour = secs/3600;
        var updated = secs.toFixed(0)+"s ago";
        if (secs>180) updated = mins.toFixed(0)+" mins ago";
        if (secs>(3600*2)) updated = hour.toFixed(0)+" hours ago";
        if (hour>24) updated = "inactive";
        var color = "rgb(255,125,20)";
        if (secs<25) color = "rgb(50,200,50)";
            else if (secs<60) color = "rgb(240,180,20)";
        return "<span style='color:"+color+";'>"+updated+"</span>";
    }
    // Format value dynamically
    function list_format_value(value)
    {
        if ( value >=10)   value = (1* value ).toFixed(1);
        if ( value >=100)  value = (1* value ).toFixed(0);
        if ( value <10)    value = (1* value ).toFixed(2);
        if ( value <=-10)  value = (1* value ).toFixed(1);
        if ( value <=-100) value = (1* value ).toFixed(0);
        return value ;
    }
