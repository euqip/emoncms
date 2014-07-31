<?php
global $path, $session;
$modulename = _('Nodes');
?>
<script type="text/javascript" src="<?php echo $path; ?>Modules/node/node.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/node/processlist.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/input.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/process_info.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>


<div class="container">
    <div id="localheading">
      <h2><?php echo _($modulename); ?>
        <a href="api"><small><span class = "glyphicon glyphicon-info-sign" title = "<?php echo _($modulename.' API Help'); ?>"></span></small></a>
    </h2>
    <p><?php echo _('This is an alternative entry point to "INPUTS" designed around providing flexible decoding of RF12b struct based data packets.') ?></p>
</div>

<table class="table">
    <tbody id="nodes"></tbody>
</table>

<div id="nofeeds" class="alert alert-block hide">
    <h4 class="alert-heading"><?php echo _('No feeds created'); ?></h4>
    <p><?php echo _('Feeds are where your monitoring data is stored. The recommended route for creating feeds is to start by creating inputs (see the inputs tab). Once you have inputs you can either log them straight to feeds or if you want you can add various levels of input processing to your inputs to create things like daily average data or to calibrate inputs before storage. You may want to follow the link as a guide for generating your request.'); ?><a href="api"><?php echo _('Feed API helper'); ?></a></p>
</div>
</div>


