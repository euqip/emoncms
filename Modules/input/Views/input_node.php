<?php
    global $path;
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/input.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>
<div class="container">
    <div id="localheading"><h2><?php echo _('Inputs'); ?>
      <a href="api"><small><span class = "glyphicon glyphicon-info-sign" title = "<?php echo _('Input API Help'); ?>"></span></small></a>
      </h2>    
    </div>
    <div id="table"></div>

    <div id="noinputs" class="alert alert-block hide">
            <h4 class="alert-heading"><?php echo _('No inputs created'); ?></h4>
            <p><?php echo _('Inputs is the main entry point for your monitoring device. Configure your device to post values here, you may want to follow the <a href="api">Input API helper</a> as a guide for generating your request.'); ?></p>
    </div>
</div>

<script>
  var path = "<?php echo $path; ?>";

  // Extend table library field types
  //for (z in customtablefields) table.fieldtypes[z] = customtablefields[z];

  table.element = "#table";

  table.fields = {
    // Actions
    //'save-action':{'title':'','tooltip':'<?php echo _("Save"); ?>', 'type':"save", 'display':"no"},
    'delete-action':{'title':'','tooltip':'<?php echo _("Delete row"); ?>', 'type':"delete", 'display':"yes", 'colwidth':" style='width:30px;'"},
    'edit-action':{'title':'','tooltip':'<?php echo _("Edit"); ?>','alt':'<?php echo _("Save"); ?>', 'type':"edit", 'display':"yes", 'colwidth':" style='width:30px;'"},

    'nodeid':{'title':'<?php echo _("Node:"); ?>','type':"fixed",'colwidth':"", 'display':"no"},
    'name':{'title':'<?php echo _("name"); ?>','type':"text", 'colwidth':" style='width:100px;'"},
    'description':{'title':'<?php echo _("Description"); ?>','type':"text", 'colwidth':" style='width:200px;'"},
    'processList':{'title':'<?php echo _("Process list"); ?>','type':"processlist",'colwidth':" style='width:250px;'"},
    'time':{'title':'<?php echo _("Last updated"); ?>', 'type':"updated", 'colwidth':" style='width:150px;'"},
    'value':{'title':'<?php echo _("Value"); ?>','type':"value",'colwidth':" style='width:70px;'"},

    'view-action':{'title':'','tooltip':'<?php echo _("Edit Process"); ?>', 'type':"iconlink", 'link':path+"input/process/list.html?inputid=", 'icon':'glyphicon glyphicon-wrench', 'display':"yes", 'colwidth':" style='width:30px;'"},
  }

  table.groupprefix = "Node ";
  table.groupby = 'nodeid';

  update();

  function update()
  {
    table.data = input.list();
    table.draw();
    if (table.data.length != 0) {
      $("#noinputs").hide();
      $("#apihelphead").hide();      
      $("#localheading").show();
    } else {
      $("#noinputs").show();
      $("#apihelphead").show(); 
      $("#localheading").hide();
    }
  }

  var updateinterval = 10000;
  var updater = setInterval(update, updateinterval);

  $("#table").bind("onSave", function(e,id,fields_to_update){
    input.set(id,fields_to_update); 
    updater = setInterval(update, updateinterval);
  });
  update();

  $("#table").bind("onEdit", function(e){
      clearInterval(updater);
  });

  $("#table").bind("onDelete", function(e,id){
      input.remove(id);
      update();
  });

      function module_event(evt, elt, row, uid, action){
        console.log('inpur module row= '+row+' - field= '+field+' - uid= '+uid+' - iconaction= '+action);                   
      }



</script>
