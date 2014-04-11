<?php global $path, $emoncms_version; ?>
<h2><?php echo _('Administration panel'); ?></h2>

Emoncms version: <?php echo $emoncms_version; ?>


<div class="table-responsive">
  <table class="table">
    <tr>
        <td>
            <br>
            <a href="<?php echo $path; ?>admin/orgs" class="btn btn-info"><?php echo _('Organisations'); ?></a>
        </td>
        <td>
            <h3><?php echo _('Organisations'); ?></h3>
            <p><?php echo _('Administer organisations'); ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <a href="<?php echo $path; ?>admin/users" class="btn btn-info"><?php echo _('Users'); ?></a>
        </td>
        <td>
            <h3><?php echo _('Users'); ?></h3>
            <p><?php echo _('Administer user accounts'); ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <a href="<?php echo $path; ?>admin/db" class="btn btn-info"><?php echo _('Update & check'); ?></a>
        </td>
        <td>
            <h3><?php echo _('Update database'); ?></h3>
            <p><?php echo _('Run this after updating emoncms, after installing a new module or to check emoncms database status.'); ?></p>
        </td>
    </tr>
  </table>
</div>


<table class="table table-striped ">
</table>

