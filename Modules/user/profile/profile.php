<?php 

/*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
*/

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

  global $path; 
 
  $languages = get_available_languages();

function languagecodetotext()
{
  _('es_ES');
  _('fr_FR');
}

?>
    <script type="text/javascript" src="<?php echo $path; ?>Modules/user/profile/profile.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/user/profile/md5.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/user/user.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/listjs/list.js"></script>

<div class="row">

  <div class="col-md-5">
   <h3><?php echo _('My account'); ?></h3>

  <div id="account">

      <span class="text-muted"><?php echo _('Username'); ?></span>
      <span id ="edit-username" style="float:right; display:inline;" class='glyphicon glyphicon-pencil'  title = <?php echo _('Edit'); ?>></span>
      <span id ="save-username" style="float:right; display:none;" class='glyphicon glyphicon-floppy-save'  title = <?php echo _('Edit'); ?>></span>

      <p>
      <div id="username-view" style="display:inline">
        <span class="username"></span>
      </div>
      <div id="edit-username-form" style="display:none">
        <input class="username form-control" type="text" size ="40">
      </div>
      </p>
      <div id="change-username-error" class="alert alert-error" style="display:none; width:100%"></div> 


      <span class="text-muted"><?php echo _('Email'); ?></span>
        <span id ="edit-email" style="float:right; display:inline;" class='glyphicon glyphicon-pencil'  title = <?php echo _('Edit'); ?>></span>
        <span id ="save-email" style="float:right; display:none;" class='glyphicon glyphicon-floppy-save'  title = <?php echo _('Edit'); ?>></span>
      <p>
      <div id="email-view" style="display:inline">
        <span class="email"></span>
      </div>
      <div id="edit-email-form" style="display:none">
        <input class="email form-control" type="email" size="40">
      </div>
      </p>
      <div id="change-email-error" class="alert alert-error" style="display:none; width:100%"></div> 




    <p>
      <a id="changedetails"><?php echo _('Change Password'); ?></a>
    </p>
  </div>

        <div id="change-password-form" style="display:none">
          <div  class="form-group">
            <label for="oldpassword" class="text-muted"><?php echo _('Current password'); ?></label>
            <input id="oldpassword" type="password" class="form-control" placeholder="'<?php echo _("type Current password"); ?>'"/>
          </div>
          <div  class="form-group">
            <label for="newpassword" class="text-muted"><?php echo _('New password'); ?></label>
            <input id="newpassword" type="password" class="form-control" placeholder="'<?php echo _("type New password"); ?>'"/>
          </div>
          <div  class="form-group">
            <label for="repeatnewpassword" class="text-muted"><?php echo _('Repeat new password'); ?></label>
            <input id="repeatnewpassword" type="password" class="form-control" placeholder="'<?php echo _("retype New password"); ?>'"/>
          </div>
          <div id="change-password-error" class="alert alert-error" style="display:none"></div>
          <input id="change-password-submit" type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>" />
          <input id="change-password-cancel" type="submit" class="btn" value="<?php echo _('Cancel'); ?>" />
        </div>

  </div>



  <div class="col-md-7">
    <h3><?php echo _('My Profile'); ?></h3>
    <div id="table"></div>
  </div>

</div>

<script>

    var path = "<?php echo $path; ?>";
    var lang = <?php echo json_encode($languages); ?>;

    list.data = user.get();

    var currentlanguage = list.data.language;

    list.fields = {
      'gravatar':{'title':"<?php echo _('Gravatar'); ?>", 'type':'gravatar'},
      'name':{'title':"<?php echo _('Name'); ?>", 'type':'text'},
      'location':{'title':"<?php echo _('Location'); ?>", 'type':'text'},
      'timezone':{'title':"<?php echo _('Timezone'); ?>", 'type':'timezone'},
      'language':{'title':"<?php echo _('Language'); ?>", 'type':'select', 'options':lang},
      'bio':{'title':"<?php echo _('Bio'); ?>", 'type':'text'}

    }
      $(startprofile);
    </script>



