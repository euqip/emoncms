<?php
    global $path;
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/custom-table-fields.js"></script>


<div id="apihelphead"><p class="text-right"><a href="api"><?php echo _('Input API Help'); ?></a></p></div>

<div class="container">
    <div id="localheading"><h2><?php echo _('Feeds'); ?>
        <a href="#" id="refreshfeedsizetop">
          <small><span class = "glyphicon glyphicon-refresh" title = "<?php echo _('Refresh feed size'); ?>"></span></small>
        </a>
      </h2>    
    </div>
    <div id="table"></div>

    <div id="nofeeds" class="alert alert-block hide">      
        <h4 class="alert-heading"><?php echo _('No feeds created'); ?></h4>
        <p><?php echo _('Feeds are where your monitoring data is stored. The recommended route for creating feeds is to start by creating inputs (see the inputs tab). Once you have inputs you can either log them straight to feeds or if you want you can add various levels of input processing to your inputs to create things like daily average data or to calibrate inputs before storage. You may want to follow the link as a guide for generating your request. '); ?></p>
        <p><a href="api"><?php echo _('Feed API helper'); ?></a></p>
        </p>
    </div>
    <button id="refreshfeedsize" class="btn btn-small" ><?php echo _('Refresh feed size')?> <span class="glyphicon glyphicon-refresh" ></span></button>

</div>

<script>


    var path = "<?php echo $path; ?>";

    // Extemd table library field types
    for (z in customtablefields) table.fieldtypes[z] = customtablefields[z];

    table.element = "#table";
  table.fields = {
    'id':{'title':"<?php echo _('Id'); ?>", 'type':"fixed",'colwidth':""},
    'name':{'title':"<?php echo _('Name'); ?>", 'type':"text",'colwidth':""},
    'tag':{'title':"<?php echo _('Tag'); ?>", 'type':"text",'colwidth':""},
    'datatype':{'title':"<?php echo _('Datatype'); ?>", 'type':"select", 'options':['','REALTIME','DAILY','HISTOGRAM']},
    'engine':{'title':"<?php echo _('Engine'); ?>", 'type':"select", 'options':['MYSQL','TIMESTORE','PHPTIMESERIES','GRAPHITE','PHPTIMESTORE','PHPFINA']},
    'public':{'title':"<?php echo _('Public'); ?>", 'tooltip': "<?php echo _('Make feed public'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-globe", 'falseicon':"glyphicon glyphicon-lock"},
    'size':{'title':"<?php echo _('Size'); ?>", 'type':"fixed"},    
    'time':{'title':"<?php echo _('Updated'); ?>", 'type':"updated"},
    'value':{'title':"<?php echo _('Value'); ?>",'type':"value"},

    // Actions
    'edit-action':{'title':'','tooltip':'<?php echo _("Edit"); ?>','alt':'<?php echo _("Save"); ?>', 'type':"edit", 'display':"yes"},
    'delete-action':{'title':'','tooltip':'<?php echo _("Delete"); ?>', 'type':"delete", 'display':"yes"},
    'view-action':{'title':'','tooltip':'<?php echo _("Preview"); ?>', 'type':"iconlink", 'link':path+"vis/auto?feedid=", 'icon':'glyphicon glyphicon-eye-open', 'display':"yes"}
  }


table.groupby = 'tag';
  table.deletedata = false;

  update();

  function update()
  {
    table.data = feed.list();
    for (z in table.data)
    {
      if (table.data[z].size<1024*100) {
        table.data[z].size = (table.data[z].size/1024).toFixed(1)+"kb";
      } else if (table.data[z].size<1024*1024) {
        table.data[z].size = Math.round(table.data[z].size/1024)+"kb";
      } else if (table.data[z].size>=1024*1024) {
        table.data[z].size = Math.round(table.data[z].size/(1024*1024))+"Mb";
      }

    }
    table.draw();
    if (table.data.length != 0) {
      $("#nofeeds").hide();
      $("#apihelphead").hide();      
      $("#localheading").show();
    } else {
      $("#nofeeds").show();
      $("#apihelphead").show(); 
      $("#localheading").hide();
    }
  }

  var updateinterval = 10000;
  var updater = setInterval(update, updateinterval);

  $("#table").bind("onEdit", function(e){
    clearInterval(updater);
  });
    $("#table").bind("onDelete", function(e,id,row){
        clearInterval(updater);
        $('#myModal').modal('show');
        $('#myModal').attr('feedid',id);
        $('#myModal').attr('feedrow',row);
    });

  $("#table").bind("onSave", function(e,id,fields_to_update){
    feed.set(id,fields_to_update); 
    updater = setInterval(update, updateinterval);
  });

  $("#table").bind("onDelete", function(e,id){
    feed.remove(id); 
    update();
  });
  $("#refreshfeedsize").click(function(){
    $.ajax({ url: path+"feed/updatesize.json", success: function(data){update();} });
  });
  $("#refreshfeedsizetop").click(function(){
    $.ajax({ url: path+"feed/updatesize.json", success: function(data){update();} });
  });

  $("#table").bind("onDelete", function(e,id,row){
    BootstrapDialog.show({
        message: "<?php echo _('WARNING deleting a feed is permanent'); ?>",
        title:"<?php echo _('Are you sure you want to delete this feed?'); ?>",
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
                feed.remove(id); 
                table.remove(row);
                update();
                dialog.close();
            }
        }]
        /*
        $('#myModal').modal('hide');
        updater = setInterval(update, 5000);
    });
        */

    });
  });
</script>
