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
        <script src="<?php echo $path; ?>Lib/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $path; ?>Lib/date.format.js"></script>
        <link href="<?php echo $path; ?>Lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <title>emoncms embed</title>
    </head>
    <body>
        <div class="content">
            <?php print $content; ?>
        </div>
    </body>
</html>
