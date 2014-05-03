<?php
    global $session,$path;
?>
<script type="text/javascript" src="<?php echo $path; ?>Modules/user/user.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>


<link href="<?php echo $path; ?>Lib/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $path; ?>Lib/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/datetimepicker/js/locales/bootstrap-datetimepicker.<?php echo substr($session['lang'],0,2); ?>.js" charset="UTF-8"></script>
<!-- source: https://github.com/smalot/bootstrap-datetimepicker -->
<div class="container">
        <div id="localheading">
          <h2><?php echo _('Feeds'); ?>
            <a href="api"><small><span class = "glyphicon glyphicon-info-sign" title = "<?php echo _('Feeds API Help'); ?>"></span></small></a>
            <a href="#" id="refreshfeedsize" ><small><span class = "glyphicon glyphicon-refresh" title = "<?php echo _('Refresh feed size'); ?>"></span></small></a>
          </h2>
        </div>

        <div id="table"></div>

        <div id="nofeeds" class="alert alert-block hide">
                <h4 class="alert-heading"><?php echo _('No feeds created'); ?></h4>
                <p><?php echo _('Feeds are where your monitoring data is stored. The recommended route for creating feeds is to start by creating inputs (see the inputs tab). Once you have inputs you can either log them straight to feeds or if you want you can add various levels of input processing to your inputs to create things like daily average data or to calibrate inputs before storage. You may want to follow the link as a guide for generating your request.'); ?><a href="api"><?php echo _('Feed API helper'); ?></a></p>
        </div>
</div>

<div class="modal fade emoncms-dialog type-primary" id="ExportModal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php echo _('CSV data export') ?></h4>
             </div>
            <div class="modal-body">
                <div>
                    <span><?php echo _('Selected feed:') ?> </span><b><span id="SelectedExportFeed"></span></b></p>
                    <p><?php echo _('Select the dates range interval that you wish to export: (From - To)') ?> </p>
                </div>
                <div id="modalhtml" class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"  id="export-start-div">
                                <div class="input-group date form_datetime" title="<?php echo _('Start Date'); ?>" data-date="" data-date-format="dd MM yyyy hh:ii:ss" data-link-field="export-start" data-link-format="yyyy/mm/dd hh:ii:ss">
                                    <input class="form-control" size="16" type="text" value="" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <input type="hidden" id="export-start" value="" />
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                        <div class="col-md-12">
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

<div class="modal fade emoncms-dialog type-danger" id="export-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php echo _('Data selection error') ?></h4>
            </div>
            <div class="modal-body">
                <div id="alert-msg" class="type-danger ">
                    <div id="start-date-error" style="display:none"><?php echo _('Please enter a valid START date.') ?> </div>
                    <div id="end-date-error " style="display:none"><?php echo _('Please enter a valid END date.') ?></div>
                    <div id="date-error" style="display:none"><?php echo _('Start date should be before end date.') ?></div>
                    <div id="size-error" style="display:none"><?php echo _('Download file size too large (download limit: 10Mb).') ?></div>
                    <div id="interval-error" style="display:none"><?php echo _('Please select sample time interval.') ?></div>
               </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Close'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade emoncms-dialog type-danger" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">"<?php echo _('Are you sure you want to delete this feed?'); ?>"</h4>
            </div>
            <div class="modal-body">
                <div class="type-danger ">
                    <div> <?php echo _('WARNING deleting a feed is permanent'); ?> </div>
               </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
                <button class="btn" id="confirmdelete"><span class="emoncms-dialog-button-icon glyphicon glyphicon-trash"></span><?php echo _('Delete Feed'); ?></button>
             </div>
        </div>
    </div>
</div>


