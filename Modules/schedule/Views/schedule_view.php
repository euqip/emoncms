<?php
    global $path;
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/schedule/Views/schedule.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>


<style>
#table input[type="text"] {
         width: 88%;
}
</style>

<div class="container">
    <div id="localheading">
        <h2><?php echo _('Schedules'); ?>
           <small>
                <a href="api">
                    <span class = "glyphicon glyphicon-info-sign" title = "<?php echo _('Schedule API Help'); ?>"></span>
                </a>
                <a href='#'  id="addnewschedule">
                    <span class = "glyphicon glyphicon-plus-sign" title = '<?php echo _("New schedule")?> '></span>
                </a>
            </small>
        </h2>
    </div>
    <div id="table"></div>

    <div id="noschedules" class="alert alert-block hide">
            <h4 class="alert-heading"><?php echo _('No schedules created'); ?></h4>
            <p><?php echo _('No schedules defined. Please add a new schedule. <a href="api">Schedule helper</a> as a guide for generating your request.'); ?></p>
    </div>
</div>


<div class="modal fade emoncms-dialog type-danger" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">"<?php echo _('Are you sure you want to delete this schedule?'); ?>"</h4>
            </div>
            <div class="modal-body">
                <div class="type-danger ">
                    <div> <?php echo _('WARNING deleting a schedule is permanent'); ?> </div>
                    <div> <?php echo _('Delete definitively schedule : '); ?> <span id="schedulename"></span> </div>
               </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
                <button class="btn" id="confirmdelete"><span class="emoncms-dialog-button-icon glyphicon glyphicon-trash"></span><?php echo _('Delete'); ?></button>
            </div>
        </div>
    </div>
</div>


<script>

    var path = "<?php echo $path; ?>";
    var firstrun = true;
    var updateinterval =10000;

    // Extend table library field types
    //for (z in customtablefields) table.fieldtypes[z] = customtablefields[z];

    table.element = "#table";

    table.fields = {
        'edit-action':{'title':'','tooltip':'<?php echo _("Edit"); ?>', 'type':"edit",'display':"yes", 'colwidth':" style='width:30px;'"},
        'id':{'type':"fixed",'tooltip':'<?php echo _("Id"); ?>', 'colwidth':" style='width:40px;'"},
        'name':{'title':'<?php echo _("Name"); ?>','tooltip':'<?php echo _("Your prefered human readable name"); ?>','type':"text", 'colwidth':" style='width:100px;'"},
        'expression':{'title':'<?php echo _('Expression'); ?>','tooltip':'<?php echo _("Scheduling rule"); ?>','type':"text", 'colwidth':" style='width:150px;'"},
        'description':{'title':'<?php echo _('Description'); ?>','tooltip':'<?php echo _("Free schedule description text"); ?>','type':"text", 'colwidth':" style='width:200px;'"},
        'public':{'title':"<?php echo _('Public'); ?>",'tooltip':'<?php echo _("Toggle Public - Private"); ?>', 'type':"icon", 'trueicon':"glyphicon glyphicon-globe", 'falseicon':"glyphicon glyphicon-lock",'display':"yes", 'colwidth':" style='width:30px;'"},

        // Actions
        'delete-action':{'title':'','tooltip':'<?php echo _("Delete line"); ?>', 'type':"delete",'display':"yes", 'colwidth':" style='width:30px;'"},
        'view-action':{'title':'', 'type':"iconbasic", 'icon':'glyphicon glyphicon-wrench','display':"no", 'colwidth':" style='width:30px;'"},
        'test-action':{'title':'', 'type':"iconbasic", 'icon':'glyphicon glyphicon-eye-open','display':"no", 'colwidth':" style='width:30px;'"}
    }

    //table.groupby = 'userid';
    table.deletedata = false;

    update();


    function module_event(evt, elt, row, uid, action){
        //console.log('feed module row= '+row+' - field= '+field+' - uid= '+uid+' - iconaction= '+action);
        switch(action)
        {
            case "export-action":
                $("#SelectedExportFeed").html(table.data[row].tag+": "+table.data[row].name);
                $("#export").attr('feedid',table.data[row].id);
                if ($("#export-timezone").val()=="") {
                    var u = user.get();
                    $("#export-timezone").val(parseInt(u.timezone));
                    $("#export-timezone-list").val($("#export-timezone").val());
                    $("#export-interval").val($("#export-interval-list").val());
                }

                $('#ExportModal').modal('show');
                break;

            default:
            //each unknown action is traznsfered to the module code
            //module_event(e,$(this),row,uid,action);
          }
    }



    function update()
    {
        $.ajax({ url: path+"schedule/list.json", dataType: 'json', async: true, success: function(data) {

            table.data = data;
            for (d in data) {
                if (data[d]['own'] != true){
                    data[d]['#READ_ONLY#'] = true;  // if the data field #READ_ONLY# is true, the fields type: edit, delete will be ommited from the table row and icon type will not update when clicked.
                }
            }

            table.draw();
            if (table.data.length != 0) {
                $("#noschedules").hide();
                $("#apihelphead").show();
                $("#localheading").show();
            } else {
                $("#noschedules").show();
                $("#localheading").show();
                $("#apihelphead").hide();
            }
        }});
    }

    var updater = setInterval(update, updateinterval);

    function updaterStart(func, interval)
    {
        clearInterval(updater);
        updater = null;
        if (interval > 0) updater = setInterval(func, interval);
    }
    updaterStart(update, updateinterval);

    $("#table").bind("onEdit", function(e){
        updaterStart(update, 0);
    });

    $("#table").bind("onSave", function(e,id,fields_to_update){
        schedule.set(id,fields_to_update);
    });

    $("#table").bind("onResume", function(e){
        updaterStart(update, updateinterval);
    });

    $("#table").bind("onDelete", function(e,id,row){
        $('#myModal').modal('show');
        $('#myModal').attr('scheduleid',id);
        $('#myModal').attr('schedulerow',row);
        // show schedule name in modal box
        $('#schedulename').html (table.data[row].name);
    });

    $("#confirmdelete").click(function()
    {
        var id = $('#myModal').attr('scheduleid');
        var row = $('#myModal').attr('schedulerow');
        schedule.remove(id);
        table.remove(row);
        update();

        $('#myModal').modal('hide');
    });

    $("#addnewschedule").click(function(){
        $.ajax({ url: path+"schedule/create.json", success: function(data){update();} });
    });


//------------------------------------------------------------------------------------------------------------------------------------
// Expression helper UI js
//------------------------------------------------------------------------------------------------------------------------------------

    $("#table").on('click', '.glyphicon-wrench', function() {

        var i = table.data[$(this).attr('row')];
        console.log(i);
        alert("TBD: Javascript expression builder " + i['id']);

    });

    $("#table").on('click', '.glyphicon-eye-open', function() {

        var i = table.data[$(this).attr('row')];
        console.log(i);
        alert("Test expression returned: " + schedule.test(i['expression']));

    });

</script>