<div class="modal fade emoncms-dialog type-primary" id="myModal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"><?php echo _('Node').' ' ?><span id="myModal1-variablename"></span></b> config:</h4>
          </div>
          <div class="modal-body">
            <div>
                <span><?php echo _('Selected feed:') ?> </span><b><span id="SelectedExportFeed"></span></b></p>
                <p><?php echo _('Select the dates range interval that you wish to export: (From - To)') ?> </p>
            </div>
            <div id="modalhtml" class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group"  id="export-start-div">
                            <div class="input-group date form_datetime" title="<?php echo _('Start Date'); ?>" data-date="" data-date-format="dd MM yyyy hh:ii:ss" data-link-field="export-start" data-link-format="yyyy/mm/dd hh:ii:ss">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                            <input type="hidden" id="export-start" value="" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" id="export-end-div">
                            <div class="input-group date form_datetime" title="<?php echo _('End Date'); ?>" data-date="" data-date-format="dd MM yyyy hh:ii:ss" data-link-field="export-end" data-link-format="yyyy/mm/dd hh:ii:ss">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <input class="form-control" size="16" type="text" value="" readonly>
                            </div>
                            <input type="hidden" id="export-end" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p><?php echo _('Select the time interval with time reference that you wish to export:') ?> </p>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group"  title="<?php echo _('Select samples time interval'); ?>" data-link-field="dtp_input3">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <select id="export-interval-list" class="form-control" placeholder="Select interval" >
                                    <option value=5>5<?php echo _('s'); ?></option>
                                    <option value=10>10<?php echo _('s'); ?></option>
                                    <option value=30>30<?php echo _('s'); ?></option>
                                    <option value=60>1<?php echo _('min'); ?></option>
                                    <option value=300>5 <?php echo _('mins'); ?></option>
                                    <option value=600>10 <?php echo _('mins'); ?></option>
                                    <option value=900>15 <?php echo _('mins'); ?></option>
                                    <option value=1800>30 <?php echo _('mins'); ?></option>
                                    <option value=3600>1 <?php echo _('hour'); ?></option>
                                    <option value=21600>6 <?php echo _('hours'); ?></option>
                                    <option value=43200>12 <?php echo _('hours'); ?></option>
                                    <option selected value=86400><?php echo _('Daily'); ?></option>
                                    <option value=604800><?php echo _('Weekly'); ?></option>
                                    <option value=2678400><?php echo _('Monthly'); ?></option>
                                    <option value=31536000><?php echo _('Annual'); ?></option>
                                </select>
                            </div>
                            <input type="hidden" id="export-interval" value="" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group"  title="<?php echo _('Select Time zone (for day export)'); ?>" data-link-field="dtp_input4">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <select id="export-timezone-list" class="form-control" >
                                    <?php
                                    for ($tt=-12; $tt<=12; $tt++)
                                    {
                                        $tt1= substr("0".abs($tt),-2);
                                        $plus= ($tt<0)?'-':'+';
                                            //need to select the user timezone!!! for better ergonomy, not present in $session
                                            //$selected=($tt==0)? 'selected':'';
                                        echo "<option ".$selected." value=".$tt."> UTC ".$plus.$tt1.":00 </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" id="export-timezone" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <h5><?php echo _('Feed intervals note:') ?></h5>
                        <p>
                            <?php echo _('if the selected interval is shorter than the feed interval the feed interval will be used instead.')?>
                            <?php echo _('Averages are only returned for feed engines with built in averaging.')?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <span class="pull-left"><?php echo _('Estimated download size '); ?> : <span id="downloadsize">0</span>kB</span>
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
            <button class="btn" id="export"><span class="emoncms-dialog-button-icon glyphicon glyphicon-download"></span><?php echo _('Export'); ?></button>
        </div>
    </div>
</div>
</div>

<div class='input-prepend input-append'>
    <label class='add-on'><?php echo _('Name').':' ?></label>
    <input style='width:150px' class='variable-name-edit' type='text'/ value='"+currentname+"'>
    <span class='add-on'>Datatype:</span>
    <select class='variable-datatype-selector' style='width:130px'><option value=1>Integer</option><option value=2>Unsigned long</option></select>
    <span class='add-on'>Scale:</span>
    <input class='variable-scale-edit' style='width:60px' type='text' value='"+currentscale+"' / >
    <span class='add-on'>Units:</span>
    <select class='variable-units-selector' style='width:60px;'><option value=''></option><option>W</option><option>kW</option><option>Wh</option><option>kWh</option><option>°C</option><option>V</option><option>mV</option><option>A</option><option>mA</option></select>
    <button class='btn save-variable'>Save</button>
</div>";


<script>

  var path = "<?php echo $path; ?>";

  processlist_ui.enable_mysql_all = <?php echo $enable_mysql_all; ?>;

  var nodes = node.getall();

  var decoders = {

    nodecoder: {
      name: 'No decoder',
      variables:[]
    },

    lowpowertemperaturenode: {
      name: 'Low power temperature node',
      updateinterval: 60,
      variables: [
        {name: 'Temperature', type: 1, scale: 0.01, units: '°C' },
        {name: 'Battery Voltage', type: 1, scale:0.001, units: 'V'}
      ]
    },

    emonTxV3_RFM12B_DiscreteSampling: {
      name: 'EmonTx V3 RFM12B DiscreteSampling',
      updateinterval: 10,
      variables: [
        {name: 'Power 1', type: 1, units: 'W'},
        {name: 'Power 2', type: 1, units: 'W'},
        {name: 'Power 3', type: 1, units: 'W'},
        {name: 'Power 4', type: 1, units: 'W'},
        {name: 'Vrms', type: 1, scale: 0.01, units: 'V'}, 
        {name: 'temp', type: 1, scale: 0.1, units: '°C'}
      ]
    },
};

    emonTxV3_continuous_whtotals: {
      name: 'EmonTx V3 (Continuous sampling with Wh totals)',
      updateinterval: 10,
      variables: [
        {name: 'Message Number', type: 2 },
        {name: 'Power CT1', type: 1, units: 'W'},
        {name: 'Power CT2', type: 1, units: 'W'},
        {name: 'Power CT3', type: 1, units: 'W'},
        {name: 'Power CT4', type: 1, units: 'W'},
        {name: 'Wh CT1', type: 2, units: 'Wh'}, 
        {name: 'Wh CT2', type: 2, units: 'Wh'}, 
        {name: 'Wh CT3', type: 2, units: 'Wh'}, 
        {name: 'Wh CT4', type: 2, units: 'Wh'}
      ]
    },

    emonTH_DHT22_DS18B20: {
      name: 'EmonTH DHT22 DS18B20',
      updateinterval: 60,
      variables: [
        {name: 'Internal temperature', type: 1, scale: 0.1, units: '°C'},
        {name: 'External temperature', type: 1, scale: 0.1, units: '°C'},
        {name: 'Humidity', type: 1, scale: 0.1, units: '%'},
        {name: 'Battery Voltage', type: 1, scale: 0.1, units: 'V'},
      ]
    },

    custom: {
      name: 'Custom decoder',
      variables:[]
    },
  };

 redraw();

 var variable_edit_mode = false;

 var interval = setInterval(update,5000);

 function update()
 {
   nodes = node.getall();
   redraw();
}
function redraw()
{
    var out = "";
    for (z in nodes)
    {
      var nodename = '(Click to select a decoder)';
      if (nodes[z].decoder!=undefined && nodes[z].decoder.name!=undefined) nodename = nodes[z].decoder.name;

      out += "<tr style='background-color:#eee' node="+z+"><td><b>Node "+z+"</b></td><td><span class='select-decoder' node="+z+" mode='namedisplay'><b>"+nodename+"</b></span><span node="+z+" class='customdecoder'></span></td><td>"+list_format_updated(nodes[z].time)+"</td><td></td></tr>";

      var bytes = nodes[z].data.split(',');
      var pos = 0;

      if (nodes[z].decoder!=undefined && nodes[z].decoder.variables.length>0)
      {
        for (i in nodes[z].decoder.variables)
        {
          var variable = nodes[z].decoder.variables[i];

          out += "<tr style='padding:0px' node="+z+" variable="+i+"><td></td><td class='variable-name'>"+variable.name+" <span class='edit-variable glyphicon glyphicon-pencil' style='display:none'></span></td>";

          if (variable.type==0)
          {
            var value = parseInt(bytes[pos]);
            pos += 1;
        }

        if (variable.type==1)
        {
            var value = parseInt(bytes[pos]) + parseInt(bytes[pos+1])*256;
            if (value>32768) value += -65536;
            pos += 2;
        }

        if (variable.type==2)
        {
            var value = parseInt(bytes[pos]) + parseInt(bytes[pos+1])*Math.pow(2,1*8) + parseInt(bytes[pos+2])*Math.pow(2,2*8) + parseInt(bytes[pos+3])*Math.pow(2,3*8);
            //if (value>32768) value += -65536;
            pos += 4;
        }
        out += "<td>";

        if (variable.scale!=undefined) {
            value *= parseFloat(variable.scale);
            if (variable.scale==1.0) out += value.toFixed(0);
            else if (variable.scale==0.1) out += value.toFixed(1);
            else if (variable.scale==0.01) out += value.toFixed(2);
            else if (variable.scale==0.001) out += value.toFixed(3);
            else out += value;
        } else {
            out += value;
        }

        if (variable.units!=undefined) {

          if (variable.units=='u00b0C') variable.units = "°C";
          out += " "+variable.units;
      }

      var labelcolor = ""; if (variable.feedid) labelcolor = 'label-info';

      var updateinterval = nodes[z].decoder.updateinterval;

      var processliststr = ""; if (variable.processlist!=undefined) processliststr = processlist_ui.drawinline(variable.processlist);
      out += "</td><td style='text-align:right'>"+processliststr+"<span class='label "+labelcolor+" record' style='cursor:pointer' >Config <span class='glyphicon glyphicon-wrench glyphicon glyphicon-white'></span></span></td></tr>";

  }
}

if (nodes[z].decoder==undefined || nodes[z].decoder.variables.length==0)
{
    out += "<tr><td></td><td><i style='color:#aaa'>Raw byte data: "+nodes[z].data+"</i>";
    out += "</td><td></td></tr>";
}

}

if (out=="") out = "<div class='alert alert-info' style='padding:40px; text-align:center'><h3>No nodes detected yet</h3><p>To use this module send a byte value csv string and the node id to: "+path+"/node/set.json?nodeid=10&data=20,20,20,20</p></div>";

$("#nodes").html(out);
}

  // Show edit
  $("#nodes").on("mouseover",'tr',function() {
    $(".glyphicon-pencil").hide();
    //if (!variable_edit_mode) $(this).find("td[class=variable-name] > i").show();
    if (!variable_edit_mode) $(this).find("td[class=variable-name] > span").show();
});

  // Draw in line editing for a variable when the pencil icon is clicked.
  $("#nodes").on("click", ".edit-variable", function() {
    //console.log("edit variable");

    // Fetch the nodeid and variableid from closest table row (tr)
    var nodeid = $(this).closest('tr').attr('node');
    var variableid = $(this).closest('tr').attr('variable');

    console.log("Nodeid: "+nodeid+" Variable: "+variableid);

    interval = clearInterval(interval);

    var currentname = nodes[nodeid].decoder.variables[variableid].name;
    var currentscale = nodes[nodeid].decoder.variables[variableid].scale;
    if (currentscale==undefined) currentscale = 1;

    // Inline editing html
    var out = "<div class='input-prepend input-append'>";
    out += "<span class='add-on'>Name:</span>";
    out += "<input style='width:150px' class='variable-name-edit' type='text'/ value='"+currentname+"'>";
    out += "<span class='add-on'>Datatype:</span>";
    out += "<select class='variable-datatype-selector' style='width:130px'><option value=1>Integer</option><option value=2>Unsigned long</option></select>";
    out += "<span class='add-on'>Scale:</span>";
    out += "<input class='variable-scale-edit' style='width:60px' type='text' value='"+currentscale+"' / >";
    out += "<span class='add-on'>Units:</span>";
    out += "<select class='variable-units-selector' style='width:60px;'><option value=''></option><option>W</option><option>kW</option><option>Wh</option><option>kWh</option><option>°C</option><option>V</option><option>mV</option><option>A</option><option>mA</option></select>";
    out += "<button class='btn save-variable'>Save</button>";
    out += "</div>";

    // Insert in place of variable name
    $("tr[node="+nodeid+"][variable="+variableid+"] td[class=variable-name]").html(out);

    // Its easiest to set a select input via jquery selectors
    $(".variable-datatype-selector").val(nodes[nodeid].decoder.variables[variableid].type);
    $(".variable-units-selector").val(nodes[nodeid].decoder.variables[variableid].units);

    // The variable edit mode flag disabled the edit icon from appearing on other variables while editing of one is in progress
    variable_edit_mode = true;
});

  // Called when the save button is clicked on the inline variable editor
  $("#nodes").on("click",'.save-variable', function()
  {
    variable_edit_mode = false;

    // Fetch the nodeid and variableid from closest table row (tr)
    var nodeid = $(this).closest('tr').attr('node');
    var variableid = $(this).closest('tr').attr('variable');

    // Fetch the edited values from the input fields & update the decoder
    nodes[nodeid].decoder.variables[variableid].name = $(".variable-name-edit").val();
    nodes[nodeid].decoder.variables[variableid].scale = $(".variable-scale-edit").val()*1;
    nodes[nodeid].decoder.variables[variableid].units = $(".variable-units-selector").val();
    nodes[nodeid].decoder.variables[variableid].type = $(".variable-datatype-selector").val();

    // Save the decoder
    node.setdecoder(nodeid,nodes[nodeid].decoder);

    interval = setInterval(update,5000);
    // redraw, apply new decoder
    redraw();
});


  $("#nodes").on("click",'.record', function()
  {
    interval = clearInterval(interval);
    // Fetch the nodeid and variableid from closest table row (tr)
    var nodeid = $(this).closest('tr').attr('node');
    var variableid = $(this).closest('tr').attr('variable');

    $("#myModal-nodeid").html(nodeid);
    $("#myModal-variablename").html(nodes[nodeid].decoder.variables[variableid].name);

    processlist_ui.nodeid = nodeid;
    processlist_ui.variableid = variableid;

    processlist_ui.init();
    processlist_ui.draw();


    $("#myModal").modal('show');
    $("#myModal").attr('node',nodeid);
    $("#myModal").attr('variable',variableid);
});

  $(".modal-exit").click(function()
  {
    $("#myModal").modal('hide');
    update();
    interval = setInterval(update,updateinterval);
});


  $("#nodes").on("click",'.select-decoder', function()
  {
    interval = clearInterval(interval);
    var nodeid = $(this).attr('node');
    var mode = $(this).attr('mode');

    var current_decoder = 'raw';
    if (nodes[nodeid].decoder!=undefined) {
      current_decoder = nodes[nodeid].decoder.decoder;
  }

  if (mode=='namedisplay')
  {
      var out = "";
      for (z in decoders)
      {
        var selected = ""; if (current_decoder==z) selected = "selected";
        out += "<option value='"+z+"' "+selected+">"+decoders[z].name+"</option>";
    }
    $(this).html("<select class='decoderselect' node="+nodeid+">"+out+"</select>");
}

$(this).attr('mode','selecting')

});

  $("#nodes").on("change",'.decoderselect', function()
  {
    var nodeid = $(this).attr('node');
    var decoder = $(this).val();

    if (decoder=='custom')
    {
      var out = " <div class='input-prepend input-append'>";
      out += "<span class='add-on'>Name:</span>";
      out += "<input style='width:150px' class='node-name-edit' type='text'/ >";
      out += "<span class='add-on'>No of variables:</span>";
      out += "<input style='width:60px' class='node-varnum-edit' type='text'/ >";
      out += "<button class='btn node-create' class='btn'>Create</button>";
      out += "</div>";
      $('.customdecoder[node='+nodeid+']').html(out);
  }
  else
  {
      nodes[nodeid].decoder = decoders[decoder];
      nodes[nodeid].decoder.decoder = decoder;

      node.setdecoder(nodeid,nodes[nodeid].decoder);
      redraw();

      $(this).parent().html("<b>"+nodes[nodeid].decoder.name+"</b>");
      $(this).attr('mode','namedisplay');
      interval = setInterval(update,updateinterval);
  }
});


  $("#nodes").on("click",'.node-create', function()
  {
    // Fetch the nodeid from closest table row (tr)
    var nodeid = $(this).closest('tr').attr('node');

    var nodename = $(".node-name-edit").val();
    var no_of_variables = parseInt($(".node-varnum-edit").val());

    nodes[nodeid].decoder = {
      name: nodename,
      updateinterval: 10,
      variables: []
  };

  for (var i=0; i<no_of_variables; i++)
  {
      nodes[nodeid].decoder.variables.push({name: "variable: "+(i+1), type: 1, scale: 1, units: ''});
  }

  nodes[nodeid].decoder.decoder = nodename.toLowerCase().replace(/ /g, '-');

  node.setdecoder(nodeid,nodes[nodeid].decoder);
  redraw();

    //interval = setInterval(update,5000);
    // redraw, apply new decoder
    //redraw();
});

  // Calculate and color updated time
  function list_format_updated(time)
  {
    time = time * 1000;
    var now = (new Date()).getTime();
    var update = (new Date(time)).getTime();
    var lastupdate = (now-update)/1000;

    var secs = (now-update)/1000;
    var mins = secs/60;
    var hour = secs/3600

    var updated = secs.toFixed(0)+"s ago";
    if (secs>180) updated = mins.toFixed(0)+" mins ago";
    if (secs>(3600*2)) updated = hour.toFixed(0)+" hours ago";
    if (hour>24) updated = "inactive";

    var color = "rgb(255,125,20)";
    if (secs<25) color = "rgb(50,200,50)"
        else if (secs<60) color = "rgb(240,180,20)";

    return "<span style='color:"+color+";'>"+updated+"</span>";
}

processlist_ui.nodes = nodes;
processlist_ui.feedlist = feed.list_assoc();
processlist_ui.inputlist = input.list_assoc();
processlist_ui.processlist = input.getallprocesses();
processlist_ui.events();

</script>
