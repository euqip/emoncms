<!--
     All Emoncms code is released under the GNU Affero General Public License.
     See COPYRIGHT.txt and LICENSE.txt.

        ---------------------------------------------------------------------
        Emoncms - open source energy visualisation
        Part of the OpenEnergyMonitor project:
        http://openenergymonitor.org
-->

<?php
    global $path, $embed;
    $feedA= isset($_GET['feedA']) ? $_GET['feedA']: 0;
    $feedB= isset($_GET['feedB']) ? $_GET['feedB']: 0;
?>

 <!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.time.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.selection.min.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo $path.MODULE; ?>/vis/visualisations/common/api.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path.MODULE; ?>/vis/visualisations/common/inst.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path.MODULE; ?>/vis/visualisations/common/proc.js"></script>

<?php if (!$embed) { ?>
<h2><?php echo _("Feed calibration and comparison tool"); ?></h2>
<p><?php echo _("Use this tool to compare two feeds: FeedA and FeedB. Enter feed ids for comparison below. If there is a difference between feed values adjust the calibration to see if the difference can be removed."); ?></p>

<div class="input-prepend">
    <span class="add-on"><?php echo _("Feed A ID"); ?></span>
    <input id="feedA" type="text"  style="width:100px">
</div><br>

<div class="input-prepend input-append">
    <span class="add-on"><?php echo _("Feed B ID"); ?></span>
    <input id="feedB" type="text"  style="width:100px">
    <button id="load" class="btn btn-info"><?php echo _("Load"); ?></button>
</div><br>

<div class="input-prepend input-append">
    <span class="add-on"><?php echo _("Feed B Calibration"); ?></span>
    <input id="calibration" type="text"  style="width:100px" value="1.0">
    <button id="update" class="btn btn-info"><?php echo _("Update"); ?></button>
</div>

<?php } ?>

<div id="graph_bound" style="width:100%; height:400px; position:relative; ">
    <div id="power"></div>
    <div style="position:absolute; top:20px; left:40px;">

        <input class="time" type="button" value="D" time="1"/>
        <input class="time" type="button" value="W" time="7"/>
        <input class="time" type="button" value="M" time="30"/>
        <input class="time" type="button" value="Y" time="365"/> |

        <input id="zoomin" type="button" value="+"/>
        <input id="zoomout" type="button" value="-"/>
        <input id="left" type="button" value="<"/>
        <input id="right" type="button" value=">"/>

    </div>

    <h3 style="position:absolute; top:0px; left:410px;"><span id="stats"></span></h3>
</div>

<h3><?php echo _("Difference between feeds (FeedB calibration applied - FeedA)"); ?></h3>
<div id="diff" style="width:100%; height:400px; "></div>

<h3><?php echo _("FeedA vs FeedB"); ?></h3>
<p><?php echo _("Relationship should be linear if measurements are the same"); ?></p>
<div id="line" style="width:100%; height:400px; "></div>

