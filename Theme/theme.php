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

    global $path,$emoncms_version;
    ?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="<?php echo $path; ?>Lib/bootstrap/css/bootstrap.css" rel="stylesheet">
        <!-- Thanks to Baptiste Gaultier for the emoncms dial icon http://bit.ly/zXgScz -->
        <link rel="shortcut icon" href="<?php echo $path; ?>Theme/favicon.png" />
        <!-- APPLE TWEAKS - thanks to Paul Dreed -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-startup-image" href="<?php echo $path; ?>Theme/ios_load.png">
        <link rel="apple-touch-icon" href="<?php echo $path; ?>Theme/logo_normal.png">
        <link href="<?php echo $path; ?>Theme/theme.css" rel="stylesheet">
        <script src="<?php echo $path; ?>Lib/jquery-2.1.3.min.js"></script>

        <title>Emoncms</title>
    </head>

    <body>
        <div id="wrap">
            <!-- navbar -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="http://emoncms.org/">
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
            <!-- end of navbar -->

<div class="content-block">


                <?php if (isset($submenu) && ($submenu)) { ?>
                    <div id="submenu">
                        <div class="container">
                            <?php echo $submenu; ?>
                        </div>
                    </div>
                <?php } ?>

                <?php
                if (!isset($content)) $content = '';
                if (!isset($fullwidth)) {
                    $fullwidth = false;
                    $content= '<div class="container">'.$content.'</div></div>';
                    };
                ?>
                <?php echo '<!-- content comes here -->'; ?>
                <?php echo $content; ?>
    </div>

        <div id="footer">
            <div class="container">
                <span class="text-left">
                    <?php echo _('Powered by '); ?>
                    <a href="http://openenergymonitor.org" target="_blank">openenergymonitor.org</a>
                    <span class="emon-version"> | v<?php echo Configure::read('EmonCMS.version'); ?></span>
                </span>
                <span>
                   ---
                </span>
                <span class="text-right">
                    <a href="http://glyphicons.com/" target = "_blank"><?php echo _('With Glyphicons support');?></a>
                </span>
                <span>
                   ---
                </span>
                <span class="text-right">
                    <a href="<?php echo $path; ?>credits" ><?php echo _('See credits');?></a>
                </span>
            </div>
        </div>
        <!-- end of footer -->

        <script src="<?php echo $path; ?>Lib/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript">
            var $buoop = {};
            $buoop.ol = window.onload;
            window.onload=function(){
             try {if ($buoop.ol) $buoop.ol();}catch (e) {}
             var e = document.createElement("script");
             e.setAttribute("type", "text/javascript");
             e.setAttribute("src", "//browser-update.org/update.js");
             document.body.appendChild(e);
            }
        </script>

</body>

</html>
