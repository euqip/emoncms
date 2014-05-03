<?php
/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

global $path, $session, $useckeditor;
?>

<span class="dashlist"><?php echo _("Dashboards:"); ?></span>

<ul class="greydashmenu">
    <?php echo $menu; ?>
</ul>

<?php if ($session['write']) { ?>

<div align="right" style="padding:4px;">
    <?php if ($type=="view" && isset($id)) { ?>
    <span href="<?php echo $path; ?>dashboard/edit?id=<?php echo $id; ?>" class="iconbutton" title="<?php echo _("Draw Editor"); ?>" >
        <span class="glyphicon glyphicon-edit"></span>
    </span>
    <?php } ?>

    <?php if ($type=="edit" && isset($id)) { ?>
    <span href="<?php echo $path; ?>dashboard/view?id=<?php echo $id; ?>" class="iconbutton" title="<?php echo _("View mode"); ?>">
        <span class="glyphicon glyphicon-eye-open"></span>
    </span>
    <span href="#myModal" id = "config-dashboard" role="button" data-toggle="modal" class="non_iconbutton" title="<?php echo _("Configure dashboard"); ?>">
        <span class="glyphicon glyphicon-wrench"></span>
    </span>
    <?php } ?>

    <span href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/create.json  ',data : '',dataType : 'json',success : location.reload()});" class="iconbutton" title="<?php echo _("New"); ?>">
        <span class="glyphicon glyphicon-plus-sign"></span>
    </span>

    <span href="<?php echo $path; ?>dashboard/list" class="iconbutton" title="<?php echo _('List view'); ?>">
        <span class="glyphicon glyphicon-th-list"></span>
    </span>   
</div>

<?php }
