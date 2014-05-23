<?php
global $path, $actions;
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/dashboard/dashboard.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>

<div class="feedback pull-right fade">
    <span id ="msgfeedback"></span>
</div>

<div class="container">
    <div id="localheading">
        <h2><?php echo _('Dashboard'); ?>
            <a href="#" class="adddashboard">
                <small><span class = "glyphicon glyphicon-plus-sign" title = '<?php echo _("Add new dashbord")?>'></span></small>
            </a>
        </h2>
    </div>

    <div id="table"></div>

    <div id="nodashboards" class="alert alert-block">
        <h4 class="alert-heading"><?php echo _('No dashboards created'); ?></h4>
        <p><?php echo _('Maybe you would like to add your first dashboard using the button') ?>
            <button type="button" id ="adddashboard" class="btn btn-default btn-lg">
                <span> <?php echo _("Add new dashbord")?></span>
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>

    <div class="modal fade emoncms-dialog type-danger" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo _('Are you sure you want to delete this dashboard?'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="type-danger ">
                        <div> <?php echo _('WARNING deleting a dashboard is permanent'); ?> </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
                    <button class="btn" id="confirmdelete"><span class="emoncms-dialog-button-icon glyphicon glyphicon-trash"></span><?php echo _('Delete permanently'); ?></button>
                </div>
            </div>
        </div>
    </div>



    <script>

    var path = "<?php echo $path; ?>";

// Extemd table library field types
//for (z in customtablefields) table.fieldtypes[z] = customtablefields[z];

table.element = "#table";

table.fields = {
    'delete-action':{'title':'','tooltip':"<?php echo _('Suppress dashboard'); ?>", 'type':"delete", 'display':"<?php echo $actions['delete']; ?>", 'colwidth':" style='width:30px;'"},
    'edit-action':{'title':'','tooltip':"<?php echo _('Edit dashboard attributes'); ?>",'alt':'<?php echo _("Save"); ?>', 'type':"edit", 'display':"<?php echo $actions['edit']; ?>", 'colwidth':" style='width:30px;'"},
    'clone-action':{'title':'','tooltip':"<?php echo _('Duplicate'); ?>", 'type':"iconlink", 'icon':"glyphicon glyphicon-random", 'link':path+"dashboard/clone?id=",  'display':"<?php echo $actions['clone']; ?>", 'colwidth':" style='width:30px;'"},
    'mine':{'title':'','tooltip':"<?php echo _('Mine'); ?>", 'type':"fixedcheckbox", 'icon':"glyphicon glyphicon-flag", 'link':path+"dashboard/clone?id=", 'display':"<?php echo $actions['mine']; ?>", 'colwidth':" style='width:30px;'"},
    'id':{'title':"<?php echo _('Id'); ?>", 'type':"fixed",'tooltip':"<?php echo _('Dashboard id'); ?>", 'display':"yes", 'colwidth':" style='width:30px;'"},
    'name':{'title':"<?php echo _('Name'); ?>", 'type':"text",'tooltip':"<?php echo _('Dashboard name'); ?>", 'display':"yes", 'colwidth':" style='width:200px;'"},
    'alias':{'title':"<?php echo _('Alias'); ?>", 'type':"text",'tooltip':"<?php echo _('Dashboard Alias'); ?>", 'display':"yes", 'colwidth':" style='width:200px;'"},
// 'description':{'title':"<?php echo _('Description'); ?>", 'type':"text"},
'main':{'title':"<?php echo _('Main'); ?>",'tooltip':"<?php echo _('set as main'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-star", 'falseicon':"glyphicon glyphicon-star-empty", 'display':"yes", 'colwidth':" style='width:30px;'"},
'menu':{'title':"<?php echo _('Menu'); ?>",'tooltip':"<?php echo _('Show it in sub-menu for quick access'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-thumbs-up", 'falseicon':"glyphicon glyphicon-thumbs-down", 'display':"yes", 'colwidth':" style='width:30px;'"},
'public':{'title':"<?php echo _('Public'); ?>", 'tooltip': "<?php echo _('make dashbord public'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-globe", 'falseicon':"glyphicon glyphicon-lock", 'iconaction':"public",  'display':"<?php echo $actions['public']; ?>", 'colwidth':" style='width:30px;'"},
'published':{'title':"<?php echo _('Publish'); ?>",'tooltip':"<?php echo _('Publish dashbord, make it usable by other users within organisation'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-ok", 'falseicon':"glyphicon glyphicon-remove",  'display':"<?php echo $actions['published']; ?>", 'colwidth':" style='width:30px;'"},

'draw-action':{'title':'','tooltip':"<?php echo _('Design this dashboard'); ?>", 'type':"iconlink", 'icon':"glyphicon glyphicon-edit", 'link':path+"dashboard/edit?id=",  'display':"<?php echo $actions['draw']; ?>", 'colwidth':" style='width:30px;'"},
'view-action':{'title':'','tooltip':"<?php echo _('Show the result dashboard'); ?>", 'type':"iconlink", 'link':path+"dashboard/view?id=",  'display':"<?php echo $actions['view']; ?>", 'colwidth':" style='width:30px;'"},
// Actions
//'clone-action':{'title':'','tooltip':"<?php echo _('Duplicate'); ?>", 'type':"iconlink", 'icon':"glyphicon glyphicon-random", 'link':path+"dashboard/clone.json?id="},

}

table.deletedata = false;

update();

function update() {
    table.data = dashboard.list();
    table.draw();
    if (table.data.length != 0) {
        $("#nodashboards").hide();
        $("#localheading").show();
    } else {
        $("#nodashboards").show();
        $("#localheading").hide();
    };
}

$("#table").bind("onEdit", function(e){});

$("#table").bind("onSave", function(e,id,fields_to_update){
    dashboard.set(id,fields_to_update);
});

$("#table").bind("onDelete", function(e,id,row){
    $('#myModal').modal('show');
    $('#myModal').attr('dashboardid',id);
    $('#myModal').attr('fdashboardrow',row);
    $('#myModal').modal('show')
})


$('#confirmdelete').click(function(e){
    var id = $('#myModal').attr('dashboardid');
    var row = $('#myModal').attr('dashboardrow');
    dashboard.remove(id);
    table.remove(row);
    update();

    $('#myModal').modal('hide');
})

$(".adddashboard").click(function(){
    $.ajax({type: 'POST',
        url:'<?php echo $path; ?>dashboard/create.json',
        success: function(){update();}
    });
});
function duplcate(id){
    $.ajax({type : 'POST',
        url :  path + 'dashboard/clone.json?id='+id,
        data : '',
        dataType : 'json',
        success : location.reload()});
}
$(function () {
    $("table a, table i, img").tooltip({
        placement : 'top'
    });
});

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
update();
}

</script>

