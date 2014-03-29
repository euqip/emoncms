<!--
     All Emoncms code is released under the GNU Affero General Public License.
     See COPYRIGHT.txt and LICENSE.txt.

        ---------------------------------------------------------------------
        Emoncms - open source energy visualisation
        Part of the OpenEnergyMonitor project:
        http://openenergymonitor.org

        this is the editrealtime vis module
-->

<?php
    global $path, $embed;

    $type = 1;
?>
<style>
    .updatebox{
        width:100% ;
        background-color:#ddd;
        padding:10px;
        margin-left:5px;
        margin-right:15px;
        border-radius:5px; 
        margin-top: 0px;
        margin-bottom: 0px;
   }
    .graphbuttonsblock{
        position:absolute;
        top:10px;
        right:20px;
        opacity: 0.1;
    -webkit-transition: opacity 1s ease-in-out;/* transition pour Chrome et Safari */
    -moz-transition: opacity 1s ease-in-out;/* transition pour Firefox */
    -o-transition: opacity 1s ease-in-out;/* transition pour Opéra */
    transition: opacity 1s ease-in-out; /* on écrit cette ligne à la fin de façon à ce que ce soit elle qui soit prise en compte lorsque l'attribut transition sera pris en compte par tous les navigateurs */
    }

    .graphbuttonsblock:hover{
        opacity:1;
    }

    .grapharea{
        height:350px;
        width:100%;
        position:relative;
    }
    .form-group{
        margin-bottom:5px;
    }

    .container
{
    display:table;
    width: 97%;
    margin-top: -13px;
    margin-right:10px;
    padding: 10px 0 10px 0; /*set left/right padding according to needs*/
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.row
{
    height: 100%;
    display: table-row;
}
.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8, .col-md-9
{
    display: table-cell;
    float: none;
    padding-top: 3px;
}
.alignbottom{
    padding-top:12px;
}
.glyphicon{
    margin-right: 10px;
}
div {
    padding-top:0px;
}
</style>


<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.selection.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.time.min.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Modules/vis/visualisations/common/api.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Modules/vis/visualisations/common/inst.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Modules/vis/visualisations/common/proc.js"></script>



<?php if (!$embed) { ?>
<h2>Datapoint editor: <?php echo $feedidname; ?></h2>
<p>Click on a datapoint to select, then in the edit box below the graph enter in the new value. You can also add another datapoint by changing the time to a point in time that does not yet have a datapoint.</p>
<?php } ?>
<div class="container">
    <div id="graph_bound" class="grapharea">
        <div id="graph" title ="<?php echo _('Use the above buttons to change scale or view window.') ?>">  
        </div>
        <div class="graphbuttonsblock">
            <input class="time" type="button" value="<?php echo _('D')?>" time="1"/>
            <input class="time" type="button" value="<?php echo _('W')?>" time="7"/>
            <input class="time" type="button" value="<?php echo _('M')?>" time="30"/>
            <input class="time" type="button" value="<?php echo _('Y')?>" time="365"/> |
            <input id="zoomin" type="button" value="+"/>
            <input id="zoomout" type="button" value="-"/>
            <input id="left" type="button" value="<"/>
            <input id="right" type="button" value=">"/>
        </div>

        <h3 style="position:absolute; top:00px; left:50px;"><span id="stats"></span></h3>
    </div>
</div>

<div class="container">
    <div class="updatebox">

        <div class="row">
            <div class="col-md-2">
                <label for="time" class="col-sm-4 control-label"><?php echo _('Edit feed') ?>_<?php echo $feedid; ?> @ <span id="humantime"></span>
                </label>               
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="time" placeholder="<?php echo _('Enter Unix time')?>">              
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                    <label for="newvalue" class="col-sm-4 control-label"><?php echo _('new value') ?>:</label>
            </div>
            <div class="col-md-4">
                    <input type="text" class="form-control" id="newvalue" placeholder="<?php echo _('Enter new value')?>">                    
            </div>
        </div>
        <div class="row">
           <div class="col-md-1">
                <button id="okb" class="btn btn-info"  title ="<?php echo _('Save the changes made to one data sample"><span class="glyphicon glyphicon-save') ?>"></span><?php echo _('Save'); ?></button>
                <button id="delete-button" class="btn btn-danger"  title ="<?php echo _('Delete the selected sample"><span class="glyphicon glyphicon-trash') ?>"></span><?php echo _('Delete point'); ?></button>
            </div>
            <div class="col-md-1">
                <button id="export-button" class="btn btn-warning"  title ="<?php echo _('Export the graph data"><span class="glyphicon glyphicon-download') ?>"></span><?php echo _('export graph data'); ?></button>                
            </div>
        </div>

        
    </div>
</div>
<div class="container">
    <div class="updatebox">
    <div class="row">
            <div class="col-md-2">
                <label for="multiplyvalue" class="control-label"><?php echo _('Multiply data in window by') ?><span id="humantime1"></span>
                </label>    
             </div>
            <div class="col-md-4">
                <input type="text" id="multiplyvalue"  class="form-control" value="" >
           </div>
        </div>

        <div class="row">
           <div class="col-md-1">
                <button id="multiply-submit" class="btn btn-info"><span class="glyphicon glyphicon-save"></span><?php echo _('Save'); ?></button>            
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-1">
            </div>
        </div>

    </div>
</div>



<div class="modal emoncms-dialog type-danger" id="myModal" style="display:none;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php echo _('WARNING deleting feed data is permanent'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="type-danger ">
                    <div> <?php echo _('Are you sure you want to delete this data sample?'); ?> </div>
               </div>
            </div>           
            <div class="modal-footer">
                <button class="btn" id="canceldelete" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
                <button class="btn btn-danger" id="confirmdelete"><span class="emoncms-dialog-button-icon glyphicon glyphicon-trash"></span><?php echo _('Delete it permanently'); ?></button>
             </div>
        </div>
    </div>
</div>


<script id="source" language="javascript" type="text/javascript">


    $('#graph').width($('#graph_bound').width());
    $('#graph').height($('#graph_bound').height());
    var feedid = "<?php echo $feedid; ?>";
    var feedname = "<?php echo $feedidname; ?>";
    var type = "<?php echo $type; ?>";
    var path = "<?php echo $path; ?>";
    var apikey = "<?php echo $write_apikey; ?>";

    var timeWindow = (3600000*24.0*7);                //Initial time window
    var start = ((new Date()).getTime())-timeWindow;      //Get start time
    var end = (new Date()).getTime();             //Get end time

    vis_feed_data();

    function vis_feed_data()
    {
        var graph_data = get_feed_data(feedid,start,end,1000);
        var stats = power_stats(graph_data);
        //$("#stats").html("Average: "+stats['average'].toFixed(0)+"W | "+stats['kwh'].toFixed(2)+" kWh");

        var plotdata = {data: graph_data, lines: { show: true, fill: true }};
        if (type == 2) plotdata = {data: graph_data, bars: { show: true, align: "center", barWidth: 3600*18*1000, fill: true}};

        var plot = $.plot($("#graph"), [plotdata], {
            grid: { show: true, clickable: true},
            xaxis: { mode: "time", timezone: "browser", min: start, max: end },
            selection: { mode: "x" }
        });

    }
    $('#time').change(function(){
        var newtime = $('#time').val();
        //there is a need to ajust entered huma,n time value back to UTC !
        var formattedTime = new Date(newtime*1000).format('Y-m-d H:i:s');
        $('#humantime').html(formattedTime);

    })

    $("#graph").bind("plotclick", function (event, pos, item) {
        //stored time is UTC
        //convert to the user real tim by adding time offset when displaying human time
        $("#time").val(item.datapoint[0]/1000);
        var formattedTime = new Date(item.datapoint[0]).format('Y-m-d H:i:s');
        $('#humantime').html(formattedTime);
        $("#newvalue").val(item.datapoint[1]);
        //$("#stats").html("Value: "+item.datapoint[1]);
    });

    //--------------------------------------------------------------------------------------
    // Graph zooming
    //--------------------------------------------------------------------------------------
    $("#graph").bind("plotselected", function (event, ranges) { start = ranges.xaxis.from; end = ranges.xaxis.to; vis_feed_data(); });
    //----------------------------------------------------------------------------------------------
    // Operate buttons
    //----------------------------------------------------------------------------------------------
    $("#zoomout").click(function () {inst_zoomout(); vis_feed_data();});
    $("#zoomin").click(function () {inst_zoomin(); vis_feed_data();});
    $('#right').click(function () {inst_panright(); vis_feed_data();});
    $('#left').click(function () {inst_panleft(); vis_feed_data();});
    $('.time').click(function () {inst_timewindow($(this).attr("time")); vis_feed_data();});
    //-----------------------------------------------------------------------------------------------

    $('#okb').click(function () {
        var time = $("#time").val();
        var newvalue = $("#newvalue").val();

        $.ajax({
            url: path+'feed/update.json',
            data: "&apikey="+apikey+"&id="+feedid+"&time="+time+"&value="+newvalue,
            dataType: 'json',
            async: false,
            success: function() {}
        });
        vis_feed_data();
    });

    $('#multiply-submit').click(function () {

        var multiplyvalue = $("#multiplyvalue").val();

        $.ajax({
            url: path+'feed/scalerange.json',
            data: "&apikey="+apikey+"&id="+feedid+"&start="+start+"&end="+end+"&value="+multiplyvalue,
            dataType: 'json',
            async: false,
            success: function() {}
        });
        vis_feed_data();
    });

    $('#delete-button').click(function () {
        $('#myModal').show();
    });
    /*
    $('#delete-button1').click(function () {
        // does not work because jquery is loaded twice
        $('#myModal').modal('show');
    });
    */

    $("#canceldelete").click(function(){
        $('#myModal').hide();        
    });

    $("#confirmdelete").click(function()
    {
        $.ajax({
            url: path+'feed/deletedatarange.json',
            data: "&apikey="+apikey+"&id="+feedid+"&start="+start+"&end="+end,
            dataType: 'json',
            async: false,
            success: function() {}
        });
        vis_feed_data();
        $('#myModal').hide();
        //$('#myModal').modal('hide');
    });


    $("#export-button").click(function()
    {
        //var feedid = $(this).attr('feedid');
        var export_start = parseInt(start/1000);
        var export_end = parseInt(end/1000);
        //try to find timeinterval used in graph
        var export_interval = 1;
        var export_error = false;
        var cmd=path+"feed/csvexport.json?id="+feedid+"&start="+export_start+"&end="+export_end+"&interval="+export_interval;
        //console.log(cmd);
        window.open (cmd);
    });

</script>

