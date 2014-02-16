<?php
    global $path;
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/dashboard/dashboard.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/custom-table-fields.js"></script>

<div class="container">
    <div id="localheading">
      <h2><?php echo _('Dashboard'); ?>
        <a href="#" id="adddashboardtop">
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


<script>

  var path = "<?php echo $path; ?>";

  // Extemd table library field types
  for (z in customtablefields) table.fieldtypes[z] = customtablefields[z];

  table.element = "#table";

  table.fields = {
    'id':{'title':"<?php echo _('Id'); ?>", 'type':"fixed"},
    'name':{'title':"<?php echo _('Name'); ?>", 'type':"text"},
    'alias':{'title':"<?php echo _('Alias'); ?>", 'type':"text"},
   // 'description':{'title':"<?php echo _('Description'); ?>", 'type':"text"},
    'main':{'title':"<?php echo _('Main'); ?>", 'tooltip':"<?php echo _('set as main'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-star", 'falseicon':"glyphicon glyphicon-star-empty"},
    'public':{'title':"<?php echo _('Public'); ?>", 'tooltip':"<?php echo _('make dashbord public'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-globe", 'falseicon':"glyphicon glyphicon-lock"},
    'published':{'title':"<?php echo _('Published'); ?>",  'tooltip':"<?php echo _('publish dashbord'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-floppy-save", 'falseicon':"glyphicon glyphicon-remove"},

    // Actions
    //'clone-action':{'title':'','tooltip':"<?php echo _('Duplicate'); ?>", 'type':"iconlink", 'icon':"glyphicon glyphicon-random", 'link':path+"dashboard/clone.json?id="},
    'clone-action':{'title':'','tooltip':"<?php echo _('Duplicate'); ?>", 'type':"iconlink", 'icon':"glyphicon glyphicon-random", 'link':path+"dashboard/clone?id="},
    'edit-action':{'title':'','tooltip':"<?php echo _('Edit'); ?>", 'type':"edit"},
    'delete-action':{'title':'','tooltip':"<?php echo _('Delete'); ?>", 'type':"delete"},
    'draw-action':{'title':'','tooltip':"<?php echo _('Design'); ?>", 'type':"iconlink", 'icon':"glyphicon glyphicon-edit", 'link':path+"dashboard/edit?id="},
    'view-action':{'title':'','tooltip':"<?php echo _('Show'); ?>", 'type':"iconlink", 'link':path+"dashboard/view?id="}

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
    BootstrapDialog.show({
        message: "<?php echo _('WARNING deleting a dashboard is permanent'); ?>",
        title:"<?php echo _('Are you sure you want to delete this dashboard?'); ?>",
        type:BootstrapDialog.TYPE_DANGER,
        closable: false,
        buttons: [{
            label: "<?php echo _('Cancel'); ?>",
            action: function(dialog){
                dialog.close();
              }
        }, {
            icon: 'glyphicon glyphicon-trash',
            label: "<?php echo _('Delete permanently'); ?>",
            cssClass: 'btn-danger',
            action: function(dialog){
                dashboard.remove(id); 
                table.remove(row);
                update();
                dialog.close();
            }
        }]

    });
  });

  $("#adddashboardtop").click(function(){
    $.ajax({type: 'POST',
      url:'<?php echo $path; ?>dashboard/create.json',
      success: function(){update();}
       });
  });
  $("#adddashboard").click(function(){
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
</script>
<script>
$(function () {
    $("table a, table i, img").tooltip({
        placement : 'top'
    });
});
</script>

