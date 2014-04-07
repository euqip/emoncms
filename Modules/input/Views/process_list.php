<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/

global $path, $session, $module;
if (!$module){$module = "input";}
$itemname=_('Node'); 
?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/processlist.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/input.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/input/Views/process_info.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>
<br>

<div><h2><span id="inputname"></span> <?php echo _('Config'); ?></h2></div>
<p><?php echo _('Input processes are executed sequentially with the result being passed back for further processing by the next processor in the input processing list.'); ?></p>

<div id="processlist-ui">
    <table class="table">
        <tr>
            <th style='width:5%;'></th>
            <th style='width:5%;'><?php echo _('Order'); ?></th>
            <th><?php echo _('Process'); ?></th>
            <th><?php echo _('Arg'); ?></th>
            <th></th>
            <th><?php echo _('Actions'); ?></th>
        </tr>
        <tbody id="variableprocesslist"></tbody>

    </table>
<div id = "alertmsg" class="container alert alert-warning" style="display:none;">
    <div id = "no-process" style="display:none;"><?php echo _('You have no processes defined'); ?></div>    


</div>

<style type="text/css">
</style>


<div class="addprocess container">
    <div><h2><?php echo _('Add process to '); ?></h2></div>
    <p><?php echo _('comments : There is no update interface available. just delete process redefine it and adjust position it in the flow.
    '); ?></p>


    <form class="form-inline">
        <div class="form-group">
            <!-- first block -->
            <div class="form-group">
                <select id="process-select" class="form-control"></select>
            </div>
            <div class="form-group" id="type-value">
                <input type="text" id="value-input" />
            </div>
            <div class="form-group" id="type-input">
                <select id="input-select"  class="form-control"></select>
            </div>            
        </div>
           <!-- second block -->
        <div class="form-group" id="type-feed">
            <div class="form-group">
                <select id="feed-select"  class="form-control"></select>
            </div>
            <div class="form-group">
                <input type="text" id="feed-name" class="form-control" placeholder="<?php echo _('Feed name...') ?>"  title = "<?php echo _('Define here your unique feed name')?>" />
            </div>
            <div class="form-group">
                <select id="feed-engine"  class="form-control"  title = "<?php echo _('Feed engine selector')?>">
                <optgroup label="Recommended">
                    <option value=6 selected><?php echo _('Fixed Interval With Averaging (PHPFIWA)')?></option>
                    <option value=5 ><?php echo _('Fixed Interval No Averaging (PHPFINA)')?></option>
                    <option value=2 ><?php echo _('Variable Interval No Averaging (PHPTIMESERIES)')?></option>
                </optgroup>
                <optgroup label="Other">
                    <option value=4 ><?php echo _('PHPTIMESTORE (Port of timestore to PHP)')?></option>
                    <option value=1 ><?php echo _('TIMESTORE (Requires installation of timestore)')?></option>
                    <option value=3 ><?php echo _('GRAPHITE (Requires installation of graphite)')?></option>
                    <option value=0 ><?php echo _('MYSQL (Slow when there is a lot of data)')?></option>
                </optgroup>

                </select>
            </div>
            <div class="form-group">
                <select id="feed-interval"  class="form-control" title = "<?php echo _('Feed Inteval selector')?>">
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
        </div>


        <div  class="form-group">
            <button id="process-add" class="btn btn-info"/><?php echo _('Add'); ?></button>
        </div>
    </form>
</div>


</div>  <!-- Add process-->

</div>

        <br />



<div class="alert alert-success">
    <h3><?php echo _('Processes informations'); ?></h3>
    <div id="description"></div>
</div>

<!--Error messages modal-->
<div class="modal fade emoncms-dialog type-danger" id="ErrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php echo _('Process creation error'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="type-danger ">
                    <div id ="err_feedname" style ="display:none;"> <?php echo _('WARNING Please enter a feed name'); ?> </div>
                    <div id ="err_creation" style ="display:none;"> <?php echo _('WARNING Feed could not be created,'); ?>
                        <span id="resultmsg"></span>
                    </div>
               </div>
            </div>           
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('ok'); ?></button>
              </div>
        </div>
    </div>
</div>

<hr/>


<script type="text/javascript">

var path = "<?php echo $path; ?>";
var itemname = "<?php echo $itemname; ?>";
var moveup = "<?php echo _("Move Up"); ?>";
var movedown = "<?php echo _("Move Down"); ?>";
var delprocess = "<?php echo _("Delete"); ?>";
var createnew = "<?php echo _("CREATE NEW:"); ?>";
var nodetext = "<?php echo _("Node"); ?>";
var inputvalue= "<?php echo _("Input value"); ?>";
var feedvalue= "<?php echo _("Feed Value"); ?>";

feedvalue

processlist_ui.inputid = <?php echo $inputid; ?>;

//console.log(processlist_ui.inputid);

processlist_ui.feedlist = feed.list_assoc();
processlist_ui.inputlist = input.list_assoc();
processlist_ui.processlist = input.getallprocesses();
processlist_ui.variableprocesslist = input.processlist(processlist_ui.inputid);
processlist_ui.init();

$(document).ready(function() {
  processlist_ui.draw();
  processlist_ui.events();
});

// SET INPUT NAME
var inputname = "";
if (processlist_ui.inputlist[processlist_ui.inputid].description!="") inputname = processlist_ui.inputlist[processlist_ui.inputid].description; else inputname = processlist_ui.inputlist[processlist_ui.inputid].name;
$("#inputname").html(itemname+processlist_ui.inputlist[processlist_ui.inputid].nodeid+": "+inputname);


</script>