<script>

    var path = "<?php echo $path; ?>";
    table.element = "#table";

  table.fields = {
    // Actions
    'delete-action': { 'title':'','tooltip':'<?php echo _("Delete"); ?>', 'type':"delete", 'display':"yes", 'colwidth':" style='width:30px;'"},
    'edit-action':   { 'title':'','tooltip':'<?php echo _("Edit"); ?>','alt':'<?php echo _("Save"); ?>', 'type':"edit", 'display':"yes", 'colwidth':" style='width:30px;'"},

    'id':            { 'title':"<?php echo _('Id'); ?>", 'type':"fixed",'colwidth':""},
    'name':          { 'title':"<?php echo _('Name'); ?>", 'type':"text",'colwidth':"", 'display':"yes", 'colwidth':" style='width:150px;'"},
    'tag':           { 'title':"<?php echo _('Tag'); ?>", 'type':"text",'colwidth':"", 'display':"yes", 'colwidth':" style='width:150px;'"},
    'datatype':      { 'title':"<?php echo _('Datatype'); ?>", 'type':"select", 'options':['','REALTIME','DAILY','HISTOGRAM'], 'display':"yes", 'colwidth':" style='width:200px;'"},
    'engine':        { 'title':"<?php echo _('Engine'); ?>", 'type':"select", 'options':['MYSQL','TIMESTORE','PHPTIMESERIES','GRAPHITE','PHPTIMESTORE','PHPFINA'], 'display':"yes", 'colwidth':" style='width:150px;'"},
    'public':        { 'title':"<?php echo _('Public'); ?>", 'tooltip': "<?php echo _('Make feed public'); ?>", 'type':"icon", 'trueicon':"glyphicon glyphicon-globe", 'falseicon':"glyphicon glyphicon-lock", 'iconaction':"public", 'display':"yes", 'colwidth':" style='width:30px;'"},
    'size':          { 'title':"<?php echo _('Size'); ?>", 'type':"fixed"},
    'time':          { 'title':"<?php echo _('Updated'); ?>", 'type':"updated"},
    'value':         { 'title':"<?php echo _('Value'); ?>",'type':"value"},
    'view-action':   { 'title':'','tooltip':'<?php echo _("Preview"); ?>', 'type':"iconlink", 'link':path+"vis/auto?feedid=", 'icon':'glyphicon glyphicon-eye-open', 'display':"yes", 'colwidth':" style='width:30px;'"},
    'export-action': { 'title':'', 'tooltip':'<?php echo _("Download data"); ?>', 'type':"iconbasic", 'icon_action':"export-action", 'icon':'glyphicon glyphicon-download', 'display':"yes", 'colwidth':" style='width:30px;'"},
  }
