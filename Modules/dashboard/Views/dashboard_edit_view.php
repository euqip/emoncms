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

global $session,$path;

if (!$dashboard['height']) $dashboard['height'] = 400;
?>
  <link href="<?php echo $path; ?>Modules/dashboard/Views/js/widget.css" rel="stylesheet">
<!-- comment in the dashboard edit view file

-->
  <script type="text/javascript" src="<?php echo $path; ?>Modules/dashboard/dashboard_langjs.php?lang=<?php echo $session['lang']; ?>"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Modules/dashboard/Views/js/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Modules/dashboard/Views/js/render.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Lib/bootstrap/js/context_menu.js"></script>

  <?php require_once "Modules/dashboard/Views/loadwidgets.php"; ?>

<div id="dashboardpage">

</div>

<div style="background-color:#ddd; padding:4px;">
  <span id="widget-buttons"></span>
  <span id="when-selected">
    <button id="options-button" class="btn" title="<?php echo _('configure selected widget'); ?>"><span class="glyphicon glyphicon-wrench"></span><?php echo _('Configure'); ?></button>
    <button id="delete-button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span><?php echo _('Delete'); ?></button>
  </span>
  <button id="save-dashboard" class="btn btn-success" style="float:right"><?php echo _('Not modified'); ?></button>
</div>

<div id="page-container" style="height:<?php echo $dashboard['height']; ?>px; position:relative;">
  <div id="page"><?php echo $dashboard['content']; ?></div>
  <canvas id="can-old" class="context" data-toggle="context" data-target="#contextmenu" width="940px" height="<?php echo $dashboard['height']; ?>px" style="z-index:200; position:absolute; top:0px; left:0px; margin:0; padding:0;"></canvas>
  <div id="can" class="context dotted-10" data-toggle="context" data-target="#contextmenu" width="940px" height="<?php echo $dashboard['height']; ?>px" style="z-index:201; position:absolute; top:0px; left:0px; margin:0; padding:0;"></div>
</div>

<div class="modal fade emoncms-dialog type-primary" id="widget_options" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"><?php echo _('Dashboard widget configuration'); ?></h4>
              </div>
              <div class="modal-body">
                <div id="msgcontent">

                </div>
              </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
                <button class="btn" id="options-save"><span class="emoncms-dialog-button-icon glyphicon glyphicon-save"></span><?php echo _('Save Changes'); ?></button>
             </div>
        </div>
    </div>
</div>

<div id = "contextmenu">
  <ul class="dropdown-menu" role="menu" class="dropdown clearfix" style = "z-index:9999;">
      <li class = "needwidget" ><a tabindex="-1" href="fw"><span class="glyphicon glyphicon-chevron-up"></span><?php echo _("Move Foreward"); ?></a></li>
      <li class = "needwidget" ><a tabindex="-1" href="bw"><span class="glyphicon glyphicon-chevron-down"></span><?php echo _("Move Backward"); ?></a></li>
      <li><a tabindex="-1" href="sv"><span class="glyphicon glyphicon-save"></span><?php echo _("Save Dashboard"); ?></a></li>
      <li class = "needwidget" ><a tabindex="-1" href="set"><span class="glyphicon glyphicon-wrench"></span><?php echo _("Widget settings"); ?></a></li>
      <li class="divider"></li>
      <li class = "needwidget" ><a tabindex="-1" href="del"><span class="glyphicon glyphicon-trash"></span><?php echo _("Delete widget"); ?></a></li>
  </ul>
</div>

<script type="text/javascript" src="<?php echo $path; ?>Modules/dashboard/Views/js/designer.js"></script>
<script type="application/javascript">

  var dashid = <?php echo $dashboard['id']; ?>;
  var path = "<?php echo $path; ?>";
  var apikey = "";
  var feedlist = feed.list();
  var userid = <?php echo $session['userid']; ?>;
  var lang = '<?php echo $session['lang']; ?>';
  var saved = '<?php echo _("Saved") ?>';

  $("#testo").hide();

  var widget = <?php echo json_encode($widgets); ?>;

  for (z in widget)
  {
    var fname = widget[z]+"_widgetlist";
    var fn = window[fname];
    $.extend(widgets,fn());
  }

  var redraw = 0;
  var reloadiframe = 0;

  var grid_size = 10;
  $('#can').width($('#dashboardpage').width());

  designer.canvas = "#can";
  designer.grid_size = 10;
  designer.widgets = widgets;

  designer.init();

  show_dashboard();

  setInterval(function() { update(); }, 10000);
  setInterval(function() { fast_update(); }, 3000);

  $('#can').contextmenu({
    target: '#contextmenu',
    before: function (e,context){
      // use the .desable class when menu item is desabled (ex no widget selected)
      var classname = "disabled";
      if (designer.selected_box ===null){
        $(".needwidget").addClass(classname);
      } else {
        $(".needwidget").removeClass(classname);
      }
      return true;
    },
    onItem: function (context, e) {
    //console.log ($(e.target).attr("href"));
    switch ($(e.target).attr("href")){
      case "fw":
        designer.zindex(1);
        break;
      case "bw":
        designer.zindex(-1);
        break;
      case "sv":
        designer.savedashboard();
        break;
      case "set":
        showsettings();
        break;
      default:
        break;
      }
    }
  });

  $("#save-dashboard").click(function (){
    designer.savedashboard();
  });


  $(window).resize(function(){
    designer.draw();
  });


  $("#options-button").click(function(event) {
    showsettings();
  })

  function showsettings(){
      html= designer.draw_options($("#"+designer.selected_box).attr("class"));
      $('#msgcontent').html(html);
      $('#widget_options').modal('show');

  }

  $('#saveconfig').click(function (e){
    saveoptions();
    update();
    $('#widget_options').modal('hide');
  })

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