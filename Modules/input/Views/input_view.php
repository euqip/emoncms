<?php
    global $path, $behavior;
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/input.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/emoncms.js"></script>

<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/processlist.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/process_info.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>


<div class="container">

    <div id="apihelphead"><div><a href="api"><?php echo _('Inputs API Help'); ?></a></div></div>
    <div id="localheading">
        <h2><?php echo _('Inputs'); ?>
            <small>
                <a href="api">
                    <span class = "glyphicon glyphicon-info-sign" title = "<?php echo _('Inputs API Help'); ?>"></span>
                </a>
                <a href='#'  id="expandall">
                    <span class = "glyphicon glyphicon-plus-sign" title = '<?php echo _("Expand all")?> '></span>
                </a>
                <a href='#'  id="collapseall">
                    <span class = "glyphicon glyphicon-minus-sign" title = '<?php echo _("Collapse all")?>'></span>
                </a>
                <a href='#'  id="nogroups">
                    <span class = "glyphicon glyphicon glyphicon-list-alt" title = '<?php echo _("Hide groups")?>'></span>
                </a>
            </small>
        </h2>
    </div>

    <div id="table"></div>

    <div id="noinputs" class="alert alert-block">
            <h4 class="alert-heading"><?php echo _('No inputs created'); ?></h4>
            <p><?php echo _('Inputs is the main entry point for your monitoring device. Configure your device to post values here, you may want to follow the <a href="api">Input API helper</a> as a guide for generating your request.'); ?></p>
    </div>

    <div class="modal fade  emoncms-dialog type-primary modal-wide" id="processlist-ui" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title"><span id="inputname"> </span><small> <?php echo _(' -> Process configuration') ?></small></h3>
                </div>
                <div class="modal-body">
                    <p><?php echo _('Input processes are executed sequentially with the result being passed to next processor for further processing.'); ?></p>
                    <table class="table">
                        <tr>
                            <th style='width:5%;'></th>
                            <th style='width:5%;'><?php echo _('Order'); ?></th>
                            <th><?php echo _('Process'); ?></th>
                            <th><?php echo _('Arg'); ?></th>
                            <th></th>
                            <th><?php echo _('Actions'); ?></th>
                        </tr>

                        <tbody id="variableprocesslist"></tbody>

                    </table>
                    <div class="bs-example">
                        <?php echo _("Add process:"); ?>
                        <form class="">
                            <div class="row">
                                <div class="col-xs-3 shortpading">
                                    <select id ="process-select" class="form-control"></select>
                                </div>
                                <div class="col-xs-1 shortpading" id = "type-value" style="display:none;">
                                    <input  id ="value-input" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="col-xs-1 shortpading" id = "type-input" style="display:none;">
                                    <select  id="input-select" class="form-control"></select>
                                </div>
                                <div id="type-feed">
                                    <div class="col-xs-2 shortpading">
                                        <select id = "feed-select" class="form-control">  </select>
                                    </div>
                                    <div class="col-xs-2 shortpading">
                                        <input id ="feed-name" type="text" class="form-control" placeholder="<?php echo _('Feed name...'); ?>" />
                                        <input type="hidden" id="feed-tag"/>
                                    </div>
                                    <div class="col-xs-1 shortpading">
                                        <span><?php echo _("Feed Engine:"); ?></span>
                                    </div>
                                    <div class="col-xs-2 shortpading">
                                        <select id="feed-engine" class="form-control">
                                            <option value=6 ><?php echo _("PHPFIWA : Fixed Interval With Averaging"); ?> </option>
                                            <option value=5 ><?php echo _("PHPFINA : Fixed Interval No Averaging"); ?> </option>
                                            <option value=2 ><?php echo _("PHPTIMESERIES : Variable Interval No Averaging"); ?> </option>
                                        </select>
                                    </div>
                                    <div class="col-xs-1 shortpading">
                                        <select id="feed-interval" class="form-control">
                                            <option value=""> <?php echo _("Select interval"); ?></option>
                                            <option value=5>5 <?php echo _("s"); ?></option>
                                            <option value=10>10 <?php echo _("s"); ?></option>
                                            <option value=15>15 <?php echo _("s"); ?></option>
                                            <option value=20>20 <?php echo _("s"); ?></option>
                                            <option value=30>30 <?php echo _("s"); ?></option>
                                            <option value=60>60 <?php echo _("s"); ?></option>
                                            <option value=120>2 <?php echo _("mins"); ?></option>
                                            <option value=300>5 <?php echo _("mins"); ?></option>
                                            <option value=600>10 <?php echo _("mins"); ?></option>
                                            <option value=900>15 <?php echo _("mins"); ?></option>
                                            <option value=1200>20 <?php echo _("mins"); ?></option>
                                            <option value=1800>30 <?php echo _("mins"); ?></option>
                                            <option value=3600>1 <?php echo _("hour"); ?></option>
                                            <option value=21600>6 <?php echo _('hours'); ?></option>
                                            <option value=43200>12 <?php echo _('hours'); ?></option>
                                            <option selected value=86400> <?php echo _('Daily'); ?></option>
                                            <option value=604800> <?php echo _('Weekly'); ?></option>
                                            <option value=2678400> <?php echo _('Monthly'); ?></option>
                                            <option value=31536000> <?php echo _('Annual'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <button id="process-add" class="btn btn-info col-xs-1 shortpading"><?php echo _('Add'); ?></button>
                            </div>

                        </form>
                    </div>
                    <br />
                    <div id="description" class="row"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Exit') ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>


<div id="myModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo _('Delete Input'); ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo _('Deleting an input will loose its name and configured process list.<br>An new blank input is automatic created by API data post if it does not already exists.'); ?>
        </p>
        <p>
           <?php echo _('Are you sure you want to delete?'); ?>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
        <button id="confirmdelete" class="btn btn-primary"><?php echo _('Delete'); ?></button>
    </div>
</div>
</div>
<script>

    var path = "<?php echo $path; ?>";

    var firstrun = true;
    var assoc_inputs = {};
    var updateinterval ="<?php echo $behavior['inputinterval']; ?>";
    var updateinterval =10000;
    var groupfield= "<?php echo $behavior['inputgroup']; ?>";
    var expanded= <?php echo $behavior['inputlistexpanded']; ?>;


    var moveup = "<?php echo _("Move Up"); ?>";
    var movedown = "<?php echo _("Move Down"); ?>";
    var delprocess = "<?php echo _("Delete"); ?>";
    var createnew = "<?php echo _("CREATE NEW:"); ?>";
    var nodetext = "<?php echo _("Node"); ?>";
    var inputvalue= "<?php echo _("Input value"); ?>";
    var feedvalue= "<?php echo _("Feed Value"); ?>";

    // Extend table library field types
    //for (z in customtablefields) table.fieldtypes[z] = customtablefields[z];

    table.element = "#table";
    table.collapsetext= "<?php echo _("Collapse this Group"); ?>";
    table.expandtext= "<?php echo _("Expand this Group"); ?>";
    table.groupprefix = "<?php echo _("Node: "); ?>";

  table.fields = {
    // Actions
    //'save-action':{'title':'','tooltip':'<?php echo _("Save"); ?>', 'type':"save", 'display':"no"},
    'edit-action':{'title':'','tooltip':'<?php echo _("Edit meta"); ?>','alt':'<?php echo _("Save"); ?>', 'type':"edit", 'display':"yes", 'colwidth':" style='width:30px;'"},
    //'view-action':{'title':'','tooltip':'<?php echo _("Edit Process"); ?>', 'type':"iconlink", 'link':path+"input/process/list.html?inputid=", 'icon':'glyphicon glyphicon-wrench', 'display':"yes", 'colwidth':" style='width:30px;'"},
    'view-action':{'title':'','tooltip':'<?php echo _("Edit Processes"); ?>', 'type':'icon', 'icon':'glyphicon glyphicon-wrench', 'display':"yes", 'colwidth':" style='width:30px;'", 'iconaction':'wrench'},

    'nodeid':{'title':'<?php echo _("Node"); ?>','type':"fixed",'display':"dynamic", 'colwidth':" style='width:50px;'"},
    'orgid':{'title':'<?php echo _("Org"); ?>','type':"fixed",'display':"dynamic", 'colwidth':" style='width:50px;'"},
    'name':{'title':'<?php echo _("name"); ?>','type':"text", 'colwidth':" style='width:100px;'"},
    'description':{'title':'<?php echo _("Description"); ?>','type':"text", 'colwidth':" style='width:200px;'"},
    'processList':{'title':'<?php echo _("Process list"); ?>','type':"processlist",'colwidth':" style='width:250px;'"},
    'time':{'title':'<?php echo _("Last updated"); ?>', 'type':"updated", 'colwidth':" style='width:150px;'"},
    'value':{'title':'<?php echo _("Value"); ?>','type':"value",'colwidth':" style='width:70px;'"},

    'delete-action':{'title':'','tooltip':'<?php echo _("Delete row"); ?>', 'type':"delete", 'display':"yes", 'colwidth':" style='width:30px;'"},
'myown':{'title':"<?php echo _('Mine'); ?>", 'tooltip': "<?php echo _('I am the owner'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-ok", 'falseicon':"glyphicon glyphicon-remove", 'iconaction':"", 'colwidth':" style='width:30px;'"},
'myorg':{'title':"<?php echo _('MyOrg'); ?>", 'tooltip': "<?php echo _('In my organisation'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-ok", 'falseicon':"glyphicon glyphicon-remove", 'iconaction':"", 'colwidth':" style='width:30px;'"},
  }
    //'nodeid':{'title':'<?php echo _("Node:"); ?>','type':"fixed",'colwidth':"", 'display':"yes", 'colwidth':" style='width:50px;'"},
    //'id':{'title':'<?php echo _("id (disable empty string to translate)"); ?>','type':"fixed",'colwidth':"", 'display':"yes"},

    table.groupby = groupfield;

    update();


    function update()
    {
        if (firstrun) {
            table.expand=expanded;
            table.collapse=!expanded;
            table.state=expanded;
        }
        //read table data
        table.data = input.list();

        table.draw();
        $("#collapseall").hide();
        $("#expandall").hide();
        if (table.data.length != 0) {
            $("#noinputs").hide();
            $("#apihelphead").hide();
            $("#localheading").show();
        } else {
            $("#noinputs").show();
            $("#apihelphead").show();
        };
        if(table.state){
            $("#collapseall").show();
        } else {
            $("#expandall").show();
        };


        if (firstrun) {
            firstrun = false;
            load_all();
        }
    }

    var updater = setInterval(update, updateinterval);

/*
    $("#table").bind("onEdit", function(e){
        clearInterval(updater);
        updater = null;
        if (interval > 0) updater = setInterval(func, interval);
    }
*/
    //updaterStart(update, 10000);

    $("#table").bind("onEdit", function(e){
        updaterStart(update, 0);
    });

    $("#table").bind("onSave", function(e,id,fields_to_update){
        input.set(id,fields_to_update);
    });

    $("#table").bind("onResume", function(e){
        updaterStart(update, updateinterval);
    });

    $("#table").bind("onDelete", function(e,id,row){
        $('#myModal').modal('show');
        $('#myModal').attr('scheduleid',id);
        $('#myModal').attr('feedrow',row);
    });

    $("#confirmdelete").click(function()
    {
        var id = $('#myModal').attr('scheduleid');
        var row = $('#myModal').attr('schedulerow');
        input.remove(id);
        table.remove(row);
        update();

        $('#myModal').modal('hide');
    });
//------------------------------------------------------------------------------------------------------------------------------------
// Process list UI js
//------------------------------------------------------------------------------------------------------------------------------------
    function module_event(e,item,row,uid,action){

        switch(action) {
            case "wrench":
                editprocesslist(row,uid);
                break;
            case "edit":
                break;
            default:
                //each unknown action is traznsfered to the module code
        }
    }
    function editprocesslist(row,uid){
        //var row = table.data[$(this).attr('row')];
        var row = table.data[row];
        //console.log("The row to edit:"+row);
        processlist_ui.inputid = row.id;
        var processlist = [];
        if (row.processList!=null && row.processList!="")
        {
            var tmp = row.processList.split(",");
            for (n in tmp)
            {
                var process = tmp[n].split(":");
                processlist.push(process);
            }
        }

        processlist_ui.variableprocesslist = processlist;
        processlist_ui.draw();

        // SET INPUT NAME
        var inputname = "";
        if (processlist_ui.inputlist[processlist_ui.inputid].description!="") {
            inputname = processlist_ui.inputlist[processlist_ui.inputid].description;
            $("#feed-name").val(inputname);
        } else {
            inputname = processlist_ui.inputlist[processlist_ui.inputid].name;
            $("#feed-name").val("node:"+processlist_ui.inputlist[processlist_ui.inputid].nodeid+":"+inputname);
        }

        $("#inputname").html("Node"+processlist_ui.inputlist[processlist_ui.inputid].nodeid+": "+inputname);

        $("#feed-tag").val("Node:"+processlist_ui.inputlist[processlist_ui.inputid].nodeid);

        $("#processlist-ui #process-select").change();  // Force a refresh

        $("#processlist-ui").show();
        window.scrollTo(0,0);

    };

    $("#processlist-ui").on('click', '.close', function() {
        $("#processlist-ui").hide();
    });

function load_all()
{
    for (z in table.data) assoc_inputs[table.data[z].id] = table.data[z];
    console.log(assoc_inputs);
    processlist_ui.inputlist = assoc_inputs;

    // Inputlist
    var out = "";
    for (i in processlist_ui.inputlist) {
      var input = processlist_ui.inputlist[i];
      out += "<option value="+input.id+">"+input.nodeid+":"+input.name+" "+input.description+"</option>";
    }
    $("#input-select").html(out);

    $.ajax({ url: path+"schedule/list.json", dataType: 'json', async: true, success: function(result) {
        var schedules = {};
        for (z in result) schedules[result[z].id] = result[z];

        processlist_ui.schedulelist = schedules;
        var groupname = {0:'Public',1:'Mine'};
        var groups = [];
        //for (z in result) schedules[result[z].id] = result[z];

        for (z in processlist_ui.schedulelist)
        {
            var group = processlist_ui.schedulelist[z].own;
            group = groupname[group];
            if (!groups[group]) groups[group] = []
            processlist_ui.schedulelist[z]['_index'] = z;
            groups[group].push(processlist_ui.schedulelist[z]);
        }

        var out = "";
        for (z in groups)
        {
            out += "<optgroup label='"+z+"'>";
            for (p in groups[z])
            {
                out += "<option value="+groups[z][p]['id']+">"+groups[z][p]['name']+(z!=groupname[1]?" ["+groups[z][p]['id']+"]":"")+"</option>";
            }
            out += "</optgroup>";
        }
        $("#schedule-select").html(out);
    }});

    $.ajax({ url: path+"feed/list.json", dataType: 'json', async: true, success: function(result) {
        var feeds = {};
        for (z in result) { feeds[result[z].id] = result[z]; }
        processlist_ui.feedlist = feeds;
    }});

    $.ajax({
        url: path+"input/getallprocesses.json",
        async: true,
        dataType: 'json',
        success: function(result){
            processlist_ui.processlist = result;
            var processgroups = [];
            var i = 0;
            for (z in processlist_ui.processlist)
            {
                i++;
                var group = processlist_ui.processlist[z][5];
                if (group!="Deleted") {
                    if (!processgroups[group]) processgroups[group] = []
                    processlist_ui.processlist[z]['id'] = z;
                    processgroups[group].push(processlist_ui.processlist[z]);
                }
            }

        var out = "";
        for (z in processgroups)
        {
            out += "<optgroup label='"+z+"'>";
            for (p in processgroups[z])
            {
                out += "<option value="+processgroups[z][p]['id']+">"+processgroups[z][p][0]+"</option>";
            }
            out += "</optgroup>";
        }
        $("#process-select").html(out);

        $("#description").html(process_info[1]);
        processlist_ui.showfeedoptions(1);
    }});

    processlist_ui.events();
}
</script>
