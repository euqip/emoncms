<?php global $path, $emoncms_version; ?>
<h2><?php echo _('Administration panel'); ?></h2>

Emoncms version: <?php echo $emoncms_version; ?>


<div class="table-responsive">
  <table class="table">
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
    <?php
    if ((isset ($_SESSION['admin'])) && ($_SESSION['admin']==1)){
        ?>
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
            <a href="<?php echo $path; ?>admin/db" class="btn btn-info"><?php echo _('Update & check'); ?></a>
        </td>
        <td>
            <h3><?php echo _('Update database'); ?></h3>
            <p><?php echo _('Run this after updating emoncms, after installing a new module or to check emoncms database status.'); ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <a href="#" class="btn btn-info"><?php echo _('Log4php installed?'); ?></a>
        </td>
        <td>
            <h3><?php if(LOG4PHP_INSTALLED) echo _("yes"); else echo _("NO Log4php is not installed on this system"); ?></h3>
            <p><?php echo _('To install Log4PHP'); ?></p>
            <code>
                sudo pear channel-discover pear.apache.org/log4php <br />
                sudo pear install log4php/Apache_log4php <br />
            </code>
            ensure that log file has write permissions for www-data, pi and root.<br />
            <code>
                sudo chmod 660 emoncms.log<br /> 
            </code>
        </td>
    </tr>
        <?php
    }
    ?>
  </table>
</div>
