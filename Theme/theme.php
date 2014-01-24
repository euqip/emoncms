<!DOCTYPE html>
<?php
  /*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
  */

  global $path;
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <script type="text/javascript" src="<?php echo $path; ?>Lib/jquery-1.9.0.min.js"></script>
        <link href="<?php echo $path; ?>Lib/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo $path; ?>Theme/theme.css" rel="stylesheet">

        <!-- Thanks to Baptiste Gaultier for the emoncms dial icon http://bit.ly/zXgScz -->
        <link rel="shortcut icon" href="<?php echo $path; ?>Theme/favicon.png" />
        <!-- APPLE TWEAKS - thanks to Paul Dreed -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-startup-image" href="<?php echo $path; ?>Theme/ios_load.png">
        <link rel="apple-touch-icon" href="<?php echo $path; ?>Theme/logo_normal.png">
        <title>Emoncms</title>
    </head>

    <body>
        <div id="wrap">
          <nav class="navbar navbar-inverse" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">
                <img src="<?php echo $path; ?>Theme/favicon.png" style="width:28px;"/>
              </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <!-- menu left -->
                        <?php if (!isset($runmenu)) $runmenu = '';
                              echo $mainmenu.$runmenu;
                        ?> 
            </div><!-- /.navbar-collapse -->
          </nav>

          <div id="topspacer"></div>

          <?php if (isset($submenu) && ($submenu)) { ?>  
            <div id="submenu">
                <div class="container">
                    <?php echo $submenu; ?> 
                </div>
            </div><br>
          <?php } ?> 

          <?php
            if (!isset($fullwidth)) $fullwidth = false;
            if (!$fullwidth) {
          ?>

          <div class="container">
              <?php echo $content; ?>
          </div>

          <?php } else { ?>
              <?php echo $content; ?>
          <?php } ?>


          <div style="clear:both; height:60px;"></div> 
        </div>

        <div id="footer">
            <?php echo _('Powered by '); ?>
            <a href="http://openenergymonitor.org">openenergymonitor.org</a> 
        </div>

        <script src="<?php echo $path; ?>Lib/bootstrap/js/bootstrap.js"></script>

    </body>

</html>