/*
=======
    table.fields = {

        one line is changed to make engine non updatable
        'engine':{'title':"<?php echo _('Engine'); ?>", 'type':"fixedselect", 'options':['MYSQL','TIMESTORE','PHPTIMESERIES','GRAPHITE','PHPTIMESTORE','PHPFINA','PHPFIWA']},

    }
>>>>>>> f78a8022ecc4c3ed3878e462ed13fc052024e627
*/
    table.groupby = 'tag';
    table.deletedata = false;

    table.draw();
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
            $("#localheading").show();
        } else {
            $("#nofeeds").show();
            $("#localheading").hide();
        }
    }
    var updatetime=10000;

    var updater = setInterval(update, updatetime);

    $("#table").bind("onEdit", function(e){
        clearInterval(updater);
    });

    $("#table").bind("onSave", function(e,id,fields_to_update){
        feed.set(id,fields_to_update);
        updater = setInterval(update, updatetime);
        update()
    });

    $("#refreshfeedsize").click(function(){
        $.ajax({ url: path+"feed/updatesize.json", success: function(data){update();} });
    });

    $("#table").bind("onDelete", function(e,id,row){
        clearInterval(updater);
        $('#myModal').attr('feedid',id);
        $('#myModal').attr('feedrow',row);
        $('#myModal').modal('show')
    })


    $('#confirmdelete').click(function(e){
       var id = $('#myModal').attr('feedid');
        var row = $('#myModal').attr('feedrow');
        feed.remove(id);
        table.remove(row);
        update();

        $('#myModal').modal('hide');
        updater = setInterval(update, updatetime);
    })


    // Feed Export feature
    $('#export-timezone-list').on('change',function(e){
        $("#export-timezone").val($("#export-timezone-list").val());
        calcdownloadsize();
/*
=======
    
    $("#table").on("click",".icon-circle-arrow-down", function(){
        var row = $(this).attr('row');
        $("#SelectedExportFeed").html(table.data[row].tag+": "+table.data[row].name);
        $("#export").attr('feedid',table.data[row].id);
        
        if ($("#export-timezone").val()=="") {
            var u = user.get();
            $("#export-timezone").val(parseInt(u.timezone));
        }
        
        $('#ExportModal').modal('show');
>>>>>>> 85f4e7d87c11406d72ea57fb13cfe83068389d82
*/
    });

    $('#export-interval-list').on('change',function(e){
        $("#export-interval").val($("#export-interval-list").val());
        calcdownloadsize();

    });

    $('.form_datetime').datetimepicker({
        language:  '<?php echo substr($session['lang'],0,2); ?>',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 0,
        forceParse: 0,
        showMeridian: 0
    });

    $('#export-start-div').on('change', function(e)
    {
        calcdownloadsize();
    });

    $('#export-end-div').on('change', function(e)
    {
        calcdownloadsize();
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
    }

    function calcdownloadsize(){
        var downloadsize = 0;
        if (!parse_timepicker_time($("#export-start").val())){return downloadsize;}
        if (!parse_timepicker_time($("#export-end").val())){return downloadsize;}
        var export_start = parse_timepicker_time($("#export-start").val());
        var export_end = parse_timepicker_time($("#export-end").val());
        if (export_end<export_start) {return downloadsize;}
        //reverse start end if start is greater than end ??
        var export_interval = $("#export-interval").val();
        downloadsize = ((export_end - export_start) / export_interval) * 17; // 17 bytes per dp
        //console.log(downloadsize);
        $("#downloadsize").html((downloadsize/1024).toFixed(0));
        return downloadsize;
    }


    $("#export").click(function()
    {
        var feedid = $(this).attr('feedid');
        var export_start = parse_timepicker_time($("#export-start").val());
        var export_end = parse_timepicker_time($("#export-end").val());
        var export_interval = $("#export-interval").val();
        var export_timezone = parseInt($("#export-timezone").val());
        var downloadsize = calcdownloadsize();
        var export_error = false;
        if (!export_start) {
            $('#start-date-error').show
            export_error=true;
            }
        if (!export_end) {
            $('#end-date-error').show();
            export_error=true;
            }
        if (!export_interval) {
            $('#interval-error').show();
            export_error=true;
            }
        if (downloadsize>(10*1048576)) {
            $('#size-error').show();
            export_error=true;
            }
        if (export_start>=export_end) {
            $('#date-error').show();
            export_error=true;
            }
        if (export_error) {
            $('#export-error').modal('show');
            return false;
            }
        var cmd=path+"feed/csvexport.json?id="+feedid+"&start="+(export_start+(export_timezone*3600))+"&end="+(export_end+(export_timezone*3600))+"&interval="+export_interval;
        //console.log(cmd);
        window.open (cmd);
        //window.open(path+"feed/csvexport.json?id="+feedid+"&start="+(export_start+(export_timezone*3600))+"&end="+(export_end+(export_timezone*3600))+"&interval="+export_interval);
        $('#ExportModal').modal('hide');
    });



    function parse_timepicker_time(timestr)
    {
        var tmp = timestr.split(" ");
        if (tmp.length!=2) return false;

        var date = tmp[0].split("/");
        if (date.length!=3) return false;

        var time = tmp[1].split(":");
        if (time.length!=3) return false;
        // year, month (-1),day, hours, mins, sec

        var xx= new Date(date[0],date[1]-1,date[2],time[0],time[1],time[2],0).getTime() / 1;
        return xx;
   }
</script>