<script id="source" language="javascript" type="text/javascript">

    var path = "<?php echo $path; ?>";
    var apikey = "<?php echo $apikey; ?>";
    var valid = "<?php echo $valid; ?>";

    var embed = <?php echo $embed; ?>;
    $('#power').width($('#graph_bound').width());
    $('#power').height($('#graph_bound').height());
    if (embed) $('#graph').height($(window).height());

    var timeWindow = (3600000*24.0*7);                //Initial time window
    var start = ((new Date()).getTime())-timeWindow;      //Get start time
    var end = (new Date()).getTime();             //Get end time

    var feedAid = <?php echo $feedA; ?>;
    var feedBid = <?php echo $feedB; ?>;

    $("#feedA").val(feedAid);
    $("#feedB").val(feedBid);

    var calibration = 1;

    var feedA = [];
    var feedB = [];
    var feedB_cal = [];
    var diff = [];

    var feedAY = [];
    var line_data = [];

    var calibration_update = false;

    vis_feed_data();

    $(window).resize(function(){
        $('#graph').width($('#graph_bound').width());
        if (embed) $('#graph').height($(window).height());
        plot();
    });

    function vis_feed_data()
    {

        feedB_cal = [];
        diff = [];

        feedAY = [];
        line_data = [];

        if (feedAid>0 && feedBid>0 && calibration_update != true) {
            feedA = [];
            feedB = [];
            feedA = get_feed_data(feedAid,start,end,800);
            feedB = get_feed_data(feedBid,start,end,800);
        }

        var sumX=0,sumY=0,sumXY=0,sumX2=0,n=0;
        for (z in  feedB)
        {
            // Create calibrated B
            feedB_cal[z] = [];
            feedB_cal[z][0] = feedB[z][0];
            feedB_cal[z][1] = calibration * feedB[z][1];

            if (feedA[z]!=undefined)
            {
                // Calculate line of best fit variables
                var XY = 1.0*feedA[z][1] * feedB[z][1];
                var X2 = 1.0*feedA[z][1] * feedA[z][1];

                sumX += 1.0*feedA[z][1];
                sumY += 1.0*feedB[z][1];

                sumXY += XY;
                sumX2 += X2;

                n++;
            }
        }

        var slope = ((n * sumXY - (sumX*sumY)) / (n * sumX2 - (sumX*sumX)));
        var intercept = (sumY - slope*sumX) / n;
        console.log(slope);

        line_data[0] = [];
        line_data[0][0] = 0;
        line_data[0][1] = slope * line_data[0][0] + intercept;

        line_data[1] = [];
        line_data[1][0] = 9000;
        line_data[1][1] = slope * line_data[1][0] + intercept;

        for (z in feedA)
        {
            if (feedB_cal[z]!=undefined) {
                diff[z] = [];
                diff[z][0] = 1.0*feedA[z][0];
                diff[z][1] = 1.0*feedB_cal[z][1] - 1.0*feedA[z][1];

                feedAY[z] = [];
                feedAY[z][0] = feedA[z][1];
                feedAY[z][1] = feedB_cal[z][1];
            }
        }

        plot();
        calibration_update = false;
    }

    function plot()
    {

        var plot = $.plot($("#power"), [
        {data: feedA, lines: { show: true }},
        {data: feedB_cal, lines: { show: true }}], {
            grid: { show: true, hoverable: true, clickable: true },
            xaxis: { mode: "time", timezone: "browser", min: start, max: end },
            selection: { mode: "x" }
        });

        var plot = $.plot($("#diff"), [{color:2, data: diff, lines: { show: true }}], {
            grid: { show: true, hoverable: true },
            xaxis: { mode: "time", timezone: "browser", min: start, max: end }
        });

        var plot = $.plot($("#line"), [
            {color:2,data: feedAY, points: { show: true }},
            {color: "#000",data: line_data,lines: { show: true, fill: false }}],{
                grid: { show: true, hoverable: true },
                xaxis: { min: 0, max: 500 },
                yaxis: { min: 0, max: 500 }
            });
    }

    //--------------------------------------------------------------------------------------
    // Graph zooming
    //--------------------------------------------------------------------------------------
    $("#power").bind("plotselected", function (event, ranges) { start = ranges.xaxis.from; end = ranges.xaxis.to; vis_feed_data(); });
    //----------------------------------------------------------------------------------------------
    // Operate buttons
    //----------------------------------------------------------------------------------------------
    $("#zoomout").click(function () {inst_zoomout(); vis_feed_data();});
    $("#zoomin").click(function () {inst_zoomin(); vis_feed_data();});
    $('#right').click(function () {inst_panright(); vis_feed_data();});
    $('#left').click(function () {inst_panleft(); vis_feed_data();});
    $('.time').click(function () {inst_timewindow($(this).attr("time")); vis_feed_data();});
    //-----------------------------------------------------------------------------------------------

    $("#load").click(function () {
        feedAid = $("#feedA").val();
        feedBid = $("#feedB").val();
        vis_feed_data();
    });

    $("#update").click(function () {
        calibration = 1.0 * $("#calibration").val();
        calibration_update = true;
        vis_feed_data();
    });
</script>

