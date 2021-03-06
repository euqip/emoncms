<?php

/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org

Ask for user (session) Language when loading dashboard_langjs.php
*/

  global $session, $path;
  $modpath = $path.MODULE."/dashboard/";
 ?>
  <script type="text/javascript" src="<?php echo $modpath; ?>dashboard_langjs.php?lang=<?php echo $session['lang']; ?>"></script>
    <link href="<?php echo $modpath; ?>Views/js/widget.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="<?php echo $modpath; ?>Views/js/widgetlist.js"></script>
    <script type="text/javascript" src="<?php echo $modpath; ?>Views/js/render.js"></script>

    <script type="text/javascript" src="<?php echo $path.MODULE; ?>/feed/feed.js"></script>

    <?php require_once MODULE."/dashboard/Views/loadwidgets.php"; ?>

    <div id="page-container" style="height:<?php echo $dashboard['height']; ?>px; position:relative;">
        <div id="page"><?php echo $dashboard['content']; ?></div>
    </div>

<script type="application/javascript">
    var dashid = <?php echo $dashboard['id']; ?>;
    var path = "<?php echo $path; ?>";
    var widget = <?php echo json_encode($widgets); ?>;
    var apikey = "<?php echo get('apikey'); ?>";
    var userid = <?php echo $session['userid']; ?>;

    for (z in widget)
    {
        var fname = widget[z]+"_widgetlist";
        var fn = window[fname];
        $.extend(widgets,fn());
    }

    var redraw = 1;
    var reloadiframe = 0;

    show_dashboard();
    setInterval(function() { update(); }, 10000);
    setInterval(function() { fast_update(); }, 30);

      $('.iconbutton').click(function (e){
    //console.log("iconbutton click");
    var myhref = ''; if ($(this).attr("href")!=undefined) {myhref=$(this).attr("href");}
    // check if Myhref = '#'
    if (myhref=='#'){myhref='';}
    // perform href if defined
    if (myhref!=''){
        window.location.assign (myhref);
        return false;
      }
    })


</script>
